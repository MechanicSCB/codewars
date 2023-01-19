<?php


namespace App\Classes\Trash\Katas;


class GapInPrimes
{
    public function solve()
    {
        // 11000000
        $input = [2, 3, 10];
        //$input = [3,3,10];
        //$input = [2, 100, 100];
        //$input = [4,30000,100000];
        ////$input = [2,1000000,1100000];
        //$input = [2,1000000,3100000];
        //$input = [2,1000000,1100000];
        //$input = [2,10000000,11000000];

        $res = $this->gap(...$input);
        df(tmr(@$this->start), $res);
    }

    function gap($g, $m, $n)
    {
        $primes = $this->getPrimes(floor(sqrt($n)));

        $tmp = [];

        for ($i = $m; $i <= $n; $i++) {
            if ($this->isPrime($i, $primes)) {
                $tmp[] = $i;
            }

            if(count($tmp) > 1 &&
                ($tmp[count($tmp)-1]-$tmp[count($tmp)-2] === $g)
            ){
                return [$tmp[count($tmp)-2],$tmp[count($tmp)-1]];
            }
        }

        return null;
    }

    function isPrime($n, $primes)
    {
        if(in_array($n, $primes)){
            return true;
        }

        foreach ($primes as $prime) {
            if ($n % $prime === 0) {
                return false;
            }
        }

        return true;
    }

    function getPrimes($n)
    {
        $arr = array_fill(2, $n - 1, 1);

        for ($i = 2; $i <= $n; $i++) {
            if (! array_key_exists($i, $arr)) {
                continue;
            }

            for ($j = $i * 2; $j <= $n; $j += $i) {
                unset($arr[$j]);
            }

        }

        return array_keys($arr);
    }


}
