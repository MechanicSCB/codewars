<?php

namespace App\Classes\Train\Runners\Java;


use App\Classes\Train\Runners\Abstract\LangSolutionValidator;

class JavaSolutionValidator extends LangSolutionValidator
{
    public function getSolution():array
    {
        $this->solutionFilename = $this->getClassName();

        return parent::getSolution();
    }

    public function getClassName(): string
    {
        $className = explode('{', $this->solutionCode)[0];
        $className = str_replace('interface', 'class', $className);
        $className = @explode('class ', $className)[1];
        return trim($className ?? 'Solution');
    }
}
