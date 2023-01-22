<?php

namespace App\Http\Controllers;

use App\Models\Kata;
use App\Models\Sample;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class SampleController extends Controller
{
    public function update(Request $request, Sample $sample)
    {
        $sample->args_list = $request->args_list;
        $sample->expected_list = $request->expected_list;
        $sample->function_names = $request->function_names;
        $sample->save();

        return back()->with('success', __('flash.successfully_saved'));
    }

    // Dev
    public function showTestCases(): View
    {
        $kataId = '5ea6a8502186ab001427809e';
        $langSlug = 'javascript';
        //$langSlug = 'ruby';
        $langSlug = 'python';
        //$langSlug = 'php';
        $testCases = json_decode(file_get_contents(base_path("_backups/codewars_data/parsed_test_cases/$langSlug.json")), 1);

        $testCases = [
            $kataId => @$testCases[$kataId],
        ];

        return view('test', compact('testCases'));
    }


}
