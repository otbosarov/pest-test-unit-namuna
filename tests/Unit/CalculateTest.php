<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\UserController;

test("check calculate", function (){

    $first = 2;
    $second = 4;

    $calculate = app(BlogController::class)->calculate($first, $second);

    expect($calculate)->toBe(6);
    expect($calculate)->toBeInt();
});

test("welcome", function(){

    $name = "alijon";
    $response = app(UserController::class)->practice($name);

    expect($response)->toBe("Assalamu alaykum $name");
});
