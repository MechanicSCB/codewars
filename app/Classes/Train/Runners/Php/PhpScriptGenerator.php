<?php

namespace App\Classes\Train\Runners\Php;


use App\Classes\Train\Runners\Abstract\LangScriptGenerator;

class PhpScriptGenerator extends LangScriptGenerator
{
    public function getScriptCode():string
    {
        return json_encode($this->attempts);
    }
}
