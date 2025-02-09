<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\App;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return Post::all();
        $posts = Post::with('user')->get();

        return response()->json([
            'message' => __('messages.post_geted_successfully'),
            'data' => $posts->map(function ($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'body' => $post->body,
                    // 'user_id' => $post->user_id,
                    'created_at' => $post->created_at,
                    'updated_at' => $post->updated_at,
                    'user' => [
                        'id' => $post->user->id,
                        'name' => $post->user->name,
                        'email' => $post->user->email,
                        'email_verified_at' => $post->user->email_verified_at,
                        'created_at' => $post->user->created_at,
                        'updated_at' => $post->user->updated_at,
                    ]
                ];
            })
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $post = Post::create($request->all());
        return response()->json([
            'message' => __('messages.post_created_successfully'),
            'data' => [
                'id' => $post->id,
                'title' => $post->title,
                'body' =>  $post->body,
            ]
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $post = Post::findOrFail($id);
        return response()->json([
            'message' => __('messages.post_found_successfully'),
            'data' => [
                'id' => $post->id,
                'title' => $post->title,
                'body' => $post->body,
            ]
            // لا تستخدم مع الترجمة
            // 'data' =>$post
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $post = Post::findOrFail($id);
        $post->update($request->all());

        return response()->json([
            'message' => __('messages.post_updated_successfully'),
            'data' => [
                'id' => $post->id,
                'title' => $post->title,
                'body' => $post->body,
            ]
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Post::destroy($id);

        return response()->json([
            'message' => __('messages.post_deleted_successfully')
        ], 204);
    }
}
