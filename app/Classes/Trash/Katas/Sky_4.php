<?php

namespace App\Classes\Trash\Katas;

class Sky_4
{
    public function solve($input): array
    {
        foreach ($this->getSolutions() as $solution){
            if($this->compare($input,$solution[1])){
                return $solution[0];
            }
        }

        return [];
    }

    protected function compare(array $input, array $solution): bool
    {
        foreach ($input as $k => $n){
            if ($n === 0){
                continue;
            }

            if($n !== $solution[$k]){
                return false;
            }
        }

        return true;
    }

    protected function getSolutions(): array
    {
        $solutions = [];

        foreach ($this->getVars() as $var){
            $solutions[] = [$var, $this->getSolution($var)];
        }

        return $solutions;
    }

    protected function getSolution(array $matrix): array
    {
        $res = [];

        for($i=0;$i<=15;$i++) {
            $line = $this->getMatrixLine($matrix, $i);
            $res[] = $this->getViewNum($line);
        }

        return $res;
    }

    protected function getViewNum(array $line): int
    {
        $views = [
            4123 => 1, 4132 => 1, 4213 => 1, 4231 => 1, 4312 => 1, 4321 => 1,
            1423 => 2, 1432 => 2, 2143 => 2, 2413 => 2, 2431 => 2, 3124 => 2, 3142 => 2, 3214 => 2, 3241 => 2, 3412 => 2, 3421 => 2,
            1243 => 3, 1324 => 3, 1342 => 3, 2134 => 3, 2314 => 3, 2341 => 3,
            1234 => 4,
        ];

        return $views[implode('', $line)];
    }

    protected function getMatrixLine(array $matrix, int $lineId): array
    {
        $line = [];
        $lineIds = [
            [[0, 0], [1, 0], [2, 0], [3, 0]],
            [[0, 1], [1, 1], [2, 1], [3, 1]],
            [[0, 2], [1, 2], [2, 2], [3, 2]],
            [[0, 3], [1, 3], [2, 3], [3, 3]],

            [[0, 3], [0, 2], [0, 1], [0, 0]],
            [[1, 3], [1, 2], [1, 1], [1, 0]],
            [[2, 3], [2, 2], [2, 1], [2, 0]],
            [[3, 3], [3, 2], [3, 1], [3, 0]],

            [[3, 3], [2, 3], [1, 3], [0, 3]],
            [[3, 2], [2, 2], [1, 2], [0, 2]],
            [[3, 1], [2, 1], [1, 1], [0, 1]],
            [[3, 0], [2, 0], [1, 0], [0, 0]],

            [[3, 0], [3, 1], [3, 2], [3, 3]],
            [[2, 0], [2, 1], [2, 2], [2, 3]],
            [[1, 0], [1, 1], [1, 2], [1, 3]],
            [[0, 0], [0, 1], [0, 2], [0, 3]],
        ];

        foreach ($lineIds[$lineId] as $coords){
            $line[] = $matrix[$coords[0]][$coords[1]];
        }

        return $line;
    }

    protected function getVars(): array
    {
        $vars = [];
        $masks = $this->getMasks();

        foreach ($masks as $mask) {
            $vars = [...$vars, ...$this->getMaskVars($mask)];
        }

        return $vars;
    }

    protected function getMaskVars(array $mask): array
    {
        $vars = [];

        foreach ($this->getRows() as $row) {
            for ($r = 0; $r < 4; $r++) {
                for ($c = 0; $c < 4; $c++) {
                    $var[$r][$c] = $row[$mask[$r][$c] - 1];
                }
            }

            $vars[] = $var;
        }

        return $vars;
    }

    protected function getMasks(): array
    {
        $masks = [];
        $rows = $this->getRows();

        foreach ($rows as $row0) {
            foreach ($rows as $row1) {
                foreach ($rows as $row2) {
                    if ($this->isValidMask($mask = [[1, 2, 3, 4], $row0, $row1, $row2])) {
                        $masks[] = $mask;
                    }
                }
            }
        }

        return $masks;
    }

    protected function getRows(): array
    {
        $rows = [
            [1, 2, 3, 4],
            [1, 2, 4, 3],
            [1, 3, 2, 4],
            [1, 3, 4, 2],
            [1, 4, 2, 3],
            [1, 4, 3, 2],

            [2, 1, 3, 4],
            [2, 1, 4, 3],
            [2, 3, 1, 4],
            [2, 3, 4, 1],
            [2, 4, 1, 3],
            [2, 4, 3, 1],

            [3, 1, 2, 4],
            [3, 1, 4, 2],
            [3, 2, 1, 4],
            [3, 2, 4, 1],
            [3, 4, 1, 2],
            [3, 4, 2, 1],

            [4, 1, 2, 3],
            [4, 1, 3, 2],
            [4, 2, 1, 3],
            [4, 2, 3, 1],
            [4, 3, 1, 2],
            [4, 3, 2, 1],
        ];

        return $rows;
    }

    function isValidMask(array $mask): bool
    {
        for ($c = 0; $c < 4; $c++) {
            $tmp = [$mask[0][$c], $mask[1][$c], $mask[2][$c], $mask[3][$c]];
            sort($tmp);

            if ($tmp !== [1, 2, 3, 4]) {
                return false;
            }
        }

        return true;
    }

}
