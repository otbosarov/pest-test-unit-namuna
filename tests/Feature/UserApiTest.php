<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Testing\Fluent\AssertableJson;

function bearerToken($user): string
{
    $token = $user->createToken('auth-sanctum')->plainTextToken;
    return $token;
}

function responseData($returnType, $message, $paginate, $result): array
{
    $data = [
        "returnType" => $returnType,
        "message" => $message,
        "paginate" => $paginate,
        "result" => $result,
    ];
    return $data;
}


function responseDataForResult($returnType, $paginate, $result): array
{
    $data = [
        "returnType" => $returnType,
        "paginate" => $paginate,
        "result" => $result
    ];
    return $data;
}

function responseDataForMessage($returnType, $message, $paginate): array
{
    $data = [
        "returnType" => $returnType,
        "message" => $message,
        "paginate" => $paginate,
    ];
    return $data;
}



test('check user register', function () {

    $data = [
        "name" => "ali",
        "username" => "ali",
        "password" => "ali"
    ];

    // urlga so'rov yuborish
    $response = $this->postJson("api/register", $data);

    // responsening statusini tekshirish
    expect($response->status())->toBe(201);

    // responsening messageni tekshirish
    expect($response->json("message"))->toBe("User created");

    // responsening datasining nameni tekshirish
    expect($response->json("result.name"))->toBe($data["name"]);

    // responsening datasining usernameni tekshirish
    expect($response->json("result.username"))->toBe($data["username"]);

    // $checkData = unset

    // $responseData = responseData("object", "User created", false, [
    //     "name" => "ali",
    //     "username" => "ali"
    // ]);
    // Log::info($responseData);

    // expect($response->json())->toMatchArray($responseData);

    // yaratilgan userning parolini tekshirish
    $user = User::where([
        "username" => $data["username"],
        "name" => $data["name"]
    ])->first();
    expect(Hash::check("ali", $user->password))->toBeTrue();
});


test('check get users', function () {
    // fake userlar yaratish
    User::factory()->count(20)->create();

    // Userning birinchisini olish
    $user = User::first();

    // birinchi userga berilgan token orqali urlga sorov yuborish
    $response = $this->withHeaders([
        'Authorization' => "Bearer " . bearerToken($user),
    ])->getJson('/api/users');

    expect($response->status())->toBe(200);

    expect($response->json("returnType"))->toBe("collection");
    expect($response->json("paginate"))->toBe(true);
    expect($response->json("result"))->toHaveCount(15);

    $response->assertJson(
        function (AssertableJson $json) {
            $json->whereType('result', 'array')
                ->has('result', 15, function ($json) {
                    $json->whereType('name', 'string')
                        ->whereType('id', 'integer')
                        ->whereType('username', 'string')
                        ->missing('password')
                        ->etc();
                })
                ->etc();    // Boshqa maydonlarni e'tiborsiz qoldirish
        }
    )
        ->assertJsonStructure([
            'result' => [
                '*' => [
                    'id',
                    'name',
                    'username'
                ]
            ]
        ]);
});


test('check update user', function () {

    // ismi Jasur bo'lgan yangi fake user yaratish
    $user = User::factory()->create(['name' => 'Jasur']);

    $data = [
        'name' => 'new name'
    ];

    $response = $this->withHeaders([
        'Authorization' => "Bearer " . bearerToken($user),
    ])->putJson("/api/users/{$user->id}", $data);

    expect($response->status())->toBe(200);

    $responseData = responseDataForMessage("string", "Updated", false);

    expect($response->json())->toMatchArray($responseData);

    expect(User::find($user->id)->name)->toBe('new name');
});


test('check delete user', function () {

    $user = User::factory()->create();

    $response = $this->withHeaders([
        'Authorization' => "Bearer " . bearerToken($user),
    ])->deleteJson("/api/users/{$user->id}");

    expect($response->status())->toBe(202);

    $responseData = responseDataForMessage("string", "Delete", false);

    expect($response->json())->toMatchArray($responseData);
    expect(User::where('id', $user->id)->first())->toBeEmpty();
});
