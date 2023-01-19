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
        //$langSlug = 'ruby';
        //$langSlug = 'python';
        //$testCases = json_decode(file_get_contents(base_path("database/data/json/test_cases.json")), 1);
        $testCases = json_decode(file_get_contents(base_path("_backups/codewars_data/parsed_test_cases/$langSlug.json")), 1);
        //$testCases = array_slice($testCases, 0, 200);
        $testCases = [
            '52dc4688eca89d0f820004c6' => @$testCases['52dc4688eca89d0f820004c6'],
        ];

        //df(tmr(@$this->start), $testCases);

        return view('test', compact('testCases'));
    }


}
