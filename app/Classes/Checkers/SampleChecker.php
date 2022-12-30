<?php


namespace App\Classes\Checkers;


use App\Classes\SolutionChecker;
use App\Classes\SolutionEvaluator;
use App\Models\Kata;
use App\Models\Sample;
use App\Models\Solution;
use Illuminate\Support\Facades\Storage;

class SampleChecker
{

    public function putSamplesFromToJsonDataFile(): void
    {
        $samples = Sample::get()->toJson();

        Storage::disk('data')->put('json/samples.json', $samples);
    }

    public function saveEvaluatedToSamplesExpectedLists($samples = null, $langSlug = 'python'): void
    {
        $samples ??= Sample::query()
            ->with('kata')
            ->whereJsonLength('expected_list', '<', 1)
            ->get();

        $samplesMod = [];

        foreach ($samples as $sample) {
            $samplesMod[] = $this->getEvaluatedToSampleExpectedList($sample, $langSlug)
                ->only('id', 'kata_id', 'args_list', 'expected_list', 'eval_list');
        }

        //df(tmr(@$this->start), $samplesMod);
        foreach (array_chunk($samplesMod, 1000) as $chunk) {
            Sample::upsert($chunk, 'id', ['eval_list']);
        }
        df(tmr(@$this->start), $samplesMod);

        df(tmr(@$this->start), 'done');
    }

    public function getEvaluatedToSampleExpectedList(Sample $sample, string $langSlug = 'python'): Sample
    {
        $solution = $this->getSolution($sample->kata, $langSlug);

        if (is_null($solution)) {
            return $sample;
        }

        $attemptsList = (new SolutionChecker($sample->kata, $solution['body'], $langSlug, 'sample'))->getAttemptsList();
        $expectedList = (new SolutionEvaluator())->evalSolutionList($solution['body'], $attemptsList, $solution->lang->slug);
        $evalList = json_encode($expectedList);

        if (strlen($evalList) < 35000) {
            $sample['eval_list'] = $evalList;
        }else{
            $sample['eval_list'] = json_encode("ERROR! More than 35000 characters");
        }

        return $sample;
    }

    public function getSolution(Kata $kata, string $langSlug = null): ?Solution
    {
        $langSlug ??= 'python';
        //$solution = $kata->solutions()->whereRelation('lang', 'slug', $langSlug)->first() ?? $kata->solutions()->first();
        $solution = $kata->solutions()->where('is_control', 1)->first()
            ?? $kata->solutions()->whereRelation('lang', 'slug', $langSlug)->first()
            ?? $kata->solutions()->first();

        return $solution;
    }

    public function show(): array
    {
        $samples = Sample::query()
            ->with('kata')
            ->whereJsonLength('args_list', '>', 1)
            ->get()//
        ;

        $array = [];

        foreach ($samples as $sample) {
            $list = [];
            $expectedList = json_decode($sample['expected_list'], 1);

            foreach (json_decode($sample['args_list'], 1) as $key => $args) {
                $functionNames = json_decode($sample['function_names'], 1);
                $functionName = $functionNames[$key] ?? $functionNames[0];
                $list[$functionName . '(' . json_encode($args) . ')'] = json_encode($expectedList[$key] ?? $expectedList[0] ?? null);
                //$list[json_encode($args)] = json_encode($expectedList[$key] ?? $expectedList[0]);
            }

            $array[$sample['kata']['slug']] = $list;
        }

        df(tmr(@$this->start), $array);

        df(tmr(@$this->start), 'show');
    }

    public function getSamplesFromTestCasesJson(string $langSlug): array
    {
        $testCases = Storage::disk('data')->get('json/test_cases.json');

        return json_decode($testCases, 1)[$langSlug];
    }

    public function getKatasIdsWithLang(string $langSlug): array
    {
        return Kata::query()
            ->whereRelation('langs', 'slug', $langSlug)
            ->pluck('id')
            ->toArray();
    }

    public function argsCountEqualsExpectedCount(): array
    {
        $wrongSamples = [];
        $samples = Sample::get(['id', 'args_list', 'expected_list']);

        foreach ($samples as $sample) {
            if (count(json_decode($sample['args_list'], 1)) !== count(json_decode($sample['expected_list'], 1))) {
                $wrongSamples[] = $sample;
            }
        }

        return $wrongSamples;
    }
}
