<?php
namespace App\Services;

use App\Models\Post;
use Illuminate\Http\Request;

class PostService
{
    public function searchAndFilter(Request $request)
    {
        $query = Post::query();
        $language = $request->header('Accept-Language', 'en');

        if ($request->has('search')) {
            $search = $request->input('search');
    
            // بناء الاستعلام بناءً على اللغة
            if ($language === 'ar') {
                $query->where('title->ar', 'like', "%{$search}%");
            } else {
                $query->where('title->en', 'like', "%{$search}%");
            }
        }

        if ($request->has('user_id')) {
            $userId = $request->input('user_id');
            $query->where('user_id', $userId);
        }

        if ($request->has('created_at')) {
            $createdAt = $request->input('created_at');
            $query->whereDate('created_at', $createdAt);
        }

        if ($request->has('sort')) {
            $sort = $request->input('sort');
            $query->orderBy('created_at', $sort);
        }

        return $query;
    }
}