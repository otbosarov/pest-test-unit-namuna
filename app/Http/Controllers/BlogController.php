<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    public function calculate(int $first, int $second) : int
    {
        $result = $first + $second;
        return $result;
    }

    public function store(Request $request)
    {
        $data = Blog::create([
            "title" => $request->title,
            "user_id" => Auth::user()->id
        ]);
        return response()->json(["returnType" => "object", "message" => "success", "result" => $data], 201);
    }

    public function update(Request $request, $blogId)
    {
        $blog = Blog::where('id', $blogId)
            ->where('user_id', Auth::user()->id);
        if (!$blog) {
            return response()->json(["message" => "Ma'lumot topilmadi"], 404);
        }
        $blog->update([
            "title" => $request->title
        ]);
        return response()->json(["returnType" => "string", "message" => "updated"], 200);
    }

    public function index()
    {
        $blogs = Blog::get();
        return response()->json([
            "returnType" => "collection",
            "paginate" => true,
            "result" => $blogs
        ], 200);
    }
}
