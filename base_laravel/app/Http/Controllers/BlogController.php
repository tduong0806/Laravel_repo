<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Blog::all();
        return view('blogs.index', compact('posts'));
    }

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:public,draft',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle the image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('img', 'minio');
        }

        // Create the blog post
        Blog::create([
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status,
            'image' => $imagePath,
        ]);

        return redirect()->route('blogs.index')->with('success', 'Post created successfully.');
    }
}