<?php

use App\Models\Blog;
use App\Models\User;
use Illuminate\Support\Facades\Log;

test('check create blog', function () {

    $user = User::factory()->create();

    $response = $this->withHeaders([
        "Authorization" => "Bearer " . bearerToken($user)
    ])
        ->postJson("api/blogs", [
            "title" => "test"
        ]);

    expect($response->status())->toBe(201);
    expect($response->json("returnType"))->toBe("object");
    expect($response->json("message"))->toBe("success");
    expect($response->json("result.title"))->toBe("test");
    expect($response->json("result.user_id"))->toBe($user->id);
});


test('check update blog', function () {
    $user = User::factory()->create();

    $blog = Blog::create([
        'title' => 'test',
        'user_id' => $user->id
    ]);

    $response = $this->withHeaders([
        "Authorization" => "Bearer " . bearerToken($user)
    ])->putJson("api/blogs/{$blog->id}", [
        "title" => "new test"
    ]);
    expect($response->status())->toBe(200);
    expect($response->json("returnType"))->toBe("string");
    expect($response->json("message"))->toBe("updated");
});

// test('check get blogs', function () {

//     User::factory()->count(1)->create();
//     $user = User::first();
    // $blogs = Blog::factory()->count(20)->create([
    //     'user_id' => $user->id
    // ]);

//     $response = $this->withHeaders([
//         "Authorization" => "Bearer " . bearerToken($user)
//     ])
//     ->getJson("api/blogs");
// });
