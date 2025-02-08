<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return Post::all();
        return Post::with('user')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $post = Post::create($request->all());

        return response()->json([
            'message' => __('messages.post_created_successfully'),
            'post' => $post
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::findOrFail($id);
        return response()->json([
            'message' => __('messages.post_found_successfully'),
            'post' => $post
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
            'post' => $post
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
