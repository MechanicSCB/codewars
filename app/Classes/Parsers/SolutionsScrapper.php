<?php


namespace App\Classes\Parsers;


use App\Models\Kata;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SolutionsScrapper
{
    protected array $headers;
    protected string $lang;

    public function __construct(string $lang = null)
    {
        $this->lang = $lang ?? 'php';
    }

    public function scrap(array $ranks = [1, 2], string $lang = null)
    {
        $lang ??= $this->lang;
        set_time_limit(300);
        //df(tmr(@$this->start), $lang);

        $allLangKatasIds = $this->getKatasIdsForScrapping($ranks, $lang);
        //df(tmr(@$this->start), $allLangKatasIds);
        $existedLangFileNames = scandir(base_path("database/data/html/solutions/$lang"));
        $existedLangFileNames = array_values(array_filter($existedLangFileNames, fn($v) => str_ends_with($v, '.html')));
        $existedLangKatasIds = array_map(fn($v) => Str::between($v, "$lang/", ".html"), $existedLangFileNames);
        $missedKatasIds = array_values(array_diff($allLangKatasIds, $existedLangKatasIds));

        //df(tmr(@$this->start), $missedKatasIds);
        $katasIds = $missedKatasIds;
        $katasIds = array_slice($katasIds, 0, 100);

        $this->saveSolutionsHtmlPagesToFile($katasIds, $lang);

        df(tmr(@$this->start), $katasIds);
    }

    public function getKatasIdsForScrapping(array $ranks = [3, 4], string $lang = null): array
    {
        $lang ??= $this->lang;

        $katasIds = Kata::query()
            ->whereIn('rank', $ranks)
            ->whereRelation('langs', 'slug', $lang)
            ->pluck('id')
            ->toArray();

        return $katasIds;
    }

    public function saveSolutionsHtmlPagesToFile(array $katasIds, string $lang = null)
    {
        $lang ??= $this->lang;
        $this->headers = $this->getHeaders();

        $responses = Http::pool(function (Pool $pool) use ($katasIds, $lang) {
            foreach ($katasIds as $kataId) {
                $url = "https://www.codewars.com/kata/$kataId/solutions/$lang?show-solutions=1";
                $return[] = $pool->as($kataId)->timeout(30)->withHeaders($this->headers)->post($url);
            }

            return $return ?? [];
        });

        foreach ($responses as $kataId => $response) {
            if (! is_a($response, 'Illuminate\Http\Client\Response')) {
                continue;
            }

            file_put_contents(base_path("database/data/html/solutions/$lang/$kataId.html"), $response->body());
        }
    }

    public function getHeaders()
    {
        $headers = [
            'Host' => 'www.codewars.com',
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:103.0) Gecko/20100101 Firefox/103.0',
            'Accept' => 'application/json, text/plain, */*',
            'Accept-Language' => 'ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3',
            'Accept-Encoding' => 'gzip, deflate, br',
            'Referer' => 'https://www.codewars.com/kata/5a331ea7ee1aae8f24000175/solutions/javascript',
            'x-requested-with' => 'XMLHttpRequest',
            'Content-Type' => 'application/json',
            'authorization' => 'eyJhbGciOiJIUzI1NiJ9.eyJpZCI6IjYyZWMyZjhmNzBmMzk1Nzg3N2MzYTdjMSIsImV4cCI6MTY2MTYyNDI0M30.cMnqHKatD4TKVu1uGwMQQUEJf_LVqUezAqc8JOF5K9s',
            'X-CSRF-Token' => 'cd1LzdNPdlXUYQBFhK/z3T2NYhjhynnFDXYEBYHVHbSu5a+xL/Oj7i6OFWPRygcl+wD/+sJb1hEBqWz0fEy5IQ==',
            'Content-Length' => '2',
            'Origin' => 'https://www.codewars.com',
            'Connection' => 'keep-alive',
            'Cookie' => '_ga_M3JYSQLS8M=GS1.1.1660758179.118.1.1660760243.0.0.0; _ga=GA1.2.421845459.1650964118; _hjid=fccfca25-d2fe-4b44-b579-f6633751e51b; _hjSessionUser_1661672=eyJpZCI6ImY2OTYzZjRiLTQ3MGUtNWM3OS04YmJhLWFlMzAyMTI4ODg0YyIsImNyZWF0ZWQiOjE2NTA5NjQxMTg4NjAsImV4aXN0aW5nIjp0cnVlfQ==; _session_id=c6bb2e79110d813bd48df728225f436f; intercom-session-x27gw54w=Z1NnSTRzYUFtTDY2SFMrNVVXVGFzQktiODNONEFqMHFFdjExSzFlVDg0dTFmVS9aaVFmWTVta2ZBQjdsbFJqbC0tbzZjazU1b3hNN2tLWVpMQWZZNlZEQT09--697f25d4025c36151c8aec86fd761bbdbea5332d; _gid=GA1.2.225622894.1660721162; remember_user_token=eyJfcmFpbHMiOnsibWVzc2FnZSI6IkJBaGJDRnNHU1NJZE5qSmxZekptT0dZM01HWXpPVFUzT0RjM1l6TmhOMk14QmpvR1JWUkpJaGxMV21odk9FMTRSV2x3Vm0xUU5qRXhZbFJsYUFZN0FFWkpJaFl4TmpZd056SXhNalU1TGpnMk1URTBOZ1k3QUVZPSIsImV4cCI6IjIwMjMtMDgtMTdUMDc6Mjc6MzkuODYxWiIsInB1ciI6bnVsbH19--ac0a287dde26f48aa4c89fdb29c64163cb2b25a0; CSRF-TOKEN=cd1LzdNPdlXUYQBFhK%2Fz3T2NYhjhynnFDXYEBYHVHbSu5a%2BxL%2FOj7i6OFWPRygcl%2BwD%2F%2BsJb1hEBqWz0fEy5IQ%3D%3D',
            'Sec-Fetch-Dest' => 'empty',
            'Sec-Fetch-Mode' => 'cors',
            'Sec-Fetch-Site' => 'same-origin',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'no-cache',
            'TE' => 'trailers',
        ];

        return $headers;
    }

    public function getHeaders3_4()
    {
        $headers = [
            'Host' => 'www.codewars.com',
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:103.0) Gecko/20100101 Firefox/103.0',
            'Accept' => 'application/json, text/plain, */*',
            'Accept-Language' => 'ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3',
            'Accept-Encoding' => 'gzip, deflate, br',
            'Referer' => 'https://www.codewars.com/kata/57f781872e3d8ca2a000007e/train/elixir',
            'x-requested-with' => 'XMLHttpRequest',
            'Content-Type' => 'application/json',
            'authorization' => 'eyJhbGciOiJIUzI1NiJ9.eyJpZCI6IjYyZTc4Nzg4OTU1MzNhMDA2NTk5NGU2ZCIsImV4cCI6MTY2MTI4MDc2M30.nt0kxJhqzjdreo5cU-Qy85qViK6k-l6_n9ZN7W2bIvo',
            'X-CSRF-Token' => 'iWSwaLxcfWWbAYb9Lb9/WA/58i4P8DkQz/MO8FlM+lSZPKq2sab70nopnATUaFTKt9LAdUhenxBfg+BKesCQrA==',
            'Content-Length' => '2',
            'Origin' => 'https://www.codewars.com',
            'Connection' => 'keep-alive',
            'Cookie' => '_ga_M3JYSQLS8M=GS1.1.1660416722.110.1.1660416764.0; _ga=GA1.2.421845459.1650964118; _hjid=fccfca25-d2fe-4b44-b579-f6633751e51b; _hjSessionUser_1661672=eyJpZCI6ImY2OTYzZjRiLTQ3MGUtNWM3OS04YmJhLWFlMzAyMTI4ODg0YyIsImNyZWF0ZWQiOjE2NTA5NjQxMTg4NjAsImV4aXN0aW5nIjp0cnVlfQ==; _session_id=a38a1a3fec22fac3172e4168ee4e0df1; remember_user_token=eyJfcmFpbHMiOnsibWVzc2FnZSI6IkJBaGJDRnNHU1NJZE5qSmxOemczT0RnNU5UVXpNMkV3TURZMU9UazBaVFprQmpvR1JWUkpJaGxhY3pNMlUxVm9aVmRHT0hsNlZGVlRjM05DVlFZN0FFWkpJaGN4TmpZd01qQTNNREU0TGpNM09UQTFPVGdHT3dCRyIsImV4cCI6IjIwMjMtMDgtMTFUMDg6MzY6NTguMzc5WiIsInB1ciI6bnVsbH19--bcdaf118fd1cd9fad196d6e90f786e5bb01e411d; intercom-session-x27gw54w=dDdCR005S05HdHhsTnRYQ0dCMFYzQ29QbExCKys1TUJ0eGV1K0k0UDFmdHpONjJoKzBhL1p6TW1CUUZna0c3Ti0tN0VIYXMzRVVtb2dXZGEybGVmQlNOdz09--d201e80c38335ab51f21e4fdfd623644be002b47; CSRF-TOKEN=iWSwaLxcfWWbAYb9Lb9%2FWA%2F58i4P8DkQz%2FMO8FlM%2BlSZPKq2sab70nopnATUaFTKt9LAdUhenxBfg%2BBKesCQrA%3D%3D; _gid=GA1.2.757905455.1660416723',
            'Sec-Fetch-Dest' => 'empty',
            'Sec-Fetch-Mode' => 'cors',
            'Sec-Fetch-Site' => 'same-origin',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'no-cache',
            'TE' => 'trailers',
        ];

        return $headers;
    }

    public function getHeadersOrig()
    {
        $headers = [
            'Host' => 'www.codewars.com',
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:103.0) Gecko/20100101 Firefox/103.0',
            'Accept' => 'application/json, text/plain, */*',
            'Accept-Language' => 'ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3',
            'Accept-Encoding' => 'gzip, deflate, br',
            'Referer' => 'https://www.codewars.com/kata/55911ef14065454c75000062/train/c',
            'x-requested-with' => 'XMLHttpRequest',
            'Content-Type' => 'application/json',
            'authorization' => 'eyJhbGciOiJIUzI1NiJ9.eyJpZCI6IjYyZTc4Nzg4OTU1MzNhMDA2NTk5NGU2ZCIsImV4cCI6MTY2MDMwNjU3M30.hAeiEqorAUwsI07UYaSeDgzZ3uIzWHxDX6ne9-qOGaM',
            'X-CSRF-Token' => 'g2vJZ0m3PwOTs21nVslmoWv3+peDlbNjUNW3wnZ82auNTN8d7yjOCg9gB7B2McMjzLd7oQtb1mlzPT24JL5XKQ==',
            'Content-Length' => '2',
            'Origin' => 'https://www.codewars.com',
            'Connection' => 'keep-alive',
            'Cookie' => '_ga_M3JYSQLS8M=GS1.1.1659436016.103.1.1659442572.0; _ga=GA1.2.421845459.1650964118; _hjid=fccfca25-d2fe-4b44-b579-f6633751e51b; _hjSessionUser_1661672=eyJpZCI6ImY2OTYzZjRiLTQ3MGUtNWM3OS04YmJhLWFlMzAyMTI4ODg0YyIsImNyZWF0ZWQiOjE2NTA5NjQxMTg4NjAsImV4aXN0aW5nIjp0cnVlfQ==; _session_id=c576c30cb2e77eaa8f83eb1697335271; _gid=GA1.2.499641002.1659339650; intercom-session-x27gw54w=NE1ZbVZuUUFiQUhxU05ldk1FSDAyNUU0THc0SmZ0eVNmNmw4amJVU2ZIUlYybWZ4Y3U4aUdmTG9CZXROL2djRy0tZ3BTUC9Ta2l6eXQyaTc5QkVzREVCUT09--24bb5c72a313c8f0a782c4f6f7065cf1f570dd8f; remember_user_token=eyJfcmFpbHMiOnsibWVzc2FnZSI6IkJBaGJDRnNHU1NJZE5qSmxOemczT0RnNU5UVXpNMkV3TURZMU9UazBaVFprQmpvR1JWUkpJaGs0T1ZWNVVISXRkall6Ym1KeWFGZFpaV04xVEFZN0FGUkpJaGN4TmpVNU16VXhNekkwTGpBeE16VTFOeklHT3dCRyIsImV4cCI6IjIwMjMtMDgtMDFUMTA6NTU6MjQuMDEzWiIsInB1ciI6bnVsbH19--b3979c64364d24d6c9afc217c0d4a55dc1d41340; CSRF-TOKEN=g2vJZ0m3PwOTs21nVslmoWv3%2BpeDlbNjUNW3wnZ82auNTN8d7yjOCg9gB7B2McMjzLd7oQtb1mlzPT24JL5XKQ%3D%3D; _gat_gtag_UA_33566223_1=1',
            'Sec-Fetch-Dest' => 'empty',
            'Sec-Fetch-Mode' => 'cors',
            'Sec-Fetch-Site' => 'same-origin',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'no-cache',
            'TE' => 'trailers',
        ];

        return $headers;
    }

    public function getHeaders5kyu()
    {
        $headers = [
            'accept' => 'application/json, text/plain, */*',
            'accept-encoding' => 'gzip, deflate, br',
            'accept-language' => 'ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
            'authorization' => 'eyJhbGciOiJIUzI1NiJ9.eyJpZCI6IjYyYzJmZjFhZTA5NDNmMDA0OTVlMWI0MCIsImV4cCI6MTY1Nzg3MTg3Nn0.EB623sIxYeneW-ssO-_pGswBEmmKRXYuONk9214vaTE',
            'content-length' => '2',
            'content-type' => 'application/json',
            'cookie' => '_gid=GA1.2.1614785492.1656782735; _hjid=7ab56dfb-f73e-485c-b1b5-c314cde0b364; _hjSessionUser_1661672=eyJpZCI6IjM5ZThlZjQ4LTEzYWEtNTY3Yy04NTViLWY2ZmY3OTJjMTgzZCIsImNyZWF0ZWQiOjE2NTY3OTEzODcyMjksImV4aXN0aW5nIjp0cnVlfQ==; intercom-session-x27gw54w=NW1HQ09pWWUrc3lHdWl0MDdaNWJ4VjMyV3BPVDJabEdKeFFXUVJlQThRbmpXQ1BvMTNEY09VODRhN3ZTZmxyYS0taldEUm5zQkRpYlpYTUNobEZWdG53UT09--8879730a4a82fe81f64cee688f6293f45a036b28; _session_id=16a2557e2de3bbb95eda3db8d7aee9f4; _ga=GA1.2.2024936030.1612347945; CSRF-TOKEN=KSKSkK1jpZYl20Tf%2F%2B1n0P2Q9xqXWnTn9G74zUJK28mS92AvUZSs6pKEbOtQyaqcQPAHmEGp27fqfPveNKEeaQ%3D%3D; _ga_M3JYSQLS8M=GS1.1.1657007057.64.1.1657007878.0',
            'origin' => 'https://www.codewars.com',
            'referer' => 'https://www.codewars.com/kata/6204c419b5e27f001b91a207/solutions/bf',
            'sec-ch-ua' => '".Not/A)Brand";v="99", "Google Chrome";v="103", "Chromium";v="103"',
            'sec-ch-ua-mobile' => '?0',
            'sec-ch-ua-platform' => '"Windows"',
            'sec-fetch-dest' => 'empty',
            'sec-fetch-mode' => 'cors',
            'sec-fetch-site' => 'same-origin',
            'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.0.0 Safari/537.36',
            'x-csrf-token' => 'KSKSkK1jpZYl20Tf/+1n0P2Q9xqXWnTn9G74zUJK28mS92AvUZSs6pKEbOtQyaqcQPAHmEGp27fqfPveNKEeaQ==',
            'x-requested-with' => 'XMLHttpRequest',
        ];

        return $headers;
    }

}
