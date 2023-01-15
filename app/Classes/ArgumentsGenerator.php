<?php


namespace App\Classes;


use App\Classes\Train\SolutionChecker;
use App\Models\Kata;
use App\Models\RandomTest;
use Faker\Factory;
use Faker\Generator;
use Faker\Provider\Lorem;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ArgumentsGenerator
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function generate(array $scheme, $count, RandomTest $randomTest): array
    {
        $count ??= 10;
        $testsRandomArgs = [];

        if ($randomTest->is_function && $randomTest->code !== null) {
            $testsRandomArgs = $this->generateFromFunction($scheme[0]['lang'] ?? 'php', $randomTest['code'], $count);
        } else {
            while ($count--) {
                $testsRandomArgs[] = $this->generateArguments($scheme);
            }
        }

        return $testsRandomArgs;
    }

    public function generateArguments(array $scheme): array
    {
        $randomArgs = [];

        foreach ($scheme as $schemeElement) {
            $randomArgs[] = $this->generateArgument($schemeElement);
        }

        return $randomArgs;
    }

    private function generateArgument(array $element)
    {
        // FUNCTION
        if ($element['type'] === 'func') {
            return $this->generateFromFunction(@$element['lang'], @$element['code'])[0];
        }

        // NUMBER
        if ($element['type'] === 'num') {
            return $this->generateRandomNumber(@$element['value'], @$element['precision'], @$element['divide'], @$element['parity'], @$element['allowed'], @$element['restricted']);
        }

        // STRING
        if ($element['type'] === 'str') {
            return $this->generateRandomString(@$element['length'], @$element['allowed'], @$element['restricted'], @$element['faker'], @$element['strcase'], @$element['fcase']);
        }

        // WORD
        if ($element['type'] === 'word' || $element['type'] === 'words') {
            return $this->generateRandomString(@$element['length'], null, null, 'word', @$element['strcase'], @$element['fcase']);
        }

        // SENTENCE
        if ($element['type'] === 'sent') {
            return $this->generateRandomString(@$element['length'], null, null, 'sent', @$element['strcase'], @$element['fcase']);
        }

        // ARRAY
        if ($element['type'] === 'arr') {
            return $this->generateRandomArray(@$element['length'], @$element['element'], @$element['array_options']);
        }

        // BOOLEAN
        if ($element['type'] === 'bool') {
            return (bool)rand(0, 1);
        }

        return null;
    }

    private function generateRandomString(int|array|null $length, array|string|null $allowed, ?array $restricted, string|array|null $faker, ?string $strcase, ?string $fcase): string
    {
        $str = '';

        $length ??= rand(1, 12);

        $length = is_array($length) ? rand($length[0], $length[1]) : $length;

        if ($length === 0) {
            return '';
        }

        // TODO: restricted not done yet
        if ($allowed) {
            $allowed = is_string($allowed) ? str_split($allowed) : $allowed;
            for ($i = 0; $i < $length; $i++) {
                $str .= Arr::random($allowed);
            }
        } elseif ($faker) {
            if (is_array($faker)) {
                $fakerArgs = implode(',', $faker['args']);
                $str = $this->faker->{$faker['name']}($fakerArgs);
            } elseif (str_starts_with(strtolower($faker), 'sent')) {
                $str = Lorem::sentence($length, false);
            } elseif (strtolower($faker) === 'words') {
                $str = Lorem::words($length, true);
            } else {
                $faker = strtolower($faker);
                $str = $this->faker->$faker();
            }
        } else {
            $str = Str::random($length);
        }

        if ($strcase) {
            if (str_starts_with(strtolower($strcase), 'l')) {
                $str = strtolower($str);
            } elseif (str_starts_with(strtolower($strcase), 'u')) {
                $str = strtoupper($str);
            }
        }

        if ($fcase) {
            if (str_starts_with(strtolower($fcase), 'l')) {
                $str = lcfirst($str);
            } elseif (str_starts_with(strtolower($fcase), 'u')) {
                $str = ucfirst($str);
            }
        }

        return $str;
    }

    public function generateRandomNumber(int|array|null $value, int|array|null $precision = 0, int|array|null $divide = 1, string|null $parity = null, array $allowed = null, array $restricted = null): int|float
    {
        if (is_array($allowed)) {
            return Arr::random($allowed);
        }

        if (! is_array($value)) {
            return $value ?? rand(0, 9);
        }

        if (is_array($precision)) {
            $precision = rand($precision[0], $precision[1]);
        }

        $num = rand($value[0] * pow(10, $precision), $value[1] * pow(10, $precision)) / pow(10, $precision);
        $divide ??= 1;
        $num /= $divide;

        if ($parity === 'even') {
            $num = $num * pow(10, $precision) % 2 ? $num + 1 / pow(10, $precision) : $num;
        } elseif ($parity === 'odd') {
            $num = $num * pow(10, $precision) % 2 ? $num : $num + 1 / pow(10, $precision);
        }

        if (in_array($num, $restricted ?? [])) {
            $num = $this->generateRandomNumber($value,$precision,$divide,$parity,$allowed,$restricted);
        }

        return $num;
    }

    private function generateRandomArray(int|array|null $length, array $element, ?array $arrayOptions): array
    {
        $arr = [];

        $length ??= rand(1, 12);

        $length = is_array($length) ? rand($length[0], $length[1]) : $length;

        for ($i = 0; $i < $length; $i++) {
            $arr[] = $this->generateArgument($element);
        }

        if (in_array('sort', $arrayOptions ?? [])) {
            sort($arr);
        } elseif (in_array('rsort', $arrayOptions ?? [])) {
            rsort($arr);
        }

        if (in_array('unique', $arrayOptions ?? [])) {
            $arr = array_values(array_unique($arr));
        }

        //df(tmr(@$this->start), $arr);
        return $arr;
    }

    private function generateFromFunction(string $lang, string $code, int $count = 1): mixed
    {
        $attempts = array_pad([], $count, ['name' => 'getRandomArguments', 'args' => []]);
        $args = (new SolutionChecker(new Kata(), $attempts, $code, $lang))->getSolutionEvalList();

        return $args;
    }

}
