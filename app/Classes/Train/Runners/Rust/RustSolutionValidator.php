<?php

namespace App\Classes\Train\Runners\Rust;


use App\Classes\Train\Runners\Abstract\LangSolutionValidator;

class RustSolutionValidator extends LangSolutionValidator
{
    public function getSolution():array
    {
        return parent::getSolution();
    }
}
