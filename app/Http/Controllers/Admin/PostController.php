<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
    public function index()
    {
        // Show newest posts first in the admin area.
        $posts = Post::with('category')
            ->latest()
            ->paginate(10);

        return view('admin.posts.index', compact('posts'));
    }
    /**
     * Show the form for creating a new resource.
     */
        public function create()
    {
        // Load blog categories for the category dropdown.
        $categories = Category::query()
            ->where('type', Category::TYPE_BLOG)
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        return view('admin.posts.create', compact('categories'));
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the submitted blog post fields.
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')
                    ->where(fn ($query) => $query
                        ->where('type', Category::TYPE_BLOG)),
            ],
            'excerpt' => ['nullable', 'string'],
            'body' => ['required', 'string'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['nullable', 'string', 'max:50'],
            'display_order' => ['required', 'integer', 'min:0'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        // Upload the thumbnail when one is provided.
        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request
                ->file('thumbnail')
                ->store('posts', 'public');
        }

        // Clean the separate tag inputs before saving.
        $validated['tags'] = collect($validated['tags'] ?? [])
            ->map(fn ($tag) => trim($tag))
            ->filter()
            ->values()
            ->all();

        // Extract readable text from the Editor.js blocks.
        $bodyData = json_decode($validated['body'], true);

        $plainText = collect($bodyData['blocks'] ?? [])
            ->map(function ($block) {
                return match ($block['type'] ?? null) {
                    'paragraph', 'header', 'quote' =>
                        strip_tags($block['data']['text'] ?? ''),

                    'list' =>
                        collect($block['data']['items'] ?? [])
                            ->map(fn ($item) => is_array($item)
                                ? strip_tags($item['content'] ?? '')
                                : strip_tags($item))
                            ->implode(' '),

                    'code' =>
                        $block['data']['code'] ?? '',

                    default => '',
                };
            })
            ->implode(' ');

        // Calculate the estimated reading time.
        $wordCount = str_word_count($plainText);

        $validated['reading_time'] = max(
            1,
            (int) ceil($wordCount / 200)
        );

        // Generate the URL-friendly slug from the title.
        $validated['slug'] = Str::slug($validated['title']);

        $validated['is_published'] = $request->boolean('is_published');

        // Set the publication date only when the post is published.
        $validated['published_at'] = $validated['is_published']
            ? now()
            : null;
        $validated['user_id'] = $request->user()->id;
        Post::create($validated);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Blog post created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        // Load blog categories for the category dropdown.
        $categories = Category::query()
            ->where('type', Category::TYPE_BLOG)
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        return view('admin.posts.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    
    public function update(Request $request, Post $post)
    {
        // Validate the edited blog post fields.
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')
                    ->where(fn ($query) => $query
                        ->where('type', Category::TYPE_BLOG)),
            ],
            'excerpt' => ['nullable', 'string'],
            'body' => ['required', 'string'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['nullable', 'string', 'max:50'],
            'display_order' => ['required', 'integer', 'min:0'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        // Replace the thumbnail only when a new one is uploaded.
        if ($request->hasFile('thumbnail')) {
            if ($post->thumbnail) {
                Storage::disk('public')->delete($post->thumbnail);
            }

            $validated['thumbnail'] = $request
                ->file('thumbnail')
                ->store('posts', 'public');
        }

        // Clean the separate tag inputs before saving.
        $validated['tags'] = collect($validated['tags'] ?? [])
            ->map(fn ($tag) => trim($tag))
            ->filter()
            ->values()
            ->all();

        // Recalculate reading time from the edited Editor.js content.
        $bodyData = json_decode($validated['body'], true);

        $plainText = collect($bodyData['blocks'] ?? [])
            ->map(function ($block) {
                return match ($block['type'] ?? null) {
                    'paragraph', 'header', 'quote' =>
                        strip_tags($block['data']['text'] ?? ''),

                    'list' =>
                        collect($block['data']['items'] ?? [])
                            ->map(fn ($item) => is_array($item)
                                ? strip_tags($item['content'] ?? '')
                                : strip_tags($item))
                            ->implode(' '),

                    'code' =>
                        $block['data']['code'] ?? '',

                    default => '',
                };
            })
            ->implode(' ');

        $wordCount = str_word_count($plainText);

        $validated['reading_time'] = max(
            1,
            (int) ceil($wordCount / 200)
        );

        // Update the slug whenever the title changes.
        $validated['slug'] = Str::slug($validated['title']);

        $validated['is_published'] = $request->boolean('is_published');

        // Preserve the original publish date unless publication status changes.
        if ($validated['is_published'] && ! $post->published_at) {
            $validated['published_at'] = now();
        }

        if (! $validated['is_published']) {
            $validated['published_at'] = null;
        }

        $post->update($validated);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Blog post updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // Delete the thumbnail file when the post has one.
        if ($post->thumbnail) {
            Storage::disk('public')->delete($post->thumbnail);
        }

        // Delete the blog post.
        $post->delete();

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Blog post deleted successfully.');
    }
}
