<?php


namespace App\Classes\Checkers;


use App\Classes\Train\SolutionResultsHandler;
use App\Models\Kata;
use App\Models\Solution;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class SolutionMassChecker
{
    public function run(Collection $solutions, string $checkMode = 'sample'): array
    {
        set_time_limit(100);
        $result = [];

        $modSolutions = [];

        foreach ($solutions as $solution) {
            if($solution['id'] !== 234662){
                //continue;
            }

            $solutionResults = $this->check($solution, $checkMode);

            if ($solutionResults['failed'] + $solutionResults['passed'] === 0) {
                $solution['status'] = 'sample_sum_equals_zero';
                $modSolutions[] = $solution->only('id', 'kata_id', 'lang_id', 'body', 'status');
                $result['passed'][$solution->kata->slug][$solution->lang->slug][$solution->id] = 'no samples';
            } elseif ($solutionResults['failed'] === 0) {
                $result['passed'][$solution->kata->slug][$solution->lang->slug][$solution->id] = $solutionResults;
                $solution['status'] = 'sample_passed';
                $modSolutions[] = $solution->only('id', 'kata_id', 'lang_id', 'body', 'status');
            } elseif ($solutionResults['failed'] > 0 && $solutionResults['passed'] > 0) {
                $result['semi'][$solution->kata->slug][$solution->lang->slug][$solution->id] = $solutionResults;
                $solution['status'] = 'sample_semi_passed';
                $modSolutions[] = $solution->only('id', 'kata_id', 'lang_id', 'body', 'status');
            } elseif ($solutionResults['failed'] > 0 && $solutionResults['passed'] === 0) {
                $result['failed'][$solution->kata->slug][$solution->lang->slug][$solution->id] = $solutionResults;
                $solution['status'] = 'sample_failed';
                $modSolutions[] = $solution->only('id', 'kata_id', 'lang_id', 'body', 'status');
            } else {
                $solution['status'] = 'undefined';
                $modSolutions[] = $solution->only('id', 'kata_id', 'lang_id', 'body', 'status');
            }
        }

        foreach (array_chunk($modSolutions, 2000) as $chunk) {
            Solution::upsert($chunk, 'id', ['status']);
        }

        return $result;
    }

    public function check(Solution $solution, string $checkMode = 'sample'): array
    {
        $results = (new SolutionResultsHandler($solution->kata, $solution->body, $solution->lang->slug, $checkMode))->getResults();
        $results['solution'] = $solution->body;

        return $results;
    }

    public function bringToUniformCase()
    {
        df(tmr(@$this->start), 'bringToUniformCase');
        set_time_limit(300);

        $katas = Kata::query()
            ->with('sample')
            ->whereRelation('sample', 'function_names', 'like', '%\_%')
            ->get()
            //->skip(2200)
            //->take(1000)
            //->filter(fn($v) => preg_match("/^[a-z][a-z\d, \"-]*$/", substr(@$v->sample->function_names, 2,-2)) === 0)
        ;

        $katas->load('solutions');


        $modSolutions = [];

        foreach ($katas as $kata) {
            $kataFunctionNames = json_decode($kata->sample->function_names, 1);

            // load solutions before to speed up
            foreach ($kata['solutions'] as $solution) {
                //df(tmr(@$this->start), $kata);
                foreach ($kataFunctionNames as $functionName) {
                    if (str_contains($solution['body'], $functionName)) {
                        continue;
                    }

                    if (str_contains($solution['body'], $snake = Str::snake($functionName))) {
                        $solution['body'] = str_replace($snake, $functionName, $solution['body']);
                    } elseif (str_contains($solution['body'], $camel = Str::camel($functionName))) {
                        $solution['body'] = str_replace($camel, $functionName, $solution['body']);
                    } elseif (str_contains($solution['body'], $kebab = Str::kebab(Str::camel($functionName)))) {
                        $solution['body'] = str_replace($kebab, $functionName, $solution['body']);
                    } elseif (str_contains($solution['body'], $studly = Str::studly($functionName))) {
                        $solution['body'] = str_replace($studly, $functionName, $solution['body']);
                    } elseif (str_contains($solution['body'], $lower = strtolower(Str::camel($functionName)))) {
                        $solution['body'] = str_replace($lower, $functionName, $solution['body']);
                    } else {
                        continue;
                    }

                    $modSolutions[] = $solution->only('id', 'body', 'kata_id', 'lang_id');
                    //df(tmr(@$this->start), 'none', $kataFunctionNames, $solution, $kata);
                }
            }
        }

        foreach (array_chunk($modSolutions, 2000) as $chunk) {
            Solution::upsert($chunk, 'id');
        }

        df(tmr(@$this->start), count($modSolutions));

        df(tmr(@$this->start), 'stop');
    }
}
