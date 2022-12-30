<?php

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $cmd = 'cd tmp && ';
    $cmd .= 'ts-node script.ts 2>&1';
    $out = shell_exec($cmd);
    //$out = 'runner index';
    echo $out;
});

Route::post('/', [MainController::class, 'index'])->name('index');
