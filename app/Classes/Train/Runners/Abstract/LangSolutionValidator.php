<?php

namespace App\Classes\Train\Runners\Abstract;

use App\Models\Kata;

abstract class LangSolutionValidator
{
    protected string $ext;
    public string $solutionFilename = 'solution';

    public function __construct(
        protected Kata $kata,
        protected string $solutionCode,
        protected string $lang,
    )
    {
        $this->ext = $this->getLangFileExtension();
    }

    public function validate():string
    {
        // check functions names matches
        foreach ($this->getFunctionNames() as $functionName){
            if(! str_contains($this->solutionCode, $functionName)){
                return "Error: function \"$functionName\" is not found in solution code!";
            }
        }

        return 'OK';
    }

    protected function getFunctionNames():array
    {
        if(! @$this->kata->sample){
            return [];
        }

        $functionsNames = array_unique(json_decode($this->kata->sample->function_names,1));

        return $functionsNames;
    }

    public function getSolution():array
    {
        $solution['code'] = $this->lang === 'php' ? '<?php ' . $this->solutionCode : $this->solutionCode;

        $ext = $this->solutionExt ?? $this->ext;
        $solution['filepath'] = "$this->solutionFilename.$ext";

        return $solution;
    }

    protected function getLangFileExtension():string
    {
        $extensions = [
            'c' => 'h',
            'clojure' => 'clj',
            'cpp' => 'hpp',
            'crystal' => 'cr',
            'python' => 'py',
            'ruby' => 'rb',
            'haxe' => 'hx',
            'haskell' => 'hs',
            'julia' => 'jl',
            'ocaml' => 'ml',
            'perl' => 'pm',
            'racket' => 'rkt',
            'raku' => 'rakumod',
            'rust' => 'rs',
            'typescript' => 'ts',
        ];

        $ext = $extensions[$this->lang] ?? $this->lang;

        return $ext;
    }
}
