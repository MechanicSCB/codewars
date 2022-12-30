<?php

namespace App\Classes\Train\Runners\Julia;


use App\Classes\Train\Runners\Abstract\LangSolutionValidator;

class JuliaSolutionValidator extends LangSolutionValidator
{
    public string $solutionFilename = 'Solution';

    public function getSolution():array
    {
        return parent::getSolution();
    }

    public function getModuleName(): string
    {
        if (!$moduleName = @explode("module ", $this->solutionCode)[1]) {
            return '';
        }

        $moduleName = str_replace(["\n", ' '], '|||', $moduleName);

        return explode('|||', $moduleName)[0];
    }
}
