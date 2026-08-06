<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostImageController extends Controller
{
    /**
     * Upload an image used inside an Editor.js blog post.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
        ]);

        $path = $request
            ->file('image')
            ->store('posts/content', 'public');

        // Editor.js expects this exact response structure.
        return response()->json([
            'success' => 1,
            'file' => [
                'url' => asset('storage/' . $path),
            ],
        ]);
    }
}