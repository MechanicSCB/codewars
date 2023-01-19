<?php


namespace App\Classes\Trash;


class BestTravel
{
    public function solve()
    {
        //df(tmr(@$this->start), 777);
        $inputs = [[163, 3, [50, 55, 56, 57, 58]], [163, 3, [50]], [230, 3, [91, 74, 73, 85, 73, 81, 87]], [331, 2, [91, 74, 73, 85, 73, 81, 87]], [331, 4, [91, 74, 73, 85, 73, 81, 87]], [331, 5, [91, 74, 73, 85, 73, 81, 87]], [331, 1, [91, 74, 73, 85, 73, 81, 87]], [700, 6, [91, 74, 73, 85, 73, 81, 87]], [230, 4, [100, 76, 56, 44, 89, 73, 68, 56, 64, 123, 2333, 144, 50, 132, 123, 34, 89]], [430, 5, [100, 76, 56, 44, 89, 73, 68, 56, 64, 123, 2333, 144, 50, 132, 123, 34, 89]], [430, 8, [100, 76, 56, 44, 89, 73, 68, 56, 64, 123, 2333, 144, 50, 132, 123, 34, 89]], [880, 8, [100, 76, 56, 44, 89, 73, 68, 56, 64, 123, 2333, 144, 50, 132, 123, 34, 89]], [2430, 15, [100, 76, 56, 44, 89, 73, 68, 56, 64, 123, 2333, 144, 50, 132, 123, 34, 89]], [100, 2, [100, 76, 56, 44, 89, 73, 68, 56, 64, 123, 2333, 144, 50, 132, 123, 34, 89]], [276, 3, [100, 76, 56, 44, 89, 73, 68, 56, 64, 123, 2333, 144, 50, 132, 123, 34, 89]], [3760, 17, [100, 76, 56, 44, 89, 73, 68, 56, 64, 123, 2333, 144, 50, 132, 123, 34, 89]], [3760, 40, [100, 76, 56, 44, 89, 73, 68, 56, 64, 123, 2333, 144, 50, 132, 123, 34, 89]], [50, 1, [100, 76, 56, 44, 89, 73, 68, 56, 64, 123, 2333, 144, 50, 132, 123, 34, 89]], [1000, 18, [100, 76, 56, 44, 89, 73, 68, 56, 64, 123, 2333, 144, 50, 132, 123, 34, 89]], [230, 4, [100, 64, 123, 2333, 144, 50, 132, 123, 34, 89]], [230, 2, [100, 64, 123, 2333, 144, 50, 132, 123, 34, 89]], [2333, 1, [100, 64, 123, 2333, 144, 50, 132, 123, 34, 89]], [2333, 8, [100, 64, 123, 2333, 144, 50, 132, 123, 34, 89]], [2300, 4, [1000, 640, 1230, 2333, 1440, 500, 1320, 1230, 340, 890, 732, 1346]], [2300, 5, [1000, 640, 1230, 2333, 1440, 500, 1320, 1230, 340, 890, 732, 1346]], [2332, 3, [1000, 640, 1230, 2333, 1440, 500, 1320, 1230, 340, 890, 732, 1346]], [23331, 8, [1000, 640, 1230, 2333, 1440, 500, 1320, 1230, 340, 890, 732, 1346]], [331, 2, [1000, 640, 1230, 2333, 1440, 500, 1320, 1230, 340, 890, 732, 1346]]];
        $outputs = [163, -1, 228, 178, 331, -1, 91, 491, 230, 430, -1, 876, 1287, 100, 276, 3654, -1, 50, -1, -1, 223, 2333, 825, 2212, -1, 2326, 10789, -1];
        // 11, 12, 15
        $n = 0;
        $input = $inputs[$n];
        //sort($input[2]);
        //df(tmr(@$this->start),$input[2]);

        $res = $this->choose_best_sum(...$input);
        $output = $outputs[$n];
        $assert = $res === $output;

        df(tmr(@$this->start), $assert, $input, $output, $res);

    }

    function choose_best_sum($limit, $cnt, $distances): int
    {
        sort($distances);

        return $this->choose_best_sum_rec($limit, $cnt, $distances);

    }

