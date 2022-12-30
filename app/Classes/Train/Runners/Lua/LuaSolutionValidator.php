<?php

namespace App\Classes\Train\Runners\Lua;


use App\Classes\Train\Runners\Abstract\LangSolutionValidator;

class LuaSolutionValidator extends LangSolutionValidator
{
    public function getSolution():array
    {
        return parent::getSolution();
    }

    public function validate():string
    {
        return 'OK';
    }

}
