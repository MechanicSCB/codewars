<?php


namespace App\Runners;


use Faker\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class PhpRunner extends LangRunner
{
    public function __construct(Request $request)
    {
        $this->ext = 'php';
        parent::__construct($request);
    }

    public function getSolutionCode(): string
    {
        return "<?php {$this->request->code}";
    }

    public function getScriptCode(): string
    {
        return '';
        //$script = "<?php include '$this->folder/solution.php';\n";
        //
        //foreach ($this->attempts as $attempt){
        //    $script .= "echo json_encode({$attempt['string']});\n";
        //    $script .= "echo \"$this->separator\";\n";
        //}
        //
        //return $script;
    }

    public function execScriptFile(): string
    {
        include "$this->folder/solution.php";


        $output = [];

        foreach ($this->attempts as $attempt){
            $output[] = $attempt['name'](...$attempt['args']);
        }

        return json_encode($output);
    }

    public function handleRawOutput(string $shellOutput): array
    {
        return json_decode($shellOutput);
    }


}
