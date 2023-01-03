<?php


namespace App\Runners;


use Illuminate\Http\Request;

class ScalaRunner extends LangRunner
{
    public function __construct(Request $request)
    {
        $this->ext = 'scala';
        $this->needCompile = true;

        parent::__construct($request);
        $this->solutionFileName = $this->getClassName();
        $this->scriptFileName = 'Script';
    }

    protected function getClassName(): string
    {
        if (! str_contains($_POST['code'], 'export function')) {
            $objectName = explode('{', $_POST['code'])[0];
            $objectName = @explode('object ', $objectName)[1];
            $objectName = trim($objectName);
        }

        if (! @$objectName) {
            echo json_encode(["object name is undefined"]);
            $this->removeTempFolder();
            die();
        }

        return $objectName;
    }

    public function getScriptCode(): string
    {
        $functionNames = [];

        foreach ($this->attempts as $attempt) {
            if (in_array($attempt['name'], $functionNames)) {
                continue;
            }

            $functionNames[] = $attempt['name'];
        }

        $script = '';

        foreach ($functionNames as $functionName) {
            $script .= "import $this->solutionFileName.$functionName\n";
        }

        $script .= "object Script {\ndef main(args: Array[String]) = {\n";

        foreach ($this->attempts as $attempt) {
            // Seq(121, 144, 19, 161, 19, 144, 19, 11)
            $attempt['string'] = str_replace(['[', ']'], ['Seq(', ')'], $attempt['string']);
            $script .= "\t\tprintln({$attempt['string']})\n";

            $script .= "\t\tprintln(\"$this->separator\")\n";
        }

        $script .= "\t}\n}";

        return $script;
    }

    public function compileScriptFile(): ?string
    {
        return shell_exec("cd $this->folder && scalac $this->solutionFileName.scala Script.scala 2>&1");
    }

    public function execScriptFile(): string
    {
        return shell_exec("cd $this->folder && scala Script 2>&1");
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
