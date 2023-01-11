<?php


namespace App\Classes\Trash;


class MoleculeParser
{
    public function __construct(private string $formula)
    {
        $this->formula = str_replace(['[', '{'], '(', $this->formula);
        $this->formula = str_replace([']', '}'], ')', $this->formula);
    }

    public function run()
    {
        $formula = $this->formula;


        $parts = $this->getParts($formula);

        while (str_contains(json_encode($parts), '(')){
            $keysToDel = [];

            foreach ($parts as $key => $part){
                if(str_contains($part['val'], '(')){
                    $subParts = $this->getParts($part['val']);
                    $subParts = array_map(fn($v) => ['val' => $v['val'],'num' => $v['num']*$part['num']],$subParts);
                    //unset($parts[$key]);
                    $keysToDel[] = $key;
                    $parts = [...$parts, ...$subParts];
                }
            }

            $parts = array_values(array_filter($parts, fn($k) => ! in_array($k, $keysToDel), ARRAY_FILTER_USE_KEY));
        }

        foreach ($parts as $key => $part) {
            // получаем атомы из простой формулы 'H2O' => {"H":2,"O":1}
            $atoms = $this->getAtoms($part['val']);
            $atoms = array_map(fn($v) => $v*$part['num'], $atoms);
            $parts[$key] = $atoms;
        }

        $res = [];

        foreach ($parts as $part){
            foreach ($part as $atom => $n){
                $res[$atom] = $n + @$res[$atom];
            }
        }

        ksort($res);

        return $res;
    }

    private function getAtoms(string $simple): array
    {
        $tmp = [];

        for ($i = 0; $i < strlen($simple); $i++) {
            $chr = $simple[$i];

            if (in_array($chr, range('A', 'Z'))) {
                if (@$atom) {
                    $tmp[] = $atom;
                }

                $atom = [$chr, 0];
            }

            if (in_array($chr, range('a', 'z'))) {
                $atom[0] .= $chr;
            }

            if (in_array($chr, range(0, 9))) {
                if ($atom[1] > 0) {
                    $atom[1] *= 10;
                }

                $atom[1] += +$chr;
            }
        }

        $tmp[] = $atom;

        $atoms = [];

        foreach ($tmp as $item) {
            $n = $item[1] === 0 ? 1 : $item[1];
            $atoms[$item[0]] = $n + @$atoms[$item[0]];
        }

        return $atoms;
    }

    public function getParts(string $formula): array
    {
        $sep = '|';
        $arrFormula = str_split($formula);

        $opened = 0;

        for ($i = 0; $i < count($arrFormula); $i++) {
            $v = $arrFormula[$i];

            if ($v === '(') {
                if ($opened === 0) {
                    $arrFormula[$i] = $sep;
                }

                $opened++;
            }

            if ($v === ')') {
                if ($opened === 1) {
                    $arrFormula[$i] = $sep;
                }

                $opened--;
            }
        }

        $tmp = implode('', $arrFormula);
        $tmp = trim($tmp, $sep);
        $tmp = explode($sep, $tmp);
        $parts = array_map(fn($v) => ['val' => $v, 'num' => 1], $tmp);

        foreach ($parts as $key => $part) {
            // eсли кусок начинается с цифры, то отрезаем цифры и помещаев в чиловое значение предыдущего куска
            if (in_array(@$part['val'][0], range(0, 9))) {
                [$num, $str] = $this->fetchHeadedDigits($part['val']);
                $parts[$key]['val'] = $str;
                $parts[$key - 1]['num'] = $num;
            }
        }

        $parts = array_filter($parts, fn($v) => $v['val']);
        $parts = array_values($parts);

        return $parts;
    }

    // отрезать от строки первые цифры и вернуть массив [$num, $str]
    private function fetchHeadedDigits(string $part): array
    {
        for ($i = 0; $i < strlen($part); $i++) {
            if (! is_numeric($part[$i])) {
                break;
            }
        }

        $num = +substr($part, 0, $i);
        $str = substr($part, $i);

        return [$num, $str];
    }
}
