<?php

namespace App\Http\Controllers;

use App\Classes\Checkers\SolutionMassChecker;
use App\Classes\SolutionChecker;
use App\Http\Requests\StoreSolutionRequest;
use App\Http\Requests\UpdateSolutionRequest;
use App\Models\Kata;
use App\Models\Lang;
use App\Models\Solution;
use Illuminate\Http\Request;

class SolutionController extends Controller
{
    public function massCheck(Request $request, Kata $kata)
    {
        $checker = new SolutionMassChecker();
        $solutions = Solution::whereKataId($kata['id']);

        if ($request->lang) {
            $solutions->whereRelation('lang', 'slug', $request->lang);
        }

        $solutions = $solutions->get();
        //df(tmr(@$this->start), $solutions);

        $result = $checker->run($solutions, $request->mode);

        $messages = [];

        foreach ($result as $status => $statusResults) {
            foreach ($statusResults as $functionName => $functionResults) {
                foreach ($functionResults as $langSlug => $langResults) {
                    foreach ($langResults as $solutionId => $solutionResult) {
                        $messages[$status][] = $solutionId;
                    }
                }
            }
        }

        $message = "passed: " . count($messages['passed'] ?? []);
        $message .= "; failed:" . count($messages['failed'] ?? []);
        $message .= "; semi:" . count($messages['semi'] ?? []);

        $messageStatus = $this->getMessageStatus($messages);
        //df(tmr(@$this->start), $result, $messages, $messageStatus);

        return back()->with($messageStatus, $message);
    }

    public function check(Request $request, Solution $solution = null)
    {
        $checker = new SolutionMassChecker();
        $solutions = Solution::whereId($solution['id'])->get();

        $result = $checker->run($solutions, $request->mode);

        $messages = [];

        foreach ($result as $status => $statusResults) {
            foreach ($statusResults as $functionName => $functionResults) {
                foreach ($functionResults as $langSlug => $langResults) {
                    foreach ($langResults as $solutionId => $solutionResult) {
                        $messages[$status][] = $solutionId;
                    }
                }
            }
        }

        $message = "passed: " . count($messages['passed'] ?? []);
        $message .= "; failed:" . count($messages['failed'] ?? []);
        $message .= "; semi:" . count($messages['semi'] ?? []);

        $messageStatus = $this->getMessageStatus($messages);
        //df(tmr(@$this->start), $result, $messages, $messageStatus);

        return back()->with($messageStatus, $message);
    }

    public function renameFunction(Request $request, Kata $kata)
    {
        $solutions = Solution::whereKataId($kata->id);

        if($langSlug = $request['lang']){
            $solutions->whereRelation('lang', 'slug', $langSlug);
        }

        $solutions = $solutions->get();

        $oldName = trim($request->from);
        $newName = trim($request->to);

        if (! $oldName || ! $newName) {
            return back()->with('error', __('flash.error'));
        }

        if (! checkFunctionName($newName) || ! checkFunctionName($newName)) {
            //return back()->with('error', __('flash.wrong_function_name'));
        }

        $cnt = 0;

        foreach ($solutions as $solution) {
            //if (! str_contains($solution['body'], $newName) && str_contains($solution['body'], $oldName)) {
            if (str_contains($solution['body'], $oldName)) {
                $solution['body'] = str_replace($oldName, $newName, $solution['body']);
                $solution->save();
                $cnt++;
            }
        }

        return back()->with($cnt ? 'success' : 'message', "$cnt functions renamed");
    }

    public function update(Request $request, Solution $solution)
    {
        df(tmr(@$this->start), $request->all(), $solution);

        return back()->with('success', __('flash.successfully_updated'));
    }

    protected function getMessageStatus(array $messages):string
    {
        $status = 'message';

        if(@$messages['failed']){
            $status = 'error';
        }

        if(@$messages['passed']){
            $status = 'success';
        }

        return $status;
    }
}
