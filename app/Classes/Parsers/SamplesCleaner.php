<?php


namespace App\Classes\Parsers;


use App\Models\Sample;

class SamplesCleaner
{
    public function deleteNullArguments()
    {
        $samples = Sample::get();

        foreach ($samples as $sample) {
            $keysToDeletion = [];

            // get wrongKeys
            foreach ($argsList = json_decode($sample['args_list']) as $key => $args) {
                if (! is_array($args)) {
                    $keysToDeletion[] = $key;
                }
            }

            if (count($keysToDeletion) === 0) {
                continue;
            }

            $expectedList = json_decode($sample['expected_list']);

            // delete wrong items and save sample
            foreach ($keysToDeletion as $keyToDeletion) {
                unset($argsList[$keyToDeletion]);
                unset($expectedList[$keyToDeletion]);
            }

            $sample['args_list'] = array_values($argsList);
            $sample['expected_list'] = array_values($expectedList);
            $sample->save();
        }
    }

    public function cleanOrigLangJsonSamples(string $langSlug, int $limit = null)
    {
        $samples = json_decode(file_get_contents(base_path("database/data/json/samples/$langSlug.json")), 1);

        if($limit){
            $samples = array_slice($samples, 0 ,$limit);
        }

        foreach ($samples as $kataId => &$sample) {
            $argsList = json_decode($sample['args_list'], 1);
            $expectedList = json_decode($sample['expected_list'], 1);

            if (! is_array($expectedList)) {
                $expectedList = array_fill(0, count($argsList), null);
            }

            if (count($argsList) !== count($expectedList)) {
                df(tmr(@$this->start), $sample);
            }

            $pairs = [];

            foreach ($argsList as $key => $args) {
                if(json_encode($argsList) > 30000 && $key > 20){
                    continue;
                }

                if($key > 100){
                    continue;
                }

                if(strlen(json_encode($args)) > 5000){
                    continue;
                }

                if(strlen(json_encode($expectedList[$key])) > 5000){
                    continue;
                }

                $pairs[] = [$args, $expectedList[$key]];
            }

            $argsList = [];
            $expectedList = [];

            foreach ($pairs as $pair){
                if($pair[0] === null){
                    continue;
                }

                $argsList[] = $pair[0];
                $expectedList[] = $pair[1];
            }

            $sample['args_list'] = json_encode($argsList);
            $sample['expected_list'] = json_encode($expectedList);
        }

        return $samples;
    }

}
