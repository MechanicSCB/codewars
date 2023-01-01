<?php

namespace App\Classes\Train;


use App\Classes\ArgumentsGenerator;
use App\Models\Kata;
use App\Models\RandomTest;

class AttemptsHandler
{
    protected array $attempts;

    public function __construct(
        private Kata $kata,
        private string $checkMode,
    )
    {
        $this->kata->loadMissing('sample');
    }

    public function getAttemptsList(): array
    {
        $attemptsList = [];

        $sampleArgs = json_decode($this->kata->sample['args_list'] ?? "[]", 1);
        $funcNamesList = json_decode($this->kata->sample['function_names'] ?? "[]", 1);

        foreach ($sampleArgs as $key => $args) {
            $item['type'] = 'fixed';
            $functionName = $funcNamesList[$key] ?? $funcNamesList[0];
            $item['name'] = $functionName;
            $item['args'] = $args;
            $attemptsList[] = $item;
        }

        if ($this->checkMode === 'attempt' && $randomTest = @$this->kata->random_test) {
            $randomArgsSets = $this->getRandomArgsSets($randomTest);

            foreach ($randomArgsSets as $randomArgsSet) {
                foreach ($randomArgsSet['args'] as $args) {
                    $item['type'] = 'random';
                    $functionName = $randomArgsSet['function_name'] ?? $funcNamesList[0];
                    $item['name'] = $functionName;
                    $item['args'] = $args;
                    $attemptsList[] = $item;
                }
            }
        }

        return $attemptsList;
    }

    public function getRandomArgsSets(RandomTest $randomTest): array
    {
        $randomArgsSets = [];

        $argsSets = json_decode($randomTest->scheme, 1);

        foreach ($argsSets as $argsSet) {
            $rand = (new ArgumentsGenerator())->generate($argsSet['args'], @$argsSet['attempts_count'], $randomTest);

            $randomArgsSets[] = [
                'function_name' => $argsSet['function_name'],
                'args' => $rand,
            ];
        }

        return $randomArgsSets;
    }
}
