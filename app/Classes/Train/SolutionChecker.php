<?php

namespace App\Classes\Train;

use App\Classes\Train\Runners\Abstract\LangOutputParser;
use App\Classes\Train\Runners\Abstract\LangScriptGenerator;
use App\Classes\Train\Runners\Abstract\LangSolutionValidator;
use App\Models\Kata;

class SolutionChecker
{
    protected LangSolutionValidator $solutionValidator;

    public function __construct(
        private Kata $kata,
        private array $attempts,
        private string $solutionCode,
        private string $langSlug,
    )
    {
    }

    public function getSolutionEvalList(): array
    {
        $rawOutput = $this->getSolutionRawOutput();
        // df(tmr(@$this->start), $rawOutput);

        // parse output
        $outputParser = $this->getOutputParser($this->solutionCode, $this->langSlug);
        $evalList = $outputParser->parseRawOutput($rawOutput);

        return $evalList;
    }

    public function getSolutionRawOutput(): string
    {
        $this->solutionValidator = $this->getSolutionValidator();

        // validate solution
        if (($validationMessage = $this->solutionValidator->validate()) !== 'OK') {
            return $validationMessage;
        }

        // get solution file
        $files['solution'] = $this->solutionValidator->getSolution();

        // get script file
        if (! $scriptGenerator = $this->getScriptGenerator()) {
            return "scriptGenerator for lang: $this->langSlug was not found";
        }

        $files['script'] = $scriptGenerator->getScript();

        // get commands
        $commands = $scriptGenerator->getCommands();

        $rawOutput = (new SolutionEvaluator())->evalSolutionList($this->langSlug, $files, $commands);

        return $rawOutput;
    }

    private function getSolutionValidator(): LangSolutionValidator
    {
        $UcfirstLangSlug = ucfirst($this->langSlug);
        $langSolutionValidatorClass = "App\Classes\Train\Runners\\$UcfirstLangSlug\\{$UcfirstLangSlug}SolutionValidator";

        if (! class_exists($langSolutionValidatorClass)) {
            $langSolutionValidatorClass = "App\Classes\Train\Runners\Common\CommonSolutionValidator";
        }

        $langSolutionValidator = new $langSolutionValidatorClass($this->kata, $this->solutionCode, $this->langSlug);

        return $langSolutionValidator;
    }

    private function getScriptGenerator(): ?LangScriptGenerator
    {
        $UcfirstLangSlug = ucfirst($this->langSlug);
        $langScriptGeneratorClass = "App\Classes\Train\Runners\\$UcfirstLangSlug\\{$UcfirstLangSlug}ScriptGenerator";

        if (! class_exists($langScriptGeneratorClass)) {
            return null;
        }

        return new $langScriptGeneratorClass($this->kata, $this->solutionCode, $this->langSlug, $this->attempts, $this->solutionValidator);
    }

    private function getOutputParser(string $solutionCode, string $langSlug): LangOutputParser
    {
        $UcfirstLangSlug = ucfirst($this->langSlug);
        $className = "App\Classes\Train\Runners\\$UcfirstLangSlug\\{$UcfirstLangSlug}OutputParser";

        if (! class_exists($className)) {
            $className = "App\Classes\Train\Runners\Common\CommonOutputParser";
        }

        $outputParser = new $className($this->kata, $this->solutionCode, $this->langSlug, $this->attempts);

        return $outputParser;
    }
}
