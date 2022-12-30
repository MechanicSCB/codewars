<?php


namespace App\Classes\Parsers;


use DiDom\Document;

class KatasListsHtmlParser
{
    public function getKatasIds(array $ranks = [1, 2, 3, 4, 5, 6, 7, 8]): array
    {
        $katasIds = [];

        foreach ($ranks as $rank) {
            $html = file_get_contents(base_path("database/data/html/katas_lists/katas_rank$rank.html"));
            $document = new Document($html);
            $katasElements = $document->find('.list-item-kata');

            foreach ($katasElements as $kataElement) {
                $katasIds[] = $kataElement->getAttribute('id');
            }

        }

        return $katasIds;
    }

}
