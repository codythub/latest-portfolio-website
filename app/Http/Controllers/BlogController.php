<?php

namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Http\Request;

use App\Models\Post;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $selectedCategory = $request->query('category');

        // Visible blog categories used for public filters.
        $categories = Category::query()
            ->where('type', Category::TYPE_BLOG)
            ->where('is_visible', true)
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        // Published posts with their assigned category.
        $posts = Post::query()
            ->with('category')
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->when($selectedCategory, function ($query) use ($selectedCategory) {
                $query->whereHas('category', function ($categoryQuery) use ($selectedCategory) {
                    $categoryQuery
                        ->where('type', Category::TYPE_BLOG)
                        ->where('is_visible', true)
                        ->where('slug', $selectedCategory);
                });
            })
            ->orderBy('display_order')
            ->orderByDesc('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('blog.index', compact(
            'posts',
            'categories',
            'selectedCategory'
        ));
    }

    public function show(Post $post)
    {
        $post->load(['category', 'author']);

        abort_unless(
            $post->is_published &&
            $post->published_at &&
            $post->category?->is_visible,
            404
        );

        $relatedPosts = Post::query()
            ->with('category')
            ->where('id', '!=', $post->id)
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->whereHas('category', function ($query) {
                $query
                    ->where('type', Category::TYPE_BLOG)
                    ->where('is_visible', true);
            })
            ->when(
                $post->category_id,
                fn ($query) => $query->orderByRaw(
                    'CASE WHEN category_id = ? THEN 0 ELSE 1 END',
                    [$post->category_id]
                )
            )
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        $hasLiked = request()->session()->has(
            'liked_posts.' . $post->id
        );

        return view('blog.show', compact(
            'post',
            'relatedPosts',
            'hasLiked'
        ));
    }


    public function toggleLike(Request $request, Post $post)
    {
        abort_unless(
            $post->is_published &&
            $post->published_at &&
            $post->category?->is_visible,
            404
        );

        $sessionKey = 'liked_posts.' . $post->id;
        $hasLiked = $request->session()->has($sessionKey);

        if ($hasLiked) {
            return response()->json([
                'liked' => true,
                'likes_count' => $post->likes_count,
            ]);
        } else {
            $post->increment('likes_count');

            $request->session()->put($sessionKey, true);

            $liked = true;
        }

        $post->refresh();

        return response()->json([
            'liked' => $liked,
            'likes_count' => $post->likes_count,
        ]);
    }

    public function recordShare(Post $post)
    {
        abort_unless(
            $post->is_published &&
            $post->published_at &&
            $post->category?->is_visible,
            404
        );

        $post->increment('shares_count');
        $post->refresh();

        return response()->json([
            'shares_count' => $post->shares_count,
        ]);
    }
}
