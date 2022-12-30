<?php

namespace App\Classes\Train\Runners\Haskell;


use App\Classes\Train\Runners\Abstract\LangSolutionValidator;

class HaskellSolutionValidator extends LangSolutionValidator
{
    public function getSolution():array
    {
        $moduleName = $this->getModuleName();
        $moduleNameWithoutPoints = str_replace('.', '', $moduleName);
        $this->solutionFilename = $moduleNameWithoutPoints;
        $this->solutionCode = str_replace($moduleName, $moduleNameWithoutPoints, $this->solutionCode);

        return parent::getSolution();
    }

    function getModuleName(): string
    {
        $moduleName = explode(' where', $this->solutionCode)[0];
        $moduleName = @explode('module ', $moduleName)[1] ?? 'Solution';
        $moduleName = trim($moduleName);

        return $moduleName;
    }


}
