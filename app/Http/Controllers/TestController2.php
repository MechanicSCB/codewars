<?php

namespace App\Http\Controllers;


use App\Classes\ReverseSeeder;
use App\Models\Lang;
use App\Models\Solution;
use App\Classes\Trash\MyKataSolver;
use Faker\Factory;
use Illuminate\Support\Str;


class TestController2 extends Controller
{
    public function test()
    {

$shapes = [
["+------------+\n|            |\n|            |\n|            |\n+------+-----+\n|      |     |\n|      |     |\n+------+-----+"],
["+-------------------+--+\n|                   |  |\n|                   |  |\n|  +----------------+  |\n|  |                   |\n|  |                   |\n+--+-------------------+"],
["           +-+             \n           | |             \n         +-+-+-+           \n         |     |           \n      +--+-----+--+        \n      |           |        \n   +--+-----------+--+     \n   |                 |     \n   +-----------------+     "],
["+-----------------+\n|                 |\n|   +-------------+\n|   |\n|   |\n|   |\n|   +-------------+\n|                 |\n|                 |\n+-----------------+"],
["+---+---+---+---+---+---+---+---+\n|   |   |   |   |   |   |   |   |\n+---+---+---+---+---+---+---+---+"],
["+---+------------+---+\n|   |            |   |\n+---+------------+---+\n|   |            |   |\n|   |            |   |\n|   |            |   |\n|   |            |   |\n+---+------------+---+\n|   |            |   |\n+---+------------+---+"],
["                 \n   +-----+       \n   |     |       \n   |     |       \n   +-----+-----+ \n         |     | \n         |     | \n         +-----+ "],
];

        $arr = [
            [["+------------+",
              "|            |",
              "|            |",
              "|            |",
              "+------+-----+",
              "|      |     |",
              "|      |     |",
              "+------+-----+"]],
            [["+-------------------+--+",
              "|                   |  |",
              "|                   |  |",
              "|  +----------------+  |",
              "|  |                   |",
              "|  |                   |",
              "+--+-------------------+"]],
            [["           +-+             ",
              "           | |             ",
              "         +-+-+-+           ",
              "         |     |           ",
              "      +--+-----+--+        ",
              "      |           |        ",
              "   +--+-----------+--+     ",
              "   |                 |     ",
              "   +-----------------+     "]],
            [["+-----------------+",
              "|                 |",
              "|   +-------------+",
              "|   |",
              "|   |",
              "|   |",
              "|   +-------------+",
              "|                 |",
              "|                 |",
              "+-----------------+"]],
            [["+---+---+---+---+---+---+---+---+",
              "|   |   |   |   |   |   |   |   |",
              "+---+---+---+---+---+---+---+---+"]],
            [["+---+------------+---+",
              "|   |            |   |",
              "+---+------------+---+",
              "|   |            |   |",
              "|   |            |   |",
              "|   |            |   |",
              "|   |            |   |",
              "+---+------------+---+",
              "|   |            |   |",
              "+---+------------+---+"]],
            [["                 ",
              "   +-----+       ",
              "   |     |       ",
              "   |     |       ",
              "   +-----+-----+ ",
              "         |     | ",
              "         |     | ",
              "         +-----+ "]],
        ];


//[["+------------+\n|            |\n|            |\n|            |\n+------------+","+------+\n|      |\n|      |\n+------+","+-----+\n|     |\n|     |\n+-----+"],["                 +--+\n                 |  |\n                 |  |\n+----------------+  |\n|                   |\n|                   |\n+-------------------+","+-------------------+\n|                   |\n|                   |\n|  +----------------+\n|  |\n|  |\n+--+"],["+-+\n| |\n+-+","+-----+\n|     |\n+-----+","+-----------+\n|           |\n+-----------+","+-----------------+\n|                 |\n+-----------------+"],["+-----------------+\n|                 |\n|   +-------------+\n|   |\n|   |\n|   |\n|   +-------------+\n|                 |\n|                 |\n+-----------------+"],["+---+\n|   |\n+---+","+---+\n|   |\n+---+","+---+\n|   |\n+---+","+---+\n|   |\n+---+","+---+\n|   |\n+---+","+---+\n|   |\n+---+","+---+\n|   |\n+---+","+---+\n|   |\n+---+"],["+---+\n|   |\n+---+","+---+\n|   |\n+---+","+---+\n|   |\n+---+","+---+\n|   |\n+---+","+------------+\n|            |\n+------------+","+------------+\n|            |\n+------------+","+---+\n|   |\n|   |\n|   |\n|   |\n+---+","+---+\n|   |\n|   |\n|   |\n|   |\n+---+","+------------+\n|            |\n|            |\n|            |\n|            |\n+------------+"],["+-----+\n|     |\n|     |\n+-----+","+-----+\n|     |\n|     |\n+-----+"]]]
// [[["+------------+", "|            |", "|            |", "|            |", "+------------+","+------+", "|      |", "|      |", "+------+","+-----+", "|     |", "|     |", "+-----+"],["                 +--+", "                 |  |", "                 |  |", "+----------------+  |", "|                   |", "|                   |", "+-------------------+","+-------------------+", "|                   |", "|                   |", "|  +----------------+", "|  |", "|  |", "+--+"],["+-+", "| |", "+-+","+-----+", "|     |", "+-----+","+-----------+", "|           |", "+-----------+","+-----------------+", "|                 |", "+-----------------+"],["+-----------------+", "|                 |", "|   +-------------+", "|   |", "|   |", "|   |", "|   +-------------+", "|                 |", "|                 |", "+-----------------+"],["+---+", "|   |", "+---+","+---+", "|   |", "+---+","+---+", "|   |", "+---+","+---+", "|   |", "+---+","+---+", "|   |", "+---+","+---+", "|   |", "+---+","+---+", "|   |", "+---+","+---+", "|   |", "+---+"],["+---+", "|   |", "+---+","+---+", "|   |", "+---+","+---+", "|   |", "+---+","+---+", "|   |", "+---+","+------------+", "|            |", "+------------+","+------------+", "|            |", "+------------+","+---+", "|   |", "|   |", "|   |", "|   |", "+---+","+---+", "|   |", "|   |", "|   |", "|   |", "+---+","+------------+", "|            |", "|            |", "|            |", "|            |", "+------------+"],["+-----+", "|     |", "|     |", "+-----+","+-----+", "|     |", "|     |", "+-----+"]]]


            $pieces = [
                [
                    ["+------------+",
                     "|            |",
                     "|            |",
                     "|            |",
                     "+------------+"],
                    ["+------+",
                     "|      |",
                     "|      |",
                     "+------+"],
                    ["+-----+",
                     "|     |",
                     "|     |",
                     "+-----+"],
                ],
                [
                    ["                 +--+",
                     "                 |  |",
                     "                 |  |",
                     "+----------------+  |",
                     "|                   |",
                     "|                   |",
                     "+-------------------+"],
                    ["+-------------------+",
                     "|                   |",
                     "|                   |",
                     "|  +----------------+",
                     "|  |",
                     "|  |",
                     "+--+"],
                ],
                [
                    ["+-+",
                     "| |",
                     "+-+"],
                    ["+-----+",
                     "|     |",
                     "+-----+"],
                    ["+-----------+",
                     "|           |",
                     "+-----------+"],
                    ["+-----------------+",
                     "|                 |",
                     "+-----------------+"],
                ],
                [
                    ["+-----------------+",
                     "|                 |",
                     "|   +-------------+",
                     "|   |",
                     "|   |",
                     "|   |",
                     "|   +-------------+",
                     "|                 |",
                     "|                 |",
                     "+-----------------+"],
                ],
                [
                    ["+---+",
                     "|   |",
                     "+---+"],
                    ["+---+",
                     "|   |",
                     "+---+"],
                    ["+---+",
                     "|   |",
                     "+---+"],
                    ["+---+",
                     "|   |",
                     "+---+"],
                    ["+---+",
                     "|   |",
                     "+---+"],
                    ["+---+",
                     "|   |",
                     "+---+"],
                    ["+---+",
                     "|   |",
                     "+---+"],
                    ["+---+",
                     "|   |",
                     "+---+"],
                ],
                [
                    ["+---+",
                     "|   |",
                     "+---+"],
                    ["+---+",
                     "|   |",
                     "+---+"],
                    ["+---+",
                     "|   |",
                     "+---+"],
                    ["+---+",
                     "|   |",
                     "+---+"],
                    ["+------------+",
                     "|            |",
                     "+------------+"],
                    ["+------------+",
                     "|            |",
                     "+------------+"],
                    ["+---+",
                     "|   |",
                     "|   |",
                     "|   |",
                     "|   |",
                     "+---+"],
                    ["+---+",
                     "|   |",
                     "|   |",
                     "|   |",
                     "|   |",
                     "+---+"],
                    ["+------------+",
                     "|            |",
                     "|            |",
                     "|            |",
                     "|            |",
                     "+------------+"],
                ],
                [
                    ["+-----+",
                     "|     |",
                     "|     |",
                     "+-----+"],

                    ["+-----+",
                     "|     |",
                     "|     |",
                     "+-----+"],
                ],
            ];
    }

}

$shape = "+------------+\n|            |\n|            |\n|            |\n+------++----+\n|      ||    |\n|      ||    |\n+------++----+";

$expected = ["+------------+\n|            |\n|            |\n|            |\n+------------+","+------+\n|      |\n|      |\n+------+","+----+\n|    |\n|    |\n+----+","++\n||\n||\n++"];


