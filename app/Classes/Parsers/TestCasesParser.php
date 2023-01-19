<?php


namespace App\Classes\Parsers;


use App\Models\Kata;
use DiDom\Document;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TestCasesParser
{
    public function getDataFromRubyTestCasesBodies(array $katasIds = [])
    {
        $testCases = json_decode(file_get_contents(base_path("database/data/json/test_cases/ruby.json")), 1);

        $data = [];
        $invalidFunctionNames = [];

        foreach ($testCases as $kataId => $testCase) {
            if ($kataId !== '5796f686c38ec292c50006c3') {
                //continue;
            }

            if (count($katasIds) && ! in_array($kataId, $katasIds)) {
                continue;
            }

            $asserts = explode('assert_equals(', $testCase);
            array_shift($asserts);

            foreach ($asserts as $key => $assert) {
                $assert = str_replace(");\n", ")\n", $assert);
                $assert = Str::before($assert, ")\n");
                $assert = rtrim($assert, ')'); // if assert on last line

                $functionName = Str::before($assert, '(');
                $functionName = trim($functionName);

                if (! checkFunctionName($functionName)) {
                    $invalidFunctionNames[$kataId][] = $assert;
                    continue;
                }

                $args = Str::after($assert, $functionName . '(');
                $closeParentPos = $this->getArgumentsCloseParenthesisPosition($args);
                $args = substr($args, 0, $closeParentPos);

                $tail = str_replace("$functionName($args)", '', $assert);
                $tail = trim($tail, " ,\n");
                $expected = $this->getExpected($tail);
                $expected = str_replace("'", '"', $expected);
                $expected = str_replace(['True', 'False'], ['true', 'false'], $expected);
                $expected = str_replace("\n", '\n', $expected);
                $expected = trim($expected);

                // replace list round parenthesis to []
                if (str_starts_with($expected, '(') && str_ends_with($expected, ')')) {
                    $expected = substr_replace($expected, '[', 0, 1);
                    $expected = substr_replace($expected, ']', -1, 1);
                }

                //df(tmr(@$this->start), $args, $expected);

                if (! str_contains($args, '"')) {
                    $args = str_replace("'", '"', $args);
                }

                $data[$kataId]['args_list'][] = json_decode("[$args]");
                $data[$kataId]['expected_list'][] = json_decode($expected);
                $data[$kataId]['function_names'][] = $functionName;
                //df(tmr(@$this->start),$assert, $args, $expected, $data);
            }

            if (isset($data[$kataId])) {
                if (@array_unique(@$data[$kataId]['args_list']) === [null] || json_encode($data[$kataId]['args_list']) === false) {
                    unset($data[$kataId]);

                    continue;
                }

                if (count(array_unique($data[$kataId]['function_names'])) === 1) {
                    $data[$kataId]['function_names'] = array_unique($data[$kataId]['function_names']);
                }

                $data[$kataId]['kata_id'] = $kataId;
                $data[$kataId]['args_list'] = json_encode($data[$kataId]['args_list']);
                $data[$kataId]['expected_list'] = json_encode($data[$kataId]['expected_list']);
                $data[$kataId]['function_names'] = json_encode($data[$kataId]['function_names']);


                //if($data[$kataId]['args_list'] === "[null,null]"){
                //    df(tmr(@$this->start), $data);
                //}
            }
        }

        //df(tmr(@$this->start), $data, $invalidFunctionNames);

        return $data;
    }

    public function getDataFromJuliaTestCasesBodies(array $katasIds = [])
    {
        $testCases = json_decode(file_get_contents(base_path("database/data/json/test_cases/julia.json")), 1);

        $data = [];
        $invalidFunctionNames = [];

        foreach ($testCases as $kataId => $testCase) {
            if ($kataId !== '61afca9787f340002a9d8ada') {
                //continue;
            }

            $asserts = explode('@fact ', $testCase);
            array_shift($asserts);

            foreach ($asserts as $key => $assert) {
                $assert = Str::before($assert, "\n");
                $assert = rtrim($assert, ';'); // if assert on last line

                $functionName = Str::before($assert, '(');
                $functionName = trim($functionName);


                if (! checkFunctionName($functionName)) {
                    $invalidFunctionNames[$kataId][] = $assert;
                    continue;
                }

                //$assert = str_replace(["[]string", "[]int", "uint64"], "", $assert);
                //$assert = str_replace(["{", "}"], [ "[", "]"], $assert);

                $args = Str::after($assert, $functionName . '(');
                $closeParentPos = $this->getArgumentsCloseParenthesisPosition($args);
                $args = substr($args, 0, $closeParentPos);
                //df(tmr(@$this->start), $args);

                $expected = Str::after($assert, ' --> ');

                $expected = str_replace("'", '"', $expected);
                $expected = str_replace(['True', 'False'], ['true', 'false'], $expected);
                $expected = str_replace("\n", '\n', $expected);
                $expected = trim($expected);

                if($expected === ""){
                    $expected = Str::after($assert, '),');
                    $expected = trim($expected, ', ');
                }

                // replace list round parenthesis to []
                if (str_starts_with($expected, '(') && str_ends_with($expected, ')')) {
                    $expected = substr_replace($expected, '[', 0, 1);
                    $expected = substr_replace($expected, ']', -1, 1);
                }

                //df(tmr(@$this->start), $args, $expected);

                if (! str_contains($args, '"')) {
                    $args = str_replace("'", '"', $args);
                }

                $data[$kataId]['args_list'][] = json_decode("[$args]");
                $data[$kataId]['expected_list'][] = json_decode($expected);
                $data[$kataId]['function_names'][] = $functionName;
                //df(tmr(@$this->start),$assert, $args, $expected, $data);
            }

            if (isset($data[$kataId])) {
                if (@array_unique(@$data[$kataId]['args_list']) === [null] || json_encode($data[$kataId]['args_list']) === false) {
                    unset($data[$kataId]);

                    continue;
                }

                if (count(array_unique($data[$kataId]['function_names'])) === 1) {
                    $data[$kataId]['function_names'] = array_unique($data[$kataId]['function_names']);
                }

                $data[$kataId]['kata_id'] = $kataId;
                $data[$kataId]['args_list'] = json_encode($data[$kataId]['args_list']);
                $data[$kataId]['expected_list'] = json_encode($data[$kataId]['expected_list']);
                $data[$kataId]['function_names'] = json_encode($data[$kataId]['function_names']);


                //if($data[$kataId]['args_list'] === "[null,null]"){
                //    df(tmr(@$this->start), $data);
                //}
            }
        }

        //df(tmr(@$this->start), $data, $invalidFunctionNames);

        return $data;
    }

    public function getDataFromGroovyTestCasesBodies(array $katasIds = [])
    {
        $testCases = json_decode(file_get_contents(base_path("database/data/json/test_cases/groovy.json")), 1);

        $data = [];
        $invalidFunctionNames = [];

        foreach ($testCases as $kataId => $testCase) {
            if ($kataId !== '61afca9787f340002a9d8ada') {
                //continue;
            }

            $asserts = explode('assert ', $testCase);
            array_shift($asserts);

            foreach ($asserts as $key => $assert) {
                $assert = Str::before($assert, "\n");
                $assert = rtrim($assert, ';'); // if assert on last line

                $functionName = Str::before($assert, '(');
                $functionName = Str::after($functionName, '.');
                $functionName = trim($functionName);


                if (! checkFunctionName($functionName)) {
                    $invalidFunctionNames[$kataId][] = $assert;
                    continue;
                }

                //$assert = str_replace(["[]string", "[]int", "uint64"], "", $assert);
                //$assert = str_replace(["{", "}"], [ "[", "]"], $assert);

                $args = Str::after($assert, $functionName . '(');
                $closeParentPos = $this->getArgumentsCloseParenthesisPosition($args);
                $args = substr($args, 0, $closeParentPos);
                //df(tmr(@$this->start), $args);

                $expected = Str::after($assert, ' == ');

                $expected = str_replace("'", '"', $expected);
                $expected = str_replace(['True', 'False'], ['true', 'false'], $expected);
                $expected = str_replace("\n", '\n', $expected);
                $expected = trim($expected);

                if($expected === ""){
                    $expected = Str::after($assert, '),');
                    $expected = trim($expected, ', ');
                }

                // replace list round parenthesis to []
                if (str_starts_with($expected, '(') && str_ends_with($expected, ')')) {
                    $expected = substr_replace($expected, '[', 0, 1);
                    $expected = substr_replace($expected, ']', -1, 1);
                }

                //df(tmr(@$this->start), $args, $expected);

                if (! str_contains($args, '"')) {
                    $args = str_replace("'", '"', $args);
                }

                $data[$kataId]['args_list'][] = json_decode("[$args]");
                $data[$kataId]['expected_list'][] = json_decode($expected);
                $data[$kataId]['function_names'][] = $functionName;
                //df(tmr(@$this->start),$assert, $args, $expected, $data);
            }

            if (isset($data[$kataId])) {
                if (@array_unique(@$data[$kataId]['args_list']) === [null] || json_encode($data[$kataId]['args_list']) === false) {
                    unset($data[$kataId]);

                    continue;
                }

                if (count(array_unique($data[$kataId]['function_names'])) === 1) {
                    $data[$kataId]['function_names'] = array_unique($data[$kataId]['function_names']);
                }

                $data[$kataId]['kata_id'] = $kataId;
                $data[$kataId]['args_list'] = json_encode($data[$kataId]['args_list']);
                $data[$kataId]['expected_list'] = json_encode($data[$kataId]['expected_list']);
                $data[$kataId]['function_names'] = json_encode($data[$kataId]['function_names']);


                //if($data[$kataId]['args_list'] === "[null,null]"){
                //    df(tmr(@$this->start), $data);
                //}
            }
        }

        //df(tmr(@$this->start), $data, $invalidFunctionNames);

        return $data;
    }

    public function getDataFromGoTestCasesBodies(array $katasIds = [])
    {
        $testCases = json_decode(file_get_contents(base_path("database/data/json/test_cases/go.json")), 1);

        $data = [];
        $invalidFunctionNames = [];

        foreach ($testCases as $kataId => $testCase) {
            if ($kataId !== '61afca9787f340002a9d8ada') {
                //continue;
            }

            $asserts = explode('Expect(', $testCase);
            array_shift($asserts);

            foreach ($asserts as $key => $assert) {
                $assert = str_replace(");\n", ")\n", $assert);
                $assert = Str::before($assert, ")\n");
                $assert = rtrim($assert, ';'); // if assert on last line

                $functionName = Str::before($assert, '(');
                $functionName = trim($functionName);


                if (! checkFunctionName($functionName)) {
                    $invalidFunctionNames[$kataId][] = $assert;
                    continue;
                }

                $assert = str_replace(["[]string", "[]int", "uint64"], "", $assert);
                $assert = str_replace(["{", "}"], [ "[", "]"], $assert);

                $args = Str::after($assert, $functionName . '(');
                $closeParentPos = $this->getArgumentsCloseParenthesisPosition($args);
                $args = substr($args, 0, $closeParentPos);
                //df(tmr(@$this->start), $args);

                $expected = Str::after($assert, 'Equal(');
                $closeParentPos = $this->getArgumentsCloseParenthesisPosition($expected);
                $expected = substr($expected, 0, $closeParentPos);

                $expected = str_replace("'", '"', $expected);
                $expected = str_replace(['True', 'False'], ['true', 'false'], $expected);
                $expected = str_replace("\n", '\n', $expected);
                $expected = trim($expected);

                if($expected === ""){
                    $expected = Str::after($assert, '),');
                    $expected = trim($expected, ', ');
                }

                // replace list round parenthesis to []
                if (str_starts_with($expected, '(') && str_ends_with($expected, ')')) {
                    $expected = substr_replace($expected, '[', 0, 1);
                    $expected = substr_replace($expected, ']', -1, 1);
                }

                //df(tmr(@$this->start), $args, $expected);

                if (! str_contains($args, '"')) {
                    $args = str_replace("'", '"', $args);
                }

                $data[$kataId]['args_list'][] = json_decode("[$args]");
                $data[$kataId]['expected_list'][] = json_decode($expected);
                $data[$kataId]['function_names'][] = $functionName;
                //df(tmr(@$this->start),$assert, $args, $expected, $data);
            }

            if (isset($data[$kataId])) {
                if (@array_unique(@$data[$kataId]['args_list']) === [null] || json_encode($data[$kataId]['args_list']) === false) {
                    unset($data[$kataId]);

                    continue;
                }

                if (count(array_unique($data[$kataId]['function_names'])) === 1) {
                    $data[$kataId]['function_names'] = array_unique($data[$kataId]['function_names']);
                }

                $data[$kataId]['kata_id'] = $kataId;
                $data[$kataId]['args_list'] = json_encode($data[$kataId]['args_list']);
                $data[$kataId]['expected_list'] = json_encode($data[$kataId]['expected_list']);
                $data[$kataId]['function_names'] = json_encode($data[$kataId]['function_names']);


                //if($data[$kataId]['args_list'] === "[null,null]"){
                //    df(tmr(@$this->start), $data);
                //}
            }
        }

        //df(tmr(@$this->start), $data, $invalidFunctionNames);

        return $data;
    }

    public function getDataFromDartlTestCasesBodies(array $katasIds = [])
    {
        $testCases = json_decode(file_get_contents(base_path("database/data/json/test_cases/dart.json")), 1);

        $data = [];
        $invalidFunctionNames = [];

        foreach ($testCases as $kataId => $testCase) {
            if ($kataId !== '61afca9787f340002a9d8ada') {
                //continue;
            }

            $asserts = explode('expect(', $testCase);
            array_shift($asserts);

            foreach ($asserts as $key => $assert) {
                $assert = str_replace(");\n", ")\n", $assert);
                $assert = Str::before($assert, ")\n");
                $assert = rtrim($assert, ';'); // if assert on last line

                $functionName = Str::before($assert, '(');
                $functionName = trim($functionName);


                if (! checkFunctionName($functionName)) {
                    $invalidFunctionNames[$kataId][] = $assert;
                    continue;
                }

                $args = Str::after($assert, $functionName . '(');
                $closeParentPos = $this->getArgumentsCloseParenthesisPosition($args);
                $args = substr($args, 0, $closeParentPos);
                //df(tmr(@$this->start), $args);

                $expected = Str::after($assert, 'equals(');
                $closeParentPos = $this->getArgumentsCloseParenthesisPosition($expected);
                $expected = substr($expected, 0, $closeParentPos);

                $expected = str_replace("'", '"', $expected);
                $expected = str_replace(['True', 'False'], ['true', 'false'], $expected);
                $expected = str_replace("\n", '\n', $expected);
                $expected = trim($expected);

                if($expected === ""){
                    $expected = Str::after($assert, '),');
                    $expected = trim($expected, ', ');
                }

                // replace list round parenthesis to []
                if (str_starts_with($expected, '(') && str_ends_with($expected, ')')) {
                    $expected = substr_replace($expected, '[', 0, 1);
                    $expected = substr_replace($expected, ']', -1, 1);
                }

                //df(tmr(@$this->start), $args, $expected);

                if (! str_contains($args, '"')) {
                    $args = str_replace("'", '"', $args);
                }

                $data[$kataId]['args_list'][] = json_decode("[$args]");
                $data[$kataId]['expected_list'][] = json_decode($expected);
                $data[$kataId]['function_names'][] = $functionName;
                //df(tmr(@$this->start),$assert, $args, $expected, $data);
            }

            if (isset($data[$kataId])) {
                if (@array_unique(@$data[$kataId]['args_list']) === [null] || json_encode($data[$kataId]['args_list']) === false) {
                    unset($data[$kataId]);

                    continue;
                }

                if (count(array_unique($data[$kataId]['function_names'])) === 1) {
                    $data[$kataId]['function_names'] = array_unique($data[$kataId]['function_names']);
                }

                $data[$kataId]['kata_id'] = $kataId;
                $data[$kataId]['args_list'] = json_encode($data[$kataId]['args_list']);
                $data[$kataId]['expected_list'] = json_encode($data[$kataId]['expected_list']);
                $data[$kataId]['function_names'] = json_encode($data[$kataId]['function_names']);


                //if($data[$kataId]['args_list'] === "[null,null]"){
                //    df(tmr(@$this->start), $data);
                //}
            }
        }

        //df(tmr(@$this->start), $data, $invalidFunctionNames);

        return $data;
    }

    public function getDataFromCsharplTestCasesBodies(array $katasIds = [])
    {
        $testCases = json_decode(file_get_contents(base_path("database/data/json/test_cases/csharp.json")), 1);

        $data = [];
        $invalidFunctionNames = [];

        foreach ($testCases as $kataId => $testCase) {
            if ($kataId !== '5cf7f4f411bd8c0010394124') {
                //continue;
            }

            $asserts = explode('.AreEqual(', $testCase);
            array_shift($asserts);

            foreach ($asserts as $key => $assert) {
                $assert = str_replace(");\n", ")\n", $assert);
                $assert = Str::before($assert, ")\n");
                $assert = rtrim($assert, ';'); // if assert on last line

                $functionName = Str::after($assert, 'Kata.');
                $functionName = Str::before($functionName, '(');
                $functionName = trim($functionName);

                if (! checkFunctionName($functionName)) {
                    $invalidFunctionNames[$kataId][] = $assert;
                    continue;
                }

                $assert = str_replace(["new[]", "{", "}"], ["", "[", "]"], $assert);


                $args = Str::after($assert, $functionName . '(');
                $closeParentPos = $this->getArgumentsCloseParenthesisPosition($args);
                $args = substr($args, 0, $closeParentPos);
                //df(tmr(@$this->start), $args);

                $expected = Str::before($assert, 'Kata.');
                $expected = trim($expected, ', ');

                if($expected === ""){
                    $expected = Str::after($assert, '),');
                    $expected = trim($expected, ', ');
                }


                // replace list round parenthesis to []
                if (str_starts_with($expected, '(') && str_ends_with($expected, ')')) {
                    $expected = substr_replace($expected, '[', 0, 1);
                    $expected = substr_replace($expected, ']', -1, 1);
                }

                //df(tmr(@$this->start), $args, $expected);

                if (! str_contains($args, '"')) {
                    $args = str_replace("'", '"', $args);
                }

                $data[$kataId]['args_list'][] = json_decode("[$args]");
                $data[$kataId]['expected_list'][] = json_decode($expected);
                $data[$kataId]['function_names'][] = $functionName;
                //df(tmr(@$this->start),$assert, $args, $expected, $data);
            }

            if (isset($data[$kataId])) {
                if (@array_unique(@$data[$kataId]['args_list']) === [null] || json_encode($data[$kataId]['args_list']) === false) {
                    unset($data[$kataId]);

                    continue;
                }

                if (count(array_unique($data[$kataId]['function_names'])) === 1) {
                    $data[$kataId]['function_names'] = array_unique($data[$kataId]['function_names']);
                }

                $data[$kataId]['kata_id'] = $kataId;
                $data[$kataId]['args_list'] = json_encode($data[$kataId]['args_list']);
                $data[$kataId]['expected_list'] = json_encode($data[$kataId]['expected_list']);
                $data[$kataId]['function_names'] = json_encode($data[$kataId]['function_names']);


                //if($data[$kataId]['args_list'] === "[null,null]"){
                //    df(tmr(@$this->start), $data);
                //}
            }
        }

        //df(tmr(@$this->start), $data, $invalidFunctionNames);

        return $data;
    }

    public function getDataFromCppTestCasesBodies(array $katasIds = [])
    {
        $testCases = json_decode(file_get_contents(base_path("database/data/json/test_cases/cpp.json")), 1);

        $data = [];
        $invalidFunctionNames = [];

        foreach ($testCases as $kataId => $testCase) {
            if ($kataId !== '5796f686c38ec292c50006c3') {
                //continue;
            }

            if (count($katasIds) && ! in_array($kataId, $katasIds)) {
                continue;
            }

            $asserts = explode('Assert::That(', $testCase);
            array_shift($asserts);

            foreach ($asserts as $key => $assert) {
                $assert = str_replace(");\n", ")\n", $assert);
                $assert = Str::before($assert, ")\n");
                $assert = rtrim($assert, ');'); // if assert on last line

                $functionName = Str::before($assert, '(');
                $functionName = trim($functionName);

                if (! checkFunctionName($functionName)) {
                    $invalidFunctionNames[$kataId][] = $assert;
                    continue;
                }

                $args = Str::after($assert, $functionName . '(');
                $args = Str::before($args, 'Equals');
                $args = rtrim($args, '), ');

                $expected = Str::after($assert, 'Equals');
                $expected = trim($expected, '() ');

                // replace list round parenthesis to []
                if (str_starts_with($expected, '(') && str_ends_with($expected, ')')) {
                    $expected = substr_replace($expected, '[', 0, 1);
                    $expected = substr_replace($expected, ']', -1, 1);
                }

                //df(tmr(@$this->start), $args, $expected);

                if (! str_contains($args, '"')) {
                    $args = str_replace("'", '"', $args);
                }

                $data[$kataId]['args_list'][] = json_decode("[$args]");
                $data[$kataId]['expected_list'][] = json_decode($expected);
                $data[$kataId]['function_names'][] = $functionName;
                //df(tmr(@$this->start),$assert, $args, $expected, $data);
            }

            if (isset($data[$kataId])) {
                if (@array_unique(@$data[$kataId]['args_list']) === [null] || json_encode($data[$kataId]['args_list']) === false) {
                    unset($data[$kataId]);

                    continue;
                }

                if (count(array_unique($data[$kataId]['function_names'])) === 1) {
                    $data[$kataId]['function_names'] = array_unique($data[$kataId]['function_names']);
                }

                $data[$kataId]['kata_id'] = $kataId;
                $data[$kataId]['args_list'] = json_encode($data[$kataId]['args_list']);
                $data[$kataId]['expected_list'] = json_encode($data[$kataId]['expected_list']);
                $data[$kataId]['function_names'] = json_encode($data[$kataId]['function_names']);


                //if($data[$kataId]['args_list'] === "[null,null]"){
                //    df(tmr(@$this->start), $data);
                //}
            }
        }

        //df(tmr(@$this->start), $data, $invalidFunctionNames);

        return $data;
    }

    public function getDataFromPhpTestCasesBodies(array $katasIds = [])
    {
        $testCases = json_decode(file_get_contents(base_path("database/data/json/test_cases/php.json")), 1);

        $data = [];
        $invalidFunctionNames = [];

        foreach ($testCases as $kataId => $testCase) {
            if ($kataId !== '5796f686c38ec292c50006c3') {
                //continue;
            }

            if (count($katasIds) && ! in_array($kataId, $katasIds)) {
                continue;
            }

            $asserts = explode('assertEquals(', $testCase);
            array_shift($asserts);

            foreach ($asserts as $key => $assert) {
                $assert = str_replace(");\n", ")\n", $assert);
                $assert = Str::before($assert, ")\n");
                $assert = rtrim($assert, ');'); // if assert on last line

                $functionName = Str::before($assert, '(');
                $functionName = trim($functionName);

                if (! checkFunctionName($functionName)) {
                    $invalidFunctionNames[$kataId][] = $assert;
                    continue;
                }

                $args = Str::after($assert, $functionName . '(');
                $closeParentPos = $this->getArgumentsCloseParenthesisPosition($args);
                $args = substr($args, 0, $closeParentPos);

                $tail = str_replace("$functionName($args)", '', $assert);
                $tail = trim($tail, " ,\n");
                $expected = $this->getExpected($tail);
                $expected = str_replace("'", '"', $expected);
                $expected = str_replace(['True', 'False'], ['true', 'false'], $expected);
                $expected = str_replace("\n", '\n', $expected);
                $expected = trim($expected);

                // replace list round parenthesis to []
                if (str_starts_with($expected, '(') && str_ends_with($expected, ')')) {
                    $expected = substr_replace($expected, '[', 0, 1);
                    $expected = substr_replace($expected, ']', -1, 1);
                }

                //df(tmr(@$this->start), $args, $expected);

                if (! str_contains($args, '"')) {
                    $args = str_replace("'", '"', $args);
                }

                $data[$kataId]['args_list'][] = json_decode("[$args]");
                $data[$kataId]['expected_list'][] = json_decode($expected);
                $data[$kataId]['function_names'][] = $functionName;
                //df(tmr(@$this->start),$assert, $args, $expected, $data);
            }

            if (isset($data[$kataId])) {
                if (@array_unique(@$data[$kataId]['args_list']) === [null] || json_encode($data[$kataId]['args_list']) === false) {
                    unset($data[$kataId]);

                    continue;
                }

                if (count(array_unique($data[$kataId]['function_names'])) === 1) {
                    $data[$kataId]['function_names'] = array_unique($data[$kataId]['function_names']);
                }

                $data[$kataId]['kata_id'] = $kataId;
                $data[$kataId]['args_list'] = json_encode($data[$kataId]['args_list']);
                $data[$kataId]['expected_list'] = json_encode($data[$kataId]['expected_list']);
                $data[$kataId]['function_names'] = json_encode($data[$kataId]['function_names']);


                //if($data[$kataId]['args_list'] === "[null,null]"){
                //    df(tmr(@$this->start), $data);
                //}
            }
        }

        //df(tmr(@$this->start), $data, $invalidFunctionNames);

        return $data;
    }

    public function getDataFromJavascriptTestCasesBodies(array $katasIds = [])
    {
        $testCases = json_decode(file_get_contents(base_path("database/data/json/test_cases/javascript.json")), 1);

        $data = [];
        $invalidFunctionNames = [];

        foreach ($testCases as $kataId => $testCase) {
            if ($kataId !== '5796f686c38ec292c50006c3') {
                //continue;
            }

            if (count($katasIds) && ! in_array($kataId, $katasIds)) {
                continue;
            }

            $asserts = explode('assertEquals(', $testCase);
            array_shift($asserts);

            foreach ($asserts as $key => $assert) {
                $assert = str_replace(");\n", ")\n", $assert);
                $assert = Str::before($assert, ")\n");
                $assert = rtrim($assert, ');'); // if assert on last line

                $functionName = Str::before($assert, '(');
                $functionName = trim($functionName);

                if (! checkFunctionName($functionName)) {
                    $invalidFunctionNames[$kataId][] = $assert;
                    continue;
                }

                $args = Str::after($assert, $functionName . '(');
                $closeParentPos = $this->getArgumentsCloseParenthesisPosition($args);
                $args = substr($args, 0, $closeParentPos);

                $tail = str_replace("$functionName($args)", '', $assert);
                $tail = trim($tail, " ,\n");
                $expected = $this->getExpected($tail);
                $expected = str_replace("'", '"', $expected);
                $expected = str_replace(['True', 'False'], ['true', 'false'], $expected);
                $expected = str_replace("\n", '\n', $expected);
                $expected = trim($expected);

                // replace list round parenthesis to []
                if (str_starts_with($expected, '(') && str_ends_with($expected, ')')) {
                    $expected = substr_replace($expected, '[', 0, 1);
                    $expected = substr_replace($expected, ']', -1, 1);
                }

                //df(tmr(@$this->start), $args, $expected);

                if (! str_contains($args, '"')) {
                    $args = str_replace("'", '"', $args);
                }

                $data[$kataId]['args_list'][] = json_decode("[$args]");
                $data[$kataId]['expected_list'][] = json_decode($expected);
                $data[$kataId]['function_names'][] = $functionName;
                //df(tmr(@$this->start),$assert, $args, $expected, $data);
            }

            if (isset($data[$kataId])) {
                if (@array_unique(@$data[$kataId]['args_list']) === [null] || json_encode($data[$kataId]['args_list']) === false) {
                    unset($data[$kataId]);

                    continue;
                }

                if (count(array_unique($data[$kataId]['function_names'])) === 1) {
                    $data[$kataId]['function_names'] = array_unique($data[$kataId]['function_names']);
                }

                $data[$kataId]['kata_id'] = $kataId;
                $data[$kataId]['args_list'] = json_encode($data[$kataId]['args_list']);
                $data[$kataId]['expected_list'] = json_encode($data[$kataId]['expected_list']);
                $data[$kataId]['function_names'] = json_encode($data[$kataId]['function_names']);


                //if($data[$kataId]['args_list'] === "[null,null]"){
                //    df(tmr(@$this->start), $data);
                //}
            }
        }

        //df(tmr(@$this->start), $data, $invalidFunctionNames);

        return $data;
    }

    public function getDataFromPythonTestCasesBodies(array $katasIds = [])
    {
        $testCases = json_decode(file_get_contents(base_path("database/data/json/test_cases/python.json")), 1);

        $data = [];
        $invalidFunctionNames = [];

        foreach ($testCases as $kataId => $testCase) {
            if ($kataId !== '5796f686c38ec292c50006c3') {
                //continue;
            }

            if (count($katasIds) && ! in_array($kataId, $katasIds)) {
                continue;
            }

            $asserts = explode('assert_equals(', $testCase);
            array_shift($asserts);

            foreach ($asserts as $key => $assert) {
                $assert = Str::before($assert, ")\n");
                $assert = rtrim($assert, ')'); // if assert on last line

                $functionName = Str::before($assert, '(');
                $functionName = trim($functionName);

                if (! checkFunctionName($functionName)) {
                    $invalidFunctionNames[$kataId][] = $assert;
                    continue;
                }

                $args = Str::after($assert, $functionName . '(');
                $closeParentPos = $this->getArgumentsCloseParenthesisPosition($args);
                $args = substr($args, 0, $closeParentPos);

                $tail = str_replace("$functionName($args)", '', $assert);
                $tail = trim($tail, " ,\n");
                $expected = $this->getExpected($tail);
                $expected = str_replace("'", '"', $expected);
                $expected = str_replace(['True', 'False'], ['true', 'false'], $expected);
                $expected = str_replace("\n", '\n', $expected);
                $expected = trim($expected);

                // replace list round parenthesis to []
                if (str_starts_with($expected, '(') && str_ends_with($expected, ')')) {
                    $expected = substr_replace($expected, '[', 0, 1);
                    $expected = substr_replace($expected, ']', -1, 1);
                }

                //df(tmr(@$this->start), $args, $expected);

                if (! str_contains($args, '"')) {
                    $args = str_replace("'", '"', $args);
                }

                $data[$kataId]['args_list'][] = json_decode("[$args]");
                $data[$kataId]['expected_list'][] = json_decode($expected);
                $data[$kataId]['function_names'][] = $functionName;
                //df(tmr(@$this->start),$assert, $args, $expected, $data);
            }

            if (isset($data[$kataId])) {
                if (@array_unique(@$data[$kataId]['args_list']) === [null] || json_encode($data[$kataId]['args_list']) === false) {
                    unset($data[$kataId]);

                    continue;
                }

                if (count(array_unique($data[$kataId]['function_names'])) === 1) {
                    $data[$kataId]['function_names'] = array_unique($data[$kataId]['function_names']);
                }

                $data[$kataId]['kata_id'] = $kataId;
                $data[$kataId]['args_list'] = json_encode($data[$kataId]['args_list']);
                $data[$kataId]['expected_list'] = json_encode($data[$kataId]['expected_list']);
                $data[$kataId]['function_names'] = json_encode($data[$kataId]['function_names']);


                //if($data[$kataId]['args_list'] === "[null,null]"){
                //    df(tmr(@$this->start), $data);
                //}
            }
        }

        //df(tmr(@$this->start), $data, $invalidFunctionNames);

        return $data;
    }

    protected function getExpected(string $str): string
    {
        if (strlen($str) < 3) {
            return $str;
        }

        //$str = 'greeting("Alex"), "Hello intruder!, Did not output the correct greeting."';
        $lastChar = substr($str, -1, 1);

        // if strin doesn't end with quote return string
        if (! in_array($lastChar, ['"', ""])) {
            return $str;
        }

        // find previous quote position
        $prevQuotePos = strrpos($str, $lastChar, -2);

        if ($prevQuotePos === 0) {
            return $str;
        }

        $expected = substr($str, 0, $prevQuotePos);
        $expected = rtrim($expected, ', ');

        return $expected;
    }

    protected function getArgumentsCloseParenthesisPosition(string $str): int
    {
        $pos = 0;
        $parentCount = 0;

        foreach (str_split($str) as $pos => $char) {
            if ($char === ')') {
                if ($parentCount > 0) {
                    $parentCount--;
                } else {
                    break;
                }
            }

            if ($char === '(') {
                $parentCount++;
            }
        }

        return $pos;
    }

    public function getDataFromTestCasesBodiesPhp(array $katasIds = [])
    {
        $testCases = Storage::disk('data')->get('json/test_cases.json');
        $testCases = json_decode($testCases, 1)['php'];
        $data = [];
        $invalidFunctionNames = [];

        foreach ($testCases as $kataId => $testCase) {
            if (count($katasIds) && ! in_array($kataId, $katasIds)) {
                continue;
            }

            $testCase = str_replace('assertEquals($', '', $testCase);
            $asserts = explode('assertEquals(', $testCase);
            array_shift($asserts);

            foreach ($asserts as $key => $assert) {
                $assert = Str::before($assert, ');');

                $functionName = Str::before($assert, '(');
                $functionName = Str::afterLast($functionName, ' ');
                $functionName = Str::afterLast($functionName, ',');
                preg_match('/[a-zA-z0-1]+/', $functionName, $matches);

                if ($functionName !== @$matches[0] || Str::contains($functionName, ['$', '\\'])) {
                    $invalidFunctionNames[] = $functionName;
                    continue;
                }

                $args = Str::after($assert, $functionName . '(');
                $args = Str::before($args, ')');

                $expected = str_replace("$functionName($args)", '', $assert);
                $expected = trim($expected, " ,\n");
                $expected = str_replace("'", '"', $expected);

                $args = str_replace("'", '"', $args); // TODO replace back [what's, doesn't, Google's, I've, someone's]


                if (Str::contains($args . $expected, ['$'])) {
                    //continue;
                }

                $data[$kataId]['args_list'][] = json_decode("[$args]");
                $data[$kataId]['expected_list'][] = json_decode($expected);
                $data[$kataId]['function_names'][] = $functionName;
            }

            if (isset($data[$kataId])) {
                if (@array_unique(@$data[$kataId]['args_list']) === [null]) {
                    unset($data[$kataId]);

                    continue;
                }


                $data[$kataId]['kata_id'] = $kataId;
                $data[$kataId]['args_list'] = json_encode($data[$kataId]['args_list']);
                $data[$kataId]['expected_list'] = json_encode($data[$kataId]['expected_list']);
                $data[$kataId]['function_names'] = json_encode($data[$kataId]['function_names']);

                //if($data[$kataId]['args_list'] === "[null,null]"){
                //    df(tmr(@$this->start), $data);
                //}
            }
        }

        return $data;
    }

    public function getDataFromTestCasesBodiesPython(array $katasIds = [])
    {
        $testCases = Storage::disk('data')->get('json/test_cases.json');
        $testCases = json_decode($testCases, 1)['python'];
        //$testCases = array_slice($testCases, 1, 100);
        $data = [];
        $invalidFunctionNames = [];

        foreach ($testCases as $kataId => $testCase) {
            if ($kataId !== '5705ca6a41e5be67720012c0') {
                //continue;
            }

            if (count($katasIds) && ! in_array($kataId, $katasIds)) {
                continue;
            }

            $asserts = explode('assert_equals(', $testCase);
            array_shift($asserts);

            foreach ($asserts as $key => $assert) {
                $assert = Str::before($assert, ")\n");

                $functionName = Str::before($assert, '(');
                $functionName = Str::afterLast($functionName, ' ');
                $functionName = Str::afterLast($functionName, ',');
                preg_match('/[a-zA-z0-1]+/', $functionName, $matches);

                if ($functionName !== @$matches[0] || Str::contains($functionName, ['$', '\\'])) {
                    $invalidFunctionNames[$functionName] = $kataId;
                    continue;
                }

                $args = Str::after($assert, $functionName . '(');
                $args = Str::before($args, ')');

                $expected = str_replace("$functionName($args)", '', $assert);
                $expected = trim($expected, " ,\n");
                $expected = str_replace("'", '"', $expected);
                $expected = @json_encode(json_decode("[$expected]")[0]);

                $args = str_replace("'", '"', $args); // TODO replace back [what's, doesn't, Google's, I've, someone's]

                //df(tmr(@$this->start),$assert, $functionName, $args, $expected );

                $data[$kataId]['args_list'][] = json_decode("[$args]");
                $data[$kataId]['expected_list'][] = json_decode($expected);
                $data[$kataId]['function_names'][] = $functionName;
            }

            if (isset($data[$kataId])) {
                if (@array_unique(@$data[$kataId]['args_list']) === [null] || json_encode($data[$kataId]['args_list']) === false) {
                    unset($data[$kataId]);

                    continue;
                }

                $data[$kataId]['kata_id'] = $kataId;
                $data[$kataId]['args_list'] = json_encode($data[$kataId]['args_list']);
                $data[$kataId]['expected_list'] = json_encode($data[$kataId]['expected_list']);
                $data[$kataId]['function_names'] = json_encode($data[$kataId]['function_names']);

                //if($data[$kataId]['args_list'] === "[null,null]"){
                //    df(tmr(@$this->start), $data);
                //}
            }
        }

        //df(tmr(@$this->start), $data);
        //df(tmr(@$this->start), $invalidFunctionNames);
        return $data;
    }

    public function parseKatasTestsHtml(string $langSlug, array $katasIds = []): array
    {
        if (count($katasIds) === 0) {
            $katasIds = Kata::whereRelation('langs', 'slug', $langSlug)->pluck('id');
        }

        $tests = [];

        foreach ($katasIds as $kataId) {
            $tests[$kataId] = $this->parseKataTestsHtml($kataId, $langSlug);
        }

        return array_filter($tests);
    }

    public function parseKataTestsHtml(string $kataId, string $langSlug): ?string
    {
        $filepath = base_path("_backups/codewars_data/katas_1_2_htmls/$langSlug/$kataId.html");

        if (! file_exists($filepath)) {
            return null;
        }

        $html = file_get_contents($filepath);
        $html = str_replace('\n', "\n", $html);
        $html = stripslashes($html);
        $document = new Document($html);
        $tests = $document->first('code')->text();

        return $tests;
    }
}
