<?php

namespace App\Classes\Train\Runners\Abstract;

use App\Models\Kata;

abstract class LangScriptGenerator
{
    protected string $scriptFilename = 'script';
    protected string $separator = "|separator|";
    protected string $ext;
    protected string $cmd;

    public function __construct(
        protected Kata $kata,
        protected string $solutionCode,
        protected string $lang,
        protected array $attempts,
        protected LangSolutionValidator $solutionValidator,
    )
    {
        $this->cmd ??= $this->lang;
        $this->ext ??= $this->lang;
    }

    public function getScript(): array
    {
        $script['code'] = $this->getScriptCode();

        $ext = $this->scriptExt ?? $this->ext;
        $script['filepath'] = "$this->scriptFilename.$ext";

        return $script;
    }

    public function getCommands(): array
    {
        $commands['compile_cmd'] = $this->getCompileCmd();
        $commands['exec_cmd'] = $this->getExecCmd();

        return $commands;
    }

    protected function getAttemptString(array $attempt): string
    {
        $argsString = $this->getArgsString($attempt);

        return "{$attempt['name']}($argsString)";
    }

    protected function getArgsString(array $attempt): string
    {
        $jsonArgs = json_encode($attempt['args']);
        $stringArgs = substr($jsonArgs, 1, -1);
        // TODO ref!
        $stringArgs = str_replace('\/', '/', $stringArgs);

        return $stringArgs;
    }

    protected function getCompileCmd(): ?string
    {
        return null;
    }

    protected function getExecCmd(): ?string
    {
        return "$this->cmd $this->scriptFilename.$this->ext 2>&1";
    }

    abstract protected function getScriptCode(): ?string;
}
