<?php


namespace App\Classes\Trash\Katas;


class BestSumMemo
{
    public function solve()
    {
        df(tmr(@$this->start), 87);
        //$input = [43, [2, 4, 6, 8, 17]];
        //$input = [7, [5,3,4]];
        $input = [72, [2, 4, 6]];
        //$input = [300, [7,14]];

        $rec = $this->bestSumMemo(...$input);
        $res = $this->bestSumTab(...$input);

        df(tmr(@$this->start), $rec, $res);
    }

    function bestSumTab($targetSum, $numbers)
    {
        $tab = array_fill(0, $targetSum + 1, null);
        $tab[0] = [[]];

        for ($i = 0; $i <= $targetSum; $i++) {
            if ($tab[$i] === null) {
                continue;
            }

            foreach ($numbers as $number) {
                $tab[$i + $number][] = [...last($tab[$i]), $number];
            }
        }

        df(tmr(@$this->start), $tab);

        $res = last($tab[$targetSum] ?? [null]);

        return $res;
    }

    function howSumTab($targetSum, $numbers)
    {
        $tab = array_fill(0, $targetSum + 1, null);
        $tab[0] = [[]];

        for ($i = 0; $i <= $targetSum; $i++) {
            if ($tab[$i] === null) {
                continue;
            }

            foreach ($numbers as $number) {
                $tab[$i + $number][] = [...last($tab[$i]), $number];
            }
        }

        $res = last($tab[$targetSum] ?? [[]]);

        return $res;
    }

    function canSumTab($targetSum, $numbers)
    {
        $tab = array_fill(0, $targetSum + 1, false);
        $tab[0] = true;

        for ($i = 0; $i <= $targetSum; $i++) {
            if ($tab[$i] === false) {
                continue;
            }

            foreach ($numbers as $number) {
                // if ($i + $number > $targetSum) continue;

                $tab[$i + $number] = true;
            }
        }

        return $tab[$targetSum];
    }

    function bestSumMemo($sum, $numbers, &$memo = [])
    {
        if (array_key_exists($sum, $memo)) {
            return $memo[$sum];
        }

        if ($sum < 0) {
            return null;
        }

        if ($sum === 0) {
            return [];
        }

        $res = null;

        foreach ($numbers as $number) {
            $tmp = $this->bestSumMemo($sum - $number, $numbers, $memo);

            if ($tmp !== null) {
                $res ??= [];

                if (count($res) && count($tmp) >= count($res)) {
                    continue;
                }

                $res = [$number, ...$tmp];
            }
        }

        $memo[$sum] = $res;

        // возвращаем итоговый false, если нет вариантов
        return $res;
    }

    function bestSumAlvin($targetSum, $numbers, &$memo = [])
    {
        if (array_key_exists($targetSum, $memo)) {
            return $memo[$targetSum];
        }

        if ($targetSum === 0) {
            return [];
        }
        if ($targetSum < 0) {
            return null;
        }

        $shortestCombination = null;

        foreach ($numbers as $num) {
            $remainder = $targetSum - $num;
            $remainderCombination = $this->bestSumAlvin($remainder, $numbers, $memo);

            if ($remainderCombination !== null) {
                $combination = [...$remainderCombination, $num];

                // if the combination is shorter than the current 'shortest', update it
                if ($shortestCombination === null || count($combination) < count($shortestCombination)) {
                    $shortestCombination = $combination;
                }
            }
        }

        $memo[$targetSum] = $shortestCombination;

        return $shortestCombination;
    }

    function bestSum($sum, $numbers)
    {
        if ($sum < 0) {
            return null;
        }

        if ($sum === 0) {
            return [];
        }

        $res = null;

        foreach ($numbers as $number) {
            $tmp = $this->bestSum($sum - $number, $numbers);

            if ($tmp !== null) {
                $res ??= [];

                if (count($res) && count($tmp) >= count($res)) {
                    continue;
                }

                $res = [$number, ...$tmp];
            }
        }

        // возвращаем итоговый false, если нет вариантов
        return $res;
    }

    function howSumMemo($sum, $numbers, &$memo = [])
    {
        if (isset($memo[$sum])) {
            return $memo[$sum];
        }

        if ($sum < 0) {
            return [];
        }

        if ($sum === 0) {
            return [0];
        }

        foreach ($numbers as $number) {
            $memo[$sum] = $this->howSumMemo($sum - $number, $numbers, $memo);

            if (count($memo[$sum])) {
                // возвращаем первый попавшийся true
                return [$number, ...$memo[$sum]];
            }
        }

        // возвращаем итоговый false, если нет вариантов
        return [];
    }

    function howSum($sum, $numbers)
    {
        if ($sum < 0) {
            return [];
        }

        if ($sum === 0) {
            return [0];
        }

        foreach ($numbers as $number) {
            $res = $this->howSum($sum - $number, $numbers);

            if (count($res)) {
                // возвращаем первый попавшийся true
                return [$number, ...$res];
            }
        }

        // возвращаем итоговый false, если нет вариантов
        return [];
    }

    function canSumMemo($sum, $numbers, &$memo = [])
    {
        if (isset($memo[$sum])) {
            return $memo[$sum];
        }

        if ($sum < 0) {
            return false;
        }

        if (in_array($sum, $numbers)) {
            return true;
        }

        foreach ($numbers as $number) {
            $memo[$sum - $number] = $this->canSumMemo($sum - $number, $numbers, $memo);

            if ($memo[$sum - $number]) {
                // возвращаем первый попавшийся true
                return $memo[$sum - $number];
            }
        }

        // возвращаем итоговый false, если нет вариантов
        return false;
    }

    function canSum($sum, $numbers)
    {
        if ($sum < 0) {
            return false;
        }

        if (in_array($sum, $numbers)) {
            return true;
        }

        foreach ($numbers as $number) {
            $res = $this->canSum($sum - $number, $numbers);

            if ($res) {
                // возвращаем первый попавшийся true
                return $res;
            }
        }

        // возвращаем итоговый false, если нет вариантов
        return false;
    }
}
