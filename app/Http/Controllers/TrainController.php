<?php

namespace App\Http\Controllers;

use App\Classes\Train\KataHandler;
use App\Classes\Train\SolutionResultsHandler;
use App\Models\Kata;
use App\Models\Lang;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Inertia\Response;

class TrainController extends Controller
{
    public function train(Kata $kata): Response
    {
        $kata->load('solutions.lang', 'sample', 'random_test', 'tags', 'creator:id,name');
        $langs = $this->getLangs($kata);
        $initLang = request()['languages'] ?? 'php';
        $preload = (new KataHandler())->getPreload($kata);
        $attemptsResults = [];

        // return inertia('Katas/TrainMod', compact('kata', 'langs', 'initLang', 'attemptsResults', 'preload'));
        return inertia('Katas/Train', compact('kata', 'langs', 'initLang', 'attemptsResults', 'preload'));
    }

    public function attempt(Request $request, Kata $kata)
    {
        //$request->solution = iconv("UTF-8", "ASCII//IGNORE", $request->solution);
        $attemptsResults = (new SolutionResultsHandler($kata, $request->solution, $request->lang, $request->attemptMode))
            ->getResults();
        $kata->load('solutions.lang', 'tags', 'creator:id,name');
        $langs = $this->getLangs($kata);
        $preload = (new KataHandler())->getPreload($kata);

        // return inertia('Katas/TrainMod', compact('kata', 'langs', 'attemptsResults', 'preload'));
        return inertia('Katas/Train', compact('kata', 'langs', 'attemptsResults', 'preload'));
    }

    protected function getLangs(Kata $kata): Collection
    {
        $langs = Lang::where('status', '>', 0)->get();
        $langsHasSolutions = $kata->solutions->groupBy('lang_id')->keys()->toArray();
        $langsHasPassedSolutions = $kata->solutions->filter(fn($v) => str_contains($v['status'], 'sample_passed'))->groupBy('lang_id')->keys()->toArray();

        foreach ($langs as &$lang) {
            $lang['has_solution'] = in_array($lang['id'], $langsHasSolutions);
            $lang['has_passed_solution'] = in_array($lang['id'], $langsHasPassedSolutions);
        }

        return $langs;
    }


}
