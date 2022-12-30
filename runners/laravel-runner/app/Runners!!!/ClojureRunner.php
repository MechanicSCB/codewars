<?php


namespace App\Runners;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ClojureRunner extends LangRunner
{
    public function __construct(Request $request)
    {
        $this->ext = 'clj';
        $this->cmdName = 'clj -M';
        $this->hasSolutionFile = false;
        parent::__construct($request);
    }

    public function getScriptCode(): string
    {
        $this->solutionCode = str_replace('( ns ', '(ns ', $this->solutionCode);

        if (! $ns = $this->getNamespace($this->solutionCode)) {
            echo json_encode(["error: namespace is not defined!\n<br> please define namespace like: (ns kata)"]);
            $this->removeTempFolder();
            die();
        }

        $script = str_replace("(ns $ns", "(ns $ns (:require [clojure.data.json :as json])", $this->solutionCode);
        $script .= "\n";

        foreach ($this->attempts as $attempt) {
            $attempt['string'] = str_replace(['(', ')', ','], ' ', $attempt['string']);
            $script .= "(json/print-json ({$attempt['string']}))\n";
            $script .= "(println \"$this->separator\")\n";
        }

        return $script;
    }

    public function execScriptFile(): string
    {
        $sourceFolder = Str::beforeLast($this->folder, '/tmp') . '/src/clojure';
        File::copyDirectory($sourceFolder, $this->folder);

        return parent::execScriptFile();
    }

    private function getNamespace(string $code): ?string
    {
        if (! $ns = @explode('(ns ', $code)[1]) {
            return null;
        }

        $ns = str_replace([')', ' '], '|||', $ns);

        return explode('|||', $ns)[0];
    }
}
