<?php

namespace App\Http\Controllers;

use App\Services\PostService;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // return Post::all();
        // $posts = Post::with('user')->get();
        $query = $this->postService->searchAndFilter($request);
        // $query = $this->postService->searchAndFilter($request)->where('user_id', Auth::id()); //  تصفية المنشورات حسب المستخدم المصادق عليه فقط
        // dump($query->toSql()); 
        $perPage = $request->input('per_page', 10);
        $posts = $query->paginate($perPage);
        return response()->json([
            'message' => __('messages.post_geted_successfully'),
            'data' => collect($posts->items())->map(function ($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'body' => $post->body,
                    'created_at' => $post->created_at,
                    'updated_at' => $post->updated_at,
                    'user' => $post->user ? [
                        'id' => $post->user->id,
                        'name' => $post->user->name,
                        'email' => $post->user->email,
                        'email_verified_at' => $post->user->email_verified_at,
                        'created_at' => $post->user->created_at,
                        'updated_at' => $post->user->updated_at,
                    ] : null
                ];
            }),
            'pagination' => [
                'total' => $posts->total(),  // إجمالي عدد العناصر
                'count' => count($posts->items()),  // عدد العناصر في الصفحة الحالية
                'per_page' => $posts->perPage(), //عدد العناصر لكل صفحة.
                'current_page' => $posts->currentPage(), //رقم الصفحة الحالية.
                'total_pages' => $posts->lastPage(),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $post = Post::create([
            'title' => $request->title,
            'body' => $request->body,
            'user_id' => Auth::id(),
        ]);
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
        $userId = Auth::id(); // الحصول على ID المستخدم المصادق عليه

        // البحث عن المنشور الذي ينتمي إلى المستخدم
        $post = Post::where('id', $id)->where('user_id', $userId)->first();

        if (!$post) {
            return response()->json(['message' => 'Unauthorized or Post Not Found'], 403);
        }
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
        if ($id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);
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
        $userId = Auth::id();

        // البحث عن المنشور الذي ينتمي للمستخدم الحالي
        $post = Post::where('id', $id)->where('user_id', $userId)->first();

        if (!$post) {
            return response()->json(['message' => 'Post Not Found or Unauthorized'], 404);
        }

        // حذف المنشور
        $post->delete();

        return response()->json([
            'message' => __('messages.post_deleted_successfully')
        ], 204);
    }
}
