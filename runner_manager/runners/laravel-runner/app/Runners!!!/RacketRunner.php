<?php


namespace App\Runners;


class RacketRunner extends LangRunner
{
    protected string $ext = 'rkt';

    public function getScriptCode(): string
    {
        $script = "#lang racket\n(require json)\n(require \"solution.rkt\")\n\n";

        foreach ($this->attempts as $attempt){
            $script .= "(write-json({$attempt['name']}";

            foreach ($attempt['args'] as $arg){
                // TODO refactor to recursive array transform
                if(is_array($arg)){
                    $racketArray = implode(' ',$arg);
                    $script .= " '($racketArray)";
                }else{
                    $script .= ' ' . json_encode($arg);
                }
            }

            $script .= "))\n";
            $script .= "\"$this->separator\"\n";
        }

        return $script;
    }

    protected function getSeparatorToExplode(): string
    {
        return "\"$this->separator\"";
    }

}
