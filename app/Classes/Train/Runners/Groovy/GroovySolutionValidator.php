<?php

namespace App\Classes\Train\Runners\Groovy;


use App\Classes\Train\Runners\Abstract\LangSolutionValidator;

class GroovySolutionValidator extends LangSolutionValidator
{
    public function getSolution():array
    {
        $this->solutionFilename = $this->getSolutionFileName();

        return parent::getSolution();
    }

    public function getSolutionFileName(): string
    {
        $className = explode('{', $this->solutionCode)[0];
        $className = @explode('class', $className)[1];
        $className = trim( $className ?? '');

        return $className;
    }

}
