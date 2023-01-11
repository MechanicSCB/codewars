<?php


namespace App\Classes\Trash;


class KataSolver
{
    //function parse_molecule($formula){
    //    return (new MoleculeParser($formula))->run();
    //}

    public function solve()
    {
        $input = "Al(((MgCaArPAlPO)7(ClNUqnUubCaArBNO)23S(ClUunAlUqnSLiAlUubBeUuo)5(UtnUHeFeUUtnFeUup)24Be(UupPUupArSCHCl)15)9(MgFUqn(UunKCaNeHCSi)20F(NLiUubSBUun)6Uuo)14Fe(P(HeUuoHeUUunO)14(NeFeBeBUbnUuoUubNe)20(CKSiNeUuoUBeNaUupCa)23(SiSUuoKFeUunS)3(UtnUupUuuUtnUqnCCaBBSi)3PUuu(NeClOLiFFClLiK)12(UqnBUuuUuoUbnBKUtnLi)6)6((FeFeArNClNeSi)7(LiKFeCLiUuoUunNAlNe)8(BeFUqnUtnP)20Mg(HHeUupArUUbnCBCCa)16(UqnUUClSiKUubKCaUup)19(FFCaBBCa)19)3UubUun((FeNUtnPCaAr)4UuoArUqnUun)10F(U(OKBeHeUBHeUqnNUqn)21(ArFOUupUtnFeNBe)2Ubn(OSUubUAr)19Uun)16)21(C(NCl(FNUunArFUubUqnLiSN)23K(UbnClClCAlFeBNe)4(CUtnKBeMg)21)13MgH((SUbnUbnUubUubUqn)13(UqnArBUupUubOPNaSi)23(ClFBeKUunOOMg)2UuoSiBe(KSHeArUuuBe)22(HBUtnSBeHSiH)17H)11(Uub(MgUUunFeKNCUuoUtnUup)18(UubBeLiKUunNaUuuClUtn)17CB)10AlNa)14UupB(H((ArNaUqnNePSiSiUbnBeUbn)21(UbnOHKFeClCa)3(AlUuuHUuuSiBeUuuCa)10Ar(NeBPHBeCaOK)10SiS(SiUuuUubUupLi)15)22((SiSiClUuoKONeUup)13Uup(UunClCPSBNaUuuHe)6Ar(HeCaUuuLiMgCaUbnO)22(HeUupUuuLiUbnFHUbn)24(BeUtnClClLiSCl)12B)13(UqnCl(NaMgCSiUqnCF)18(BNaUunAlKClHF)19Al(UCKCAlHFe)5(CaUqnSiUbnMg)3UunAr(NaNFeMgNeNa)9)4NaBNa(Utn(NaFNaKUuu)4(FeBUuuSMgFOK)21Uqn(ArOArCaSN)20(MgLiUuuPLiCaUbnUuoNe)4)16)18(SiUub((ArAlOUuoFeO)7Uuu(UunUuuSKUbn)14(PLiNUupFFUun)25UbnUqnCaHeAr)25((ClULiUupFLi)5(LiNUuuClUup)19(HPBArNePCa)3Ubn(UqnAlKUubOH)10(UuuArUqnFeMgNa)3)21(B(BUuuHeAlHLiFUuu)3U(FBePPUup)3(UqnMgKUupUtnClCa)17)17)22((UtnUuuNPMg(PUubUubCaNeUupSiUunSSi)4H(BUqnLiNaBeB)12Uuo)7NeBeU((FeUFCUuoUuuUqnBe)12(OUupUtnFSiClOB)8BeUupUbn(UtnUbnLiUuoUNaUubPCa)11C(OCUUtnKC)19)23(P(HeAlUupHeUtnU)4UbnNLi(BMgAlUuoClUub)25)15Cl((UupSiUunBUSiNeUtnCl)19(ClUuuUuuBeUqnUtnUun)14(UuuNaUbnNeNeUubAlNaNaC)2(NeOFUuoUupUubUuu)25ClNe(BNHeUunSUqnKCl)9MgP)15((HUupClUuuN)15N(ArUubKUuuClKSiOP)17(ArNUunNaMgSiPUunUqnNa)18(PSNUuuUuoClSC)5Uun)24)19";
        $exp = "{\"Al\":28647,\"Mg\":46347,\"Ca\":60430,\"Ar\":72846,\"P\":69255,\"O\":94854,\"Cl\":89826,\"N\":80748,\"Uqn\":77934,\"Uub\":70455,\"B\":66093,\"S\":56840,\"Uun\":74864,\"Li\":70837,\"Be\":59662,\"Uuo\":47701,\"Utn\":61434,\"U\":61404,\"He\":45257,\"Fe\":36861,\"Uup\":94557,\"C\":51588,\"H\":48316,\"F\":80038,\"K\":96146,\"Ne\":50023,\"Si\":83382,\"Ubn\":59731,\"Na\":50769,\"Uuu\":103950}";
        $act = "{\"Al\":166909,\"Uup\":513616,\"B\":197681,\"F\":445861,\"Na\":154809,\"Ar\":208246,\"O\":313801,\"Utn\":190008,\"Fe\":83866,\"N\":379460,\"Be\":116443,\"Ubn\":206180,\"Cl\":474018,\"C\":92243,\"Ne\":178262,\"S\":196168,\"Uub\":250807,\"Uqn\":261782,\"P\":285823,\"Si\":246038,\"K\":346723,\"Uun\":412226,\"Mg\":209550,\"Uuo\":177259,\"He\":114620,\"Uuu\":517490,\"H\":179694,\"U\":112052,\"Li\":406957,\"Ca\":160059}";
        $exp = json_decode($exp,1);

        $input = "H2O";
        //$input = "((Mg)7(Cl))23S((Mg)7(Cl))23S";
        $input = "((Mg))((A)7)";
        $exp = $this->parse_molecule($input);
        $return = (new MoleculeParser($input))->run();
        ksort($exp);
        ksort($return);
        $assert = json_encode($exp) === json_encode($return);
        df(tmr(@$this->start),$assert, $exp,$return);
    }

    function parse_molecule(string $formula): array
    {
        $molecule = '('.$formula.')';
        do {
            $molecule = preg_replace_callback('/[\(\[\{](\w+)[\)\]\}](\d+)?/', function ($matches) {
                return $this->parseBrackets($matches[1], $matches[2] ?? 1);
            }, $molecule, -1, $count);
        } while ($count);

        $atoms = [];
        preg_match_all('/([A-Z][a-z]*)(\d+)?/', $molecule, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $atom = $match[1];
            $atoms[$atom] =  ($atoms[$atom] ?? 0) + $match[2];
        }
        return $atoms;
    }

    function parseBrackets($string, $multiply)
    {
        return preg_replace_callback('/([A-Z][a-z]*)(\d+)?/', function ($matches) use ($multiply) {
            return $matches[1].($matches[2] ?? 1)*$multiply;
        }, $string);
    }

    public function solveOrig()
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
