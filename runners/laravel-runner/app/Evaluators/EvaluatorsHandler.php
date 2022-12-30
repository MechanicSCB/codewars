<?php

namespace App\Evaluators;


use Illuminate\Http\Request;

class EvaluatorsHandler
{
    protected ?LangEvaluator $evaluator;

    public function getEvaluatorOutput(Request $request): string
    {
        // get lang evaluator
        $this->evaluator = new LangEvaluator($request);

        // save files to temporary folder
        $this->evaluator->saveSolutionFile();
        $this->evaluator->saveScriptFile();

        // compile the script if necessary
        if ($compileErrors = $this->evaluator->compileScriptFile()) {
            $this->evaluator->removeTempFolder();

            return $compileErrors;
        }

        //get script exec raw output
        if ($request['lang'] === 'php') {
            $output = $this->evaluator->getPhpOutput();
        } else {
            $output = $this->evaluator->execScriptFile();
        }

        // remove temp directory
        $this->evaluator->removeTempFolder();

        return $output;
    }

}
