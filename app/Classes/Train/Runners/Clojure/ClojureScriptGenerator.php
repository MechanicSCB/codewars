<?php

namespace App\Classes\Train\Runners\Clojure;


use App\Classes\Train\Runners\Abstract\LangScriptGenerator;
use Illuminate\Support\Str;

class ClojureScriptGenerator extends LangScriptGenerator
{
    protected string $ext = 'clj';
    protected string $cmd = 'clj -M';

    public function getScriptCode():string
    {
        $this->solutionCode = str_replace('( ns ', '(ns ', $this->solutionCode);

        $ns = $this->getNamespace($this->solutionCode);

        $script = str_replace("(ns $ns", "(ns $ns (:require [clojure.data.json :as json])", $this->solutionCode);
        $script .= "\n";

        foreach ($this->attempts as $attempt) {
            $attemptString = $this->getAttemptString($attempt);
            $attemptString = str_replace(['(', ')', ','], ' ', $attemptString);
            $script .= "(json/print-json ($attemptString))\n";
            $script .= "(println \"$this->separator\")\n";
        }

        return $script;
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
