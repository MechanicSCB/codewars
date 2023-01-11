<?php


namespace App\Classes\Trash;


class KataSolver
{
    public function solve()
    {
        $inputs = [["H2O"], ["Mg(OH)2"], ["K4[ON(SO3)2]2"], ["B2H6"], ["C6H12O6"], ["Mo(CO)6"], ["Fe(C5H5)2"], ["(C5H5)Fe(CO)2CH3"], ["Pd[P(C6H5)3]4"], ["As2{Be4C5[BCo3(CO2)3]2}4Cu5"], ["{[Co(NH3)4(OH)2]3Co}(SO4)3"], ["C2H2(COOH)2"]];
        $outputs = "[{\"H\": 2, \"O\": 1}, {\"H\": 2, \"O\": 2, \"Mg\": 1}, {\"K\": 4, \"N\": 2, \"O\": 14, \"S\": 4}, {\"B\": 2, \"H\": 6}, {\"C\": 6, \"H\": 12, \"O\": 6}, {\"C\": 6, \"O\": 6, \"Mo\": 1}, {\"C\": 10, \"H\": 10, \"Fe\": 1}, {\"C\": 8, \"H\": 8, \"O\": 2, \"Fe\": 1}, {\"C\": 72, \"H\": 60, \"P\": 4, \"Pd\": 1}, {\"B\": 8, \"C\": 44, \"O\": 48, \"As\": 2, \"Be\": 16, \"Co\": 24, \"Cu\": 5}, {\"H\": 42, \"N\": 12, \"O\": 18, \"S\": 3, \"Co\": 4}, {\"C\": 4, \"H\": 4, \"O\": 4}]";
        $outputs = json_decode($outputs, 1);

        $n = 10;
        $formula = $inputs[$n][0];

        $return = (new MoleculeParser($formula))->run();

        ksort($return);
        ksort($outputs[$n]);
        $assert = json_encode($return) === json_encode($outputs[$n]);
        df(tmr(@$this->start), $assert, $inputs[$n], $return, $outputs[$n]);
    }


}
