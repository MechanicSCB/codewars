<?php

namespace App\Classes\Train\Runners\Typescript;


use App\Classes\Train\Runners\Abstract\LangSolutionValidator;

class TypescriptSolutionValidator extends LangSolutionValidator
{
    public function getSolution():array
    {
        return parent::getSolution();
    }

    function getClassName(): string
    {
        if (! str_contains($this->solutionCode, 'export function')) {
            $className = explode('{', $this->solutionCode)[0];
            $className = str_replace('interface', 'class', $className);
            $className = @explode('class ', $className)[1];
            $className = trim($className);
        }

        return $className ?? '';
    }
}
