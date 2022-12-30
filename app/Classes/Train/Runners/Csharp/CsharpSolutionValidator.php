<?php

namespace App\Classes\Train\Runners\Csharp;


use App\Classes\Train\Runners\Abstract\LangSolutionValidator;

class CsharpSolutionValidator extends LangSolutionValidator
{
    public function getSolution():array
    {
        return parent::getSolution();
    }

    public function getFunctionsInfo():array
    {
        $functionsInfo = [];

        // removeDoubleSpaces;
        $code = preg_replace('/\s+/', ' ', $this->solutionCode);

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

            $csharpTypes = [
                'byte', 'bool', 'short', 'int', 'long', 'double', 'float', 'char', 'string', 'list',
                'byte[]', 'bool[]', 'short[]', 'int[]', 'long[]', 'double[]', 'float[]', 'char[]', 'string[]',
                'list<string>', 'biginteger', 'int[][]',
            ];

            // check allowed type
            if (! in_array(strtolower($functionInfo['return_type']), $csharpTypes)) {
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
