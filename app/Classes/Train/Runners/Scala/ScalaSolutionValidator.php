<?php

namespace App\Classes\Train\Runners\Scala;


use App\Classes\Train\Runners\Abstract\LangSolutionValidator;

class ScalaSolutionValidator extends LangSolutionValidator
{
    public function getSolution():array
    {
        $className = $this->getClassName();
        $this->solutionFilename = $className;
        $this->solutionCode = str_replace("package $className", 'package main', $this->solutionCode);

        return parent::getSolution();
    }

    function getClassName(): string
    {
        if (! str_contains($code = $this->solutionCode, 'export function')) {
            $objectName = explode('{', $code)[0];
            $objectName = @explode('object ', $objectName)[1];
            $objectName = trim($objectName);
        }

        return $objectName;
    }
}
