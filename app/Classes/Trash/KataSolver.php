<?php


namespace App\Classes\Trash;


use App\Classes\Trash\Katas\PathFinder2;
use Illuminate\Support\Str;
use function Symfony\Component\String\b;

class KataSolver
{
    public function solve()
    {
        $inputs = [[".W.\n.W.\n..."], ["......\n......\n......\n......\n......\n......"], ["......\n......\n......\n......\n.....W\n....W."], [".W...W...W...\n.W.W.W.W.W.W.\n.W.W.W.W.W.W.\n.W.W.W.W.W.W.\n.W.W.W.W.W.W.\n.W.W.W.W.W.W.\n.W.W.W.W.W.W.\n.W.W.W.W.W.W.\n.W.W.W.W.W.W.\n.W.W.W.W.W.W.\n.W.W.W.W.W.W.\n.W.W.W.W.W.W.\n...W...W...W."], ["."]];
        $outputs = [4, 10, -1, 96, 0];
        $n = 0;
        $input = $inputs[$n];

        //$input[0] = explode("\n", $input[0]);

        $res = $this->path_finder(...$input);
        df(tmr(@$this->start), $inputs[$n], $outputs[$n], $res);
    }

    function path_finder($maze)
    {
        $maze = explode("\n", $maze);

        foreach ($maze as $key => $row) {
            $maze[$key] = str_split($row);
        }

        return (new PathFinder2($maze))->run();
    }

}













