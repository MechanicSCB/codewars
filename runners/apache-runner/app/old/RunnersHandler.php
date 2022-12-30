<?php



class RunnersHandler
{
    protected $runner;

    public function getAttemptsResults(array $request)
    {
        // get lang runner
        if(! $this->runner = $this->getRunner($request)){
            return ["Lang runner not found!"];
        }

        // get solution file code and save solution to file
        $this->runner->saveSolutionToFile($request['code']);

        // get runner script code and save script to file
        $scriptCode = $this->runner->getScriptCode();
        $this->runner->saveScriptToFile($scriptCode);


        // compile the script if necessary
        if($compileOutput = $this->runner->compileScriptFile()){
            return [$compileOutput];
        }

        // get script exec raw output
        $execOutput = $this->runner->execScriptFile();

        // handle raw output
        $output = $this->runner->handleRawOutput($execOutput);

        // remove temp directory
        $this->runner->removeTempFolder();

        //$output =$execOutput;

        return $output;
    }

    protected function getRunner(array $request): ?LangRunner
    {
        $langSlug = ucfirst($request['lang']);
        $langRunnerClass = "{$langSlug}Runner";

        if (class_exists($langRunnerClass)) {
            $langRunner = new $langRunnerClass($request);
        } else {
            $langRunner = null;
        }

        return $langRunner;
    }


}
