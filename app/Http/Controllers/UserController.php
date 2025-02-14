<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $data = User::create([
            "name" => $request->name,
            "username" => $request->username,
            "password" => Hash::make($request->password)
        ]);
        $token = $data->createToken('auth-sanctum')->plainTextToken;
        return response()->json(["message" => "User created", "returnType" => "object", "result" => $data, "token" => $token], 201);
    }

    public function index()
    {
        $users = User::paginate();
        return response()->json([
            "returnType" => "collection",
            "paginate" => true,
            "result" => UserResource::collection($users)
        ], 200);
    }

    public function update(Request $request, $userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(["message" => "Foydalanuvchi topilmadi"], 404);
        }
        $user->update([
            'name' => $request->name
        ]);
        return response()->json(["returnType" => "string", "paginate" => false, "message" => "Updated"], 200);
    }

    public function destroy($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(["message" => "Foydalanuvchi topilmadi"], 404);
        }
        $user->delete();
        return response()->json(["returnType" => "string", "paginate" => false, "message" => "Delete"], 202);
    }

    public function practice(string $name):  string
    {
        return "Assalamu alaykum $name";
    }
    public function test(){
	    return 'sasdfsdfsadfasdfalofdddsfasa';
    
    }

}
