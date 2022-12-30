<?php

namespace App\Classes\Train\Runners\Haxe;


use App\Classes\Train\Runners\Abstract\LangSolutionValidator;

class HaxeSolutionValidator extends LangSolutionValidator
{
    public function getSolution():array
    {
        $this->solutionFilename = $this->getSolutionFileName();

        return parent::getSolution();
    }

    public function getSolutionFileName()
    {
        $className = @explode('class ', $this->solutionCode)[1];
        $className = @explode('{', $className)[0];

        return trim( $className);
    }
}
