<?php

namespace App\Http\Controllers;

use App\Evaluators\EvaluatorsHandler;
use App\Runners\RunnersHandler;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index(Request $request)
    {
        $output = (new EvaluatorsHandler())->getEvaluatorOutput($request);
        $jsonOutput = json_encode($output);

        echo $jsonOutput;
    }

    public function indexBoth(Request $request)
    {
        $attempts = json_decode($request->attempts,1);

        if(@$attempts[0]['string']){
            echo json_encode((new RunnersHandler())->getAttemptsResults($request));
        }else{
            echo json_encode((new EvaluatorsHandler())->getEvaluatorOutput($request));
        }

    }

    public function indexOld(Request $request)
    {
        //$request['attempts'] ??= '[{"string":"multiply(1,1)","name":"multiply","args":[1,1],"expected":1},{"string":"multiply(2,1)","name":"multiply","args":[2,1],"expected":2},{"string":"multiply(2,2)","name":"multiply","args":[2,2],"expected":4},{"string":"multiply(3,5)","name":"multiply","args":[3,5],"expected":15},{"string":"multiply(15,25)","name":"multiply","args":[15,25],"expected":375},{"string":"multiply(0,5)","name":"multiply","args":[0,5],"expected":0},{"string":"multiply(0,0)","name":"multiply","args":[0,0],"expected":0}]';
        $attemptsResults = (new RunnersHandler())->getAttemptsResults($request);

        echo json_encode($attemptsResults);
    }
}
