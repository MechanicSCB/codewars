<?php

namespace App\Classes\Train\Runners\Perl;


use App\Classes\Train\Runners\Abstract\LangSolutionValidator;

class PerlSolutionValidator extends LangSolutionValidator
{
    public function getSolution():array
    {
        $this->solutionFilename = $this->getSolutionFileName();

        return parent::getSolution();
    }

    public function getSolutionFileName(): string
    {
        $packageName = @explode('package ', $this->solutionCode)[1];
        $packageName = @explode(';', $packageName)[0];
        $packageName = trim( $packageName);

        return $packageName;
    }

}
