<?php


namespace App\Runners;


use Illuminate\Http\Request;

class JavaRunner extends LangRunner
{
    public function __construct(Request $request)
    {
        $this->ext = 'java';
        $this->needCompile = true;

        parent::__construct($request);
        $this->solutionFileName = $this->getClassName();

    }

    protected function getClassName(): string
    {
        $className = explode('{', $this->solutionCode)[0];
        $className = str_replace('interface', 'class', $className);
        $className = @explode('class ', $className)[1];
        return trim($className ?? 'Solution');
    }

    public function getScriptCode(): string
    {
        $script = "import java.util.Arrays;\nclass script {\npublic static void main(String[] args) {\n";

        if(count($functionsInfo = $this->getFunctionsInfo($this->solutionCode)) === 0){
            echo json_encode(['ERROR: no public static method found']);
            $this->removeTempFolder();
            die();
        }

        foreach ($this->attempts as $key => $attempt) {
            $attempt['string'] = str_replace([']', '['], ['}', 'new int[]{'], $attempt['string']);
            //$attempt['string'] = str_replace([']', '['], ['}', 'new String[]{'], $attempt['string']);
            //$attempt['string'] = str_replace([']', '['], [')', 'Arrays.asList('], $attempt['string']);

            if(str_ends_with($functionsInfo[$attempt['name']]['return_type'] ?? '', '[]')){
                $script .= "System.out.println(Arrays.toString($this->solutionFileName.{$attempt['string']}));\n";
            }else{
                $script .= "System.out.println($this->solutionFileName.{$attempt['string']});\n";
            }

            $script .= "System.out.println(\"$this->separator\");\n";
        }

        $script .= "\t}\n}";

        return $script;
    }

    public function execScriptFile(): string
    {
        return shell_exec("cd $this->folder && java script 2>&1");
    }

    protected function getFunctionsInfo(string $code): array
    {
        $functionsInfo = [];

        // removeDoubleSpaces;
        $code = preg_replace('/\s+/', ' ', $code);

        // TODO ? change to preg function because of 'static' without public possibility
        $code = str_replace(['public static ', '{ static ', "{static "], '|separator|', $code);
        $rows = explode('|separator|', $code);

        if (count($rows) < 2) {
            return $functionsInfo;
        }

        array_shift($rows);

        $functionsInfo = [];

        foreach ($rows as $row) {
            $functionInfo = [];

            if (count($tmp = explode(' ', $row, 2)) !== 2) {
                continue;
            }

            [$functionInfo['return_type'], $tail] = $tmp;
            $functionInfo['return_type'] = trim($functionInfo['return_type']);

            $javaTypes = [
                'byte', 'boolean', 'short', 'int', 'long', 'double', 'float', 'char', 'string', 'list',
                'byte[]', 'boolean[]', 'short[]', 'int[]', 'long[]', 'double[]', 'float[]', 'char[]', 'string[]',
                'list<string>', 'biginteger', 'int[][]',
            ];

            // check allowed type
            if (! in_array(strtolower($functionInfo['return_type']), $javaTypes)) {
                continue;
            }

            if (count($tmp = explode('(', $tail, 2)) !== 2) {
                continue;
            }

            [$functionInfo['name'], $tail] = $tmp;
            $functionInfo['name'] = trim($functionInfo['name']);

            // check allowed function name
            if (! preg_match("/^[A-z][A-z\d_-]+$/", $functionInfo['name'])) {
                continue;
            }

            if (count($tmp = explode(')', $tail, 2)) !== 2) {
                continue;
            }

            [$args, $tail] = $tmp;
            $args = trim($args);
            $args = explode(',', $args);

            foreach ($args as $arg) {
                $arg = trim($arg);

                if (count($tmp = explode(' ', $arg, 2)) !== 2) {
                    continue;
                }

                $functionInfo['args'][] = [
                    'type' => $tmp[0],
                    'name' => $tmp[1],
                ];
            }

            $functionsInfo[$functionInfo['name']] = $functionInfo;
        }

        return $functionsInfo;
    }

}
