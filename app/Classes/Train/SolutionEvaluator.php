<?php


namespace App\Classes\Train;

use Illuminate\Support\Facades\Http;

class SolutionEvaluator
{
    public function evalSolutionList(string $langSlug, array $files, array $commands): string
    {
        $url = env('RUNNER_URL', 'http://192.168.1.66');

        if (in_array($langSlug, ['c', 'cpp', 'crystal', 'fortran', 'haskell', 'julia', 'nim', 'rust'])) {
            $port = 5099;
        } else {
            $port = 81;
        }

        try {
            $response = Http::asForm()
                ->timeout(15)
                ->post("$url:$port", ['lang' => $langSlug, 'files' => json_encode($files), "commands" => json_encode($commands)]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return $response->body();
    }
}
