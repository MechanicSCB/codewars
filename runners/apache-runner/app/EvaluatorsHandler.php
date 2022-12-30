<?php

use Illuminate\Http\Request;

class EvaluatorsHandler
{
    protected ?LangEvaluator $evaluator;

    public function getEvaluatorOutput($request): string
    {
        // get lang evaluator
        $this->evaluator = new LangEvaluator($request);

        // save files to temporary folder
        $this->evaluator->saveSolutionFile();
        $this->evaluator->saveScriptFile();

        // compile the script if necessary
        if ($compileErrors = $this->evaluator->compileScriptFile()) {
            // skip haskell compile message
            if(! str_contains($compileErrors, 'Linking script ...')){
                $this->evaluator->removeTempFolder();

                return $compileErrors;
            }
        }

        //get script exec raw output
        $output = $this->evaluator->execScriptFile();

        // remove temp directory
        $this->evaluator->removeTempFolder();

        return $output;
    }

}
