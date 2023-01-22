<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\VarDumper\VarDumper;

function stdToArray($input): array
{
    return json_decode(json_encode($input), 1) ?? [];
}

function tmr(?float $time = null, $precision = 2): string
{
    $time ??= $_SERVER['REQUEST_TIME_FLOAT'];

    return 'time = ' . number_format(microtime(true) - $time, $precision) . ' sec.';
}

if (! function_exists('clearDbTable')) {
    function clearDbTable(string $tableName)
    {
        switch(DB::getDriverName()) {
            case 'mysql':
                DB::statement('SET FOREIGN_KEY_CHECKS=0');
                break;
            case 'sqlite':
                DB::statement('PRAGMA foreign_keys = OFF');
                break;
        }

        DB::table($tableName)->truncate();

        //Schema::disableForeignKeyConstraints();
        //DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        //Schema::enableForeignKeyConstraints();
    }

}

if (! function_exists('checkFunctionName')) {
    function checkFunctionName(string $name):bool
    {
        return preg_match("/^[A-z][A-z\d_-]+$/", $name);
    }
}

function flatten(array $array, string $parentKey = null)
{
    $return = [];

    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $key = $parentKey ? "$parentKey|$key" : $key;
            $return += flatten($value, $key);
        } else {
            $return += ["$parentKey|$key" => $value];
        }
    }

    return $return;
}

if (! function_exists('df')) {
    function df(...$vars)
    {
        $file = str_replace(base_path(), '', debug_backtrace()[0]['file']);
        $line = debug_backtrace()[0]['line'];

        VarDumper::dump("$file:$line");

        foreach ($vars as $v) {
            VarDumper::dump($v);
        }

        exit(1);
    }
}

if (! function_exists('array_group_by')) {
    /**
     * Groups an array by a given key.
     *
     * Groups an array into arrays by a given key, or set of keys, shared between all array members.
     *
     * Based on {@author Jake Zatecky}'s {@link https://github.com/jakezatecky/array_group_by array_group_by()} function.
     * This variant allows $key to be closures.
     *
     * @param array $array The array to have grouping performed on.
     * @param mixed $key,... The key to group or split by. Can be a _string_,
     *                       an _integer_, a _float_, or a _callable_.
     *
     *                       If the key is a callback, it must return
     *                       a valid key from the array.
     *
     *                       If the key is _NULL_, the iterated element is skipped.
     *
     *                       ```
     *                       string|int callback ( mixed $item )
     *                       ```
     *
     * @return array|null Returns a multidimensional array or `null` if `$key` is invalid.
     */
    function array_group_by(array $array, $key)
    {
        if (! is_string($key) && ! is_int($key) && ! is_float($key) && ! is_callable($key)) {
            trigger_error('array_group_by(): The key should be a string, an integer, or a callback', E_USER_ERROR);

            return null;
        }

        $func = (! is_string($key) && is_callable($key) ? $key : null);
        $_key = $key;

        // Load the new array, splitting by the target key
        $grouped = [];
        foreach ($array as $value) {
            $key = null;

            if (is_callable($func)) {
                $key = call_user_func($func, $value);
            } elseif (is_object($value) && property_exists($value, $_key)) {
                $key = $value->{$_key};
            } elseif (isset($value[$_key])) {
                $key = $value[$_key];
            }

            if ($key === null) {
                continue;
            }

            $grouped[$key][] = $value;
        }

        // Recursively build a nested grouping if more parameters are supplied
        // Each grouped array value is grouped according to the next sequential key
        if (func_num_args() > 2) {
            $args = func_get_args();

            foreach ($grouped as $key => $value) {
                $params = array_merge([$value], array_slice($args, 2, func_num_args()));
                $grouped[$key] = call_user_func_array('array_group_by', $params);
            }
        }

        return $grouped;
    }
}


function getNonExistedInDbIds(array $values, string $table, string $column = 'id'): array
{
    if (! $values) {
        return [];
    }

    if (! Schema::hasColumn($table, $column)) {
        //Log::channel('custom')
        //    ->alert('querying non existed table_column',
        //        ['table' => $table, 'column' => $column, 'values' => $values]);

        return [];
    }

    $unionValues = getUnionStringFromArray($values); // "SELECT 1 AS val UNION SELECT 2 UNION SELECT 3 UNION SELECT 16"
    $nonExistedInDbTeamsIds = DB::select("SELECT val FROM
                                    (SELECT $unionValues) AS values_table
                                    LEFT JOIN $table
                                    ON $table.$column = val
                                    WHERE $table.$column IS NULL");

    $nonExistedInDbTeamsIds = stdToArray($nonExistedInDbTeamsIds);
    $nonExistedInDbTeamsIds = Arr::flatten($nonExistedInDbTeamsIds);
    $nonExistedInDbTeamsIds = array_unique($nonExistedInDbTeamsIds);

    return $nonExistedInDbTeamsIds;
}

function getUnionStringFromArray(array $values): string
{
    $firstItem = array_shift($values);
    $unionString = "'$firstItem' AS val"; //$union = "SELECT 1 AS i UNION SELECT 2 UNION SELECT 3 UNION SELECT 16 UNION SELECT 7";

    foreach ($values as $nextValue) {
        $unionString .= " UNION SELECT '$nextValue'";
    }

    return $unionString;
}

