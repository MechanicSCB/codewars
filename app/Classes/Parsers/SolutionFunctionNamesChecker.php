<?php


namespace App\Classes\Parsers;


use App\Models\Solution;
use Illuminate\Support\Str;

class SolutionFunctionNamesChecker
{
    public function getSolutionFunctionName(string $code): string
    {
        $delimiter = '|delimiter|';
        $code = str_replace(['function ', 'def '], $delimiter, $code);
        $name = Str::betweenFirst($code, $delimiter, '(');
        $name = str_replace(['='], '', $name);

        return trim($name);
    }
}
