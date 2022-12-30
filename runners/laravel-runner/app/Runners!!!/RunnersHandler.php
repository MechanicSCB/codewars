<?php


namespace App\Runners;


use Illuminate\Http\Request;

class RunnersHandler
{
    protected ?LangRunner $runner;

    public function getAttemptsResults(Request $request)
    {
        // get lang runner
        if(! $this->runner = $this->getRunner($request)){
            return ["Lang runner not found!"];
        }

        // get solution file code and save solution to file
        $solutionCode = $this->runner->getSolutionCode();
        $this->runner->saveSolutionToFile($solutionCode);

        // get runner script code and save script to file
        $scriptCode = $this->runner->getScriptCode();
        $this->runner->saveScriptToFile($scriptCode);

        // compile the script if necessary
        if($compileErrors = $this->runner->compileScriptFile()){
            //$this->runner->removeTempFolder();
            return [$compileErrors];
        }

        // get script exec raw output
        $execOutput = $this->runner->execScriptFile();

        // handle raw output
        $output = $this->runner->handleRawOutput($execOutput);

        // remove temp directory
        $this->runner->removeTempFolder();

        //$output = $execOutput;

        return $output;
    }

    protected function getRunner(Request $request): ?LangRunner
    {
        $langSlug = ucfirst($request->lang);
        $langRunnerClass = "App\Runners\\{$langSlug}Runner";

        if (class_exists($langRunnerClass)) {
            $langRunner = new $langRunnerClass($request);
        } else {
            $langRunner = null;
        }

        return $langRunner;
    }
}
