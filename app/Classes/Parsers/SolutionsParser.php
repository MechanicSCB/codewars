<?php


namespace App\Classes\Parsers;


use DiDom\Document;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SolutionsParser
{
    public function parseMissingLangSolutions(string $langSlug): array
    {
        // Get katas ids of all html file from lang folder
        $existedLangHtmlFileNames = scandir(database_path("data/html/solutions/$langSlug"));
        $existedLangHtmlFileNames = array_values(array_filter($existedLangHtmlFileNames, fn($v) => ! in_array($v, ['.', '..'])));
        $existedLangHtmlFileKatasIds = array_map(fn($v) => Str::between($v, "$langSlug/", ".html"), $existedLangHtmlFileNames);

        // Get existed solutions from json file
        $existedLangJsonSolutions = json_decode(file_get_contents(database_path("data/json/parsed_solutions/$langSlug.json")), 1);
        $existedLangJsonFileKatasIds = array_keys($existedLangJsonSolutions ?? []);

        // Get missing in existed json file katas ids
        $missingKatasIds = array_diff($existedLangHtmlFileKatasIds, $existedLangJsonFileKatasIds);

        // parse missing solutions
        $parsedLangSolutions = $this->parseKatasSolutionsHtml($missingKatasIds, $langSlug);

        return $parsedLangSolutions;
    }

    public function parseKatasSolutionsHtml(array $katasIds, string $langSlug): array
    {
        $solutions = [];

        foreach ($katasIds as $kataId) {
            $solutions[$kataId] = $this->parseKataSolutionsHtml($kataId, $langSlug);
        }

        return $solutions;
    }

    public function parseKataSolutionsHtml(string $kataId, string $langSlug): array
    {
        $kataSolutions = [];
        $html = file_get_contents(base_path("database/data/html/solutions/$langSlug/$kataId.html"));
        $html = str_replace('\n', "\n", $html);
        $html = stripslashes($html);
        $document = new Document($html);
        //$solutionsElements = $document->find('li[data-solution-group-group-id-value]');
        $solutionsElements = $document->find('[data-solution-group-group-id-value]');

        foreach ($solutionsElements as $solutionElement) {
            if ($variations = $solutionElement->first('div[data-controller="solution-variations"]')) {
                $variations = $variations->text();
            }

            $kataSolutions[] = [
                'body' => $solutionElement->first('code')->text(),
                'users' => $solutionElement->first('.solution-group-users-list')->text(),
                'variations' => (int)$variations,
                'best_practice' => (int)$solutionElement->first('a[data-label="best_practice"] span')->text(),
                'clever' => (int)$solutionElement->first('a[data-label="clever"] span')->text(),
                'comments' => (int)$solutionElement->first('a.js-show-comments span')->text(),
            ];
        }

        return $kataSolutions;
    }
}
