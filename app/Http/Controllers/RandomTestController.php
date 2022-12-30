<?php

namespace App\Http\Controllers;

use App\Models\Kata;
use App\Models\RandomTest;
use Illuminate\Http\Request;

class RandomTestController extends Controller
{
    public function edit(RandomTest $randomTest)
    {
        $tests = json_decode($randomTest->scheme, 1);

        foreach ($tests  as &$test){
            foreach ($test['args'] as &$arg){
                $arg = array_map('json_encode', $arg);
            }
        }

        $randomTest->tests = $tests;

        return inertia('RandomTests/EditRandomTests', compact('randomTest'));
    }

    public function update(Request $request, RandomTest $randomTest)
    {
        $randomTest->scheme = $request->scheme;
        $randomTest->save();

        return back();
    }

    private function getScheme(array $tests):string
    {
        foreach ($tests as &$test){
            foreach ($test['args'] as &$arg){
                $arg = array_map('json_decode', $arg);
            }
        }

        return json_encode($tests);
    }

    private function getSchemeOld(array $tests):string
    {
        foreach ($tests as &$test){
            foreach ($test['args'] as &$arg){
                foreach (['value', 'length', 'allowed'] as $item){
                    $arg[$item] = json_decode($arg[$item]);
                }
            }
        }

        return json_encode($tests);
    }

}
