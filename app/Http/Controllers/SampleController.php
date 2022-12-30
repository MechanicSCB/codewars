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
        $langSlug = 'javascript';
        $langSlug = 'ruby';
        $langSlug = 'python';
        //$testCases = json_decode(file_get_contents(base_path("database/data/json/test_cases.json")), 1);
        $testCases = json_decode(file_get_contents(base_path("database/data/json/parsed_test_cases/$langSlug.json")), 1);
        //$testCases = array_slice($testCases, 0, 200);
        $testCases = [
            '5671d975d81d6c1c87000022' => @$testCases['5671d975d81d6c1c87000022'],
            '5679d5a3f2272011d700000d' => @$testCases['5679d5a3f2272011d700000d'],
            '5917a2205ffc30ec3a0000a8' => @$testCases['5917a2205ffc30ec3a0000a8'],
        ];

        //df(tmr(@$this->start), $testCases);


        return view('test', compact('testCases'));
    }


}
