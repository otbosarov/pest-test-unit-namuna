<?php

use Illuminate\Support\Facades\Schema;

test("check users table", function () {

    $table = Schema::hasTable('users');
    $username = Schema::hasColumn('users', 'username');
    $password = Schema::hasColumn('users', 'password');
    $name = Schema::hasColumn('users', 'name');

    expect($table)->toBeTrue();
    expect($username)->toBeTrue();
    expect($password)->toBeTrue();
    expect($name)->toBeTrue();

});

test("check blogs table", function(){

    $table = Schema::hasTable('blogs');
    $userId = Schema::hasColumn('blogs', 'user_id');
    $title = Schema::hasColumn('blogs', 'title');

    expect($table)->toBeTrue();
    expect($userId)->toBeTrue();
    expect($title)->toBeTrue();

});
