<?php

namespace App\Classes\Train;


use App\Models\Kata;
use App\Models\Lang;
use App\Models\Solution;

class SolutionResultsHandler
{
    protected array $attempts;

    public function __construct(
        private Kata $kata,
        private string $solutionBody,
        private string $langSlug,
        private string $checkMode,
    )
    {
        $this->attempts = (new AttemptsHandler($this->kata, $this->checkMode))->getAttemptsList();
    }

    public function getResults(): array
    {
        // get expected list
        $expectedList = $this->getAttemptsExpected();

        $this->addExpectedToAttempts($expectedList);

        $start = microtime(true);

        $solutionEvalList = (new SolutionChecker($this->kata, $this->attempts, $this->solutionBody, $this->langSlug))->getSolutionEvalList();

        $attemptsResults = $this->getSolutionAttemptsResults($expectedList, $solutionEvalList, $start);

        return $attemptsResults;
    }

    protected function getAttemptsExpected()
    {
        if ($this->checkMode === 'attempt') {
            $controlSolution = $this->getControlSolution();
            // if not control solution throw exception?

            $expectedEvalList = (new SolutionChecker(
                $this->kata,
                $this->attempts,
                $controlSolution->body,
                Lang::find($controlSolution->lang_id)->slug)
            )->getSolutionEvalList();
        } else {
            $expectedEvalList = json_decode($this->kata->sample['expected_list'] ?? "[]", 1);
        }

        return stdToArray($expectedEvalList);
    }

    protected function getSolutionAttemptsResults(array $expectedList, array $solutionEvalList, float $start): array
    {
        $attemptsResults = [
            'items' => [],
            'passed' => 0,
            'failed' => 0,
        ];

        $attemptsResults['time'] = round((microtime(true) - $start) * 1000) . 'ms';

        foreach ($expectedList as $key => $expectedResult) {
            if(! array_key_exists($key,$solutionEvalList)){
                $solutionEvalList[$key] = 'not executed!';
            }

            $comparison = $this->getComparison($solutionEvalList[$key], $expectedResult);

            $expectedResultStr = $this->getStrResultValue($expectedResult);
            $solutionResultStr = $this->getStrResultValue($solutionEvalList[$key]);
            $functionString = $this->getFunctionString($key);

            if ($comparison === true) {
                $attemptsResults['items']['passed'][$key] = [
                    'function' => $functionString,
                    'result' => "Expected = Actual: \"$solutionResultStr\"",
                ];

                $attemptsResults['passed']++;
            } else {
                $attemptsResults['items']['failed'][$key] = [
                    'function' => $functionString,
                    'result' => "Expected:&nbsp;\"$expectedResultStr\"\nActual&nbsp;&nbsp;:&nbsp;\"$solutionResultStr\"",
                ];

                $attemptsResults['failed']++;
            }
        }

        return $attemptsResults;
    }

    private function getControlSolution(): ?Solution
    {
        $kataSolutions = $this->kata->solutions->where('status', 'sample_passed');

        if ($kataSolutions->count() === 0) {
            return null;
        }

        return $kataSolutions->firstWhere('is_control', 1)
            ?? $kataSolutions->firstWhere('lang_id', 38)
            ?? $kataSolutions->firstWhere('lang_id', 27)
            ?? $kataSolutions->firstWhere('lang_id', 42)
            ?? $kataSolutions->firstWhere('lang_id', 47)
            ?? $kataSolutions->firstWhere('lang_id', 31)
            ?? $kataSolutions->first();
    }

    protected function getFunctionString(int $key): string
    {
        if(! @$this->attempts[$key]){
            return '';
        }

        $functionName = $this->attempts[$key]['name'];
        $args = $this->attempts[$key]['args'];
        $argsString = substr(json_encode($args), 1, -1);
        $functionString = "$functionName($argsString)";

        return $functionString;
    }

    private function getStrResultValue(mixed $expectedResult): string
    {
        if (gettype($expectedResult) === 'boolean') {
            return $expectedResult ? 'true' : 'false';
        }

        if ($expectedResult === null) {
            return 'null';
        }

        if (is_array($expectedResult) || is_object($expectedResult)) {
            $expectedResult = json_encode($expectedResult);
        }

        return (string)$expectedResult;
    }

    private function getComparison(mixed $result, mixed $expected): bool
    {
        //df(tmr(@$this->start), $result, $expected);
        if(is_object($result)){
            $result = stdToArray($result);
        }

        // 1.0 => 1 --- convert float without decimals to integer
        if (is_int($expected) && is_float($result) && intval($result) == $result) {
            $result = (int)$result;
        }

        // round float number
        if (is_float($expected) && is_float($result)) {
            $result = round($result, 9);
            $expected = round($expected, 9);
        }

        // If expected string and result number convert number to string
        if(gettype($expected) === 'string' && ! is_array($result)){
            $result = (string) $result;
        }

        // compare array ignore types
        if(is_array($expected)){
            // TODO sort assoc arrays before comparison?
            if(range( 0, count($expected) -1 ) !== array_keys( $expected )){
                //$comparison = $result === $expected;
                if(is_array($result)){
                    ksort($result);
                }

                ksort($expected);
            }

            $comparison = json_encode($result) === json_encode($expected);
        }else{
            $comparison = $result === $expected;
        }

        return $comparison;
    }

    private function addExpectedToAttempts(array $expectedList)
    {
        foreach ($this->attempts as $key => &$attempt) {
            $attempt['expected'] = [
                'value' => $expectedList[$key] ?? 'not executed',
                'type' => gettype(@$expectedList[$key]),
            ];
        }
    }
}
