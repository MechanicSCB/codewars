<?php


namespace App\Classes\Train;


use App\Models\Kata;

class KataHandler
{
    public function getPreload(Kata $kata): array
    {
        $functionNames ??= $this->getFunctionNames($kata);

        $preload = [
            'common' => $this->getCommonPreload($functionNames),
            'php' => $this->getLangPreload($functionNames, 'php'),
            'python' => $this->getLangPreload($functionNames, 'python'),
            'javascript' => $this->getLangPreload($functionNames, 'javascript'),
        ];

        return $preload;
    }

    private function getFunctionNames(Kata $kata): array
    {
        $functionNames = @json_decode($kata->sample->function_names) ?? [];
        $functionNames = array_values(array_unique($functionNames));

        return $functionNames;
    }

    private function getLangPreload(array $functionNames, string $langSlug): string
    {
        $langPreloadTemplates = [
            'php' => "function {FUNCTION_NAME}(your_args) {\n\t// your code here\n}\n\n",
            'python' => "def {FUNCTION_NAME}(your_args):\n\t// your code here\n\n",
            'javascript' => "function {FUNCTION_NAME}(your_args) {\n\t// your code here\n}\n\n",
        ];
        $langPreload = '';

        foreach ($functionNames as $functionName) {
            $langPreload .= str_replace('{FUNCTION_NAME}', $functionName, $langPreloadTemplates[$langSlug]);
        }

        return $langPreload;
    }

    private function getCommonPreload(array $functionNames): string
    {
        $commonPreload = "// use function names:";

        foreach ($functionNames as $functionName) {
            $commonPreload .= " $functionName,";
        }

        return substr($commonPreload, 0, -1) . "\n";
    }
}
