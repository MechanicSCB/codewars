<?php


namespace App\Classes\Parsers;


use App\Models\Kata;
use DiDom\Document;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KataDescriptionHtmlParser
{
    public function uploadDescriptionImages()
    {
        $katasWithImages = (new KataDescriptionHtmlParser())->parseDescriptionImagesHref();

        $cnt = 0;

        foreach ($katasWithImages as $kataId => $kataHrefs){
            if (Storage::exists("public/katas_images/$kataId")){
                continue;
            }

            $cnt++;

            foreach ($kataHrefs as $href){
                $filename = Str::afterLast($href, '/');

                try {
                    Storage::put("public/katas_images/$kataId/$filename", file_get_contents($href));
                }catch (\Exception $exception){
                    //
                }
            }
        }

        df(tmr(@$this->start), $katasWithImages);

    }

    public function parseDescriptionImagesHref():array
    {
        $katasWithImages = Kata::where('description', 'like', '%<img %')->get(['id', 'description'])->toArray();

        $imagesHrefs = [];

        foreach ($katasWithImages as $kata){
            $document = new Document($kata['description']);
            $images = $document->find('img');

            foreach ($images as $image){
                $imagesHrefs[$kata['id']][] = $image->getAttribute('src');
            }
        }

        return $imagesHrefs;
    }
}
