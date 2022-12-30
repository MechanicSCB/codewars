<?php

namespace App\Classes\Train\Runners\Fortran;


use App\Classes\Train\Runners\Abstract\LangSolutionValidator;

class FortranSolutionValidator extends LangSolutionValidator
{
    public function getSolution():array
    {
        return parent::getSolution();
    }
}
