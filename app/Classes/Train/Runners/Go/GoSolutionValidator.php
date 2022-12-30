<?php

namespace App\Classes\Train\Runners\Go;


use App\Classes\Train\Runners\Abstract\LangSolutionValidator;

class GoSolutionValidator extends LangSolutionValidator
{
    public function getSolution():array
    {
        $packageName = $this->getPackageName();
        $this->solutionCode = str_replace("package $packageName", 'package main', $this->solutionCode);

        return parent::getSolution();
    }

    function getPackageName(): string
    {
        // removeDoubleSpaces;
        $code = preg_replace('/\s+/', ' ', $this->solutionCode);

        if (count($tmp = explode('package ', $code, 2)) !== 2) {
            return '';
        }

        $tmp = str_replace('//', ' //', $tmp[1]);
        $packageName = explode(' ', $tmp)[0];

        // check allowed package name
        if (! preg_match("/^[A-z][A-z\d_-]+$/", $packageName)) {
            return '';
        }

        return $packageName;
    }
}