    // return integer (sum of elements)
    function choose_best_sum3($limit, $cnt, $distances): int
    {
        if ($cnt === 0) {
            return 0;
        }

        $res = -1;

        foreach ($distances as $key => $distance) {
            if ($distance > $limit) {
                continue;
            }

            $tmp = $this->choose_best_sum3($limit - $distance, $cnt - 1, array_slice($distances, $key + 1));

            if ($tmp !== -1) {
                $nr = $distance + $tmp;

                if ($nr > $res && $nr <= $limit) {
                    $res = $nr;
                }
            }
        }

        return $res;
    }


    // return integer (sum of elements) with memoization
    function choose_best_sum_rec($limit, $cnt, $distances, &$memo = []): int
    {
        if(count($memo) > 13){
            df(tmr(@$this->start), $limit, $cnt, $distances, $memo);
        }

        $mKey = "$limit," . implode(',', $distances);

        if (isset($memo[$mKey])) {
            return $memo[$mKey];
        }

        if (count($distances) < $cnt) {
            $memo[$mKey] = -1;

            return -1;
        }

        // если мимимальная сумма $cnt элементов больше $limit return -1
        if (array_sum(array_slice($distances, 0, $cnt)) > $limit) {
            $memo[$mKey] = -1;

            return -1;
        }

        $distances = array_filter($distances, fn($v) => $v <= $limit);

        if (count($distances) === 0) {
            $memo[$mKey] = 0;

            return 0;
        }

        if ($cnt === 1) {
            $memo[$mKey] = max($distances);

            return max($distances);
        }

        $max = 0;

        $cnt--;

        foreach ($distances as $key => $distance) {
            $rest = $distances;
            unset($rest[$key]);
            $rest = array_values($rest);

            $tmp = $this->choose_best_sum_rec($limit - $distance, $cnt, $rest, $memo);

            if ($tmp > 0) {
                $tmp += $distance;
            }

            if ($tmp > $max) {
                $max = $tmp;
            }

        }

        $memo[$mKey] = $max;

        return $max;
    }

    // return integer (sum of elements)
    function choose_best_sum2($limit, $cnt, $distances): int
    {
        if (count($distances) < $cnt) {
            return -1;
        }

        // если мимимальная сумма $cnt элементов больше $limit return -1
        if (array_sum(array_slice($distances, 0, $cnt)) > $limit) {
            return -1;
        }

        $distances = array_filter($distances, fn($v) => $v <= $limit);

        if (count($distances) === 0) {
            return 0;
        }

        if ($cnt === 1) {
            return max($distances);
        }

        $max = 0;

        $cnt--;

        foreach ($distances as $key => $distance) {
            $rest = $distances;
            unset($rest[$key]);
            $rest = array_values($rest);

            $tmp = $this->choose_best_sum2($limit - $distance, $cnt, $rest);

            if ($tmp > 0) {
                $tmp += $distance;
            }

            if ($tmp > $max) {
                $max = $tmp;
            }
        }

        return $max;
    }

    // return array of distances
    function choose_best_sum1($limit, $cnt, $distances)
    {
        $distances = array_filter($distances, fn($v) => $v <= $limit);

        if (count($distances) === 0 || count($distances) < $cnt) {
            return [];
        }

        if ($cnt === 1) {
            return [max($distances)];
        }

        $carry = [];

        $cnt--;

        foreach ($distances as $key => $distance) {
            $rest = $distances;
            unset($rest[$key]);
            $rest = array_values($rest);


            $tmp = $this->choose_best_sum1($limit - $distance, $cnt, $rest);

            if (count($tmp) === $cnt) {
                $tmp = [$distance, ...$tmp];
            }

            if (array_sum($tmp) > array_sum($carry)) {
                if ($cnt === 7 && $key > 0) {
                    //df(tmr(@$this->start), $distances, $key, $distance, $rest, $carry, $tmp);
                }

                $carry = $tmp;
            }
        }

        //if ($cnt ===7) {
        //    df(tmr(@$this->start), $distances, $rest, $carry, $tmp);
        //}


        return $carry;
    }
}
