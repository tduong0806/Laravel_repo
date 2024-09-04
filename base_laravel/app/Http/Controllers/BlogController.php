<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Tag;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Blog::with(['category', 'tags', 'taggedUsers', 'author'])
                    ->where('status', 'public')
                    ->get();
    
        $tags = Tag::all();
        $categories = Category::all();
        $users = User::all();
    
        return view('blogs.index', compact('posts', 'categories', 'tags', 'users'));
    }

    public function drafts()
    {
        $posts = Blog::with(['category', 'tags', 'taggedUsers', 'author'])
                    ->where('status', 'draft')
                    ->where('user_id', Auth::user()->id)
                    ->get();

        return view('blogs.drafts', compact('posts'));
    }

    public function create()
    {
        $tags = Tag::all();
        $categories = Category::all();
        $users = User::all();
        return view('blogs.create', compact('tags', 'categories', 'users'));
    }

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:public,draft',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'array|nullable',
            'tags.*' => 'exists:tags,id',
            'users' => 'array|nullable',
            'users.*' => 'exists:users,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
        ]);

        // Handle the image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('img', 'minio');
        }

        // Create the blog post
       
        $blog = Blog::create([
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status,
            'image' => $imagePath,
            'category_id' => $request->category_id,
            'user_id' => Auth::user()->id,
        ]);

        if ($request->has('tags')) {
            $blog->tags()->sync($request->tags);
        }

        if ($request->has('users')) {
            $blog->users()->sync($request->users);
        }

        return redirect()->route('blogs.index')->with('success', 'Post created successfully.');
    }

    public function edit(Blog $blog)
    {
        $tags = Tag::all();
        $categories = Category::all();
        $users = User::all();
        return view('blogs.edit', compact('blog', 'tags', 'categories', 'users'));
    }

    public function update(Request $request, Blog $blog)
    {
        // Validate input
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|string|in:draft,public',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'array|nullable',
            'tags.*' => 'exists:tags,id',
            'users' => 'array|nullable',
            'users.*' => 'exists:users,id',
            'image' => 'nullable|image|max:4096',
        ]);

        // Update blog
        $blog->update($request->only('title', 'content', 'status', 'category_id'));

        // Sync tags
        if ($request->has('tags')) {
            $blog->tags()->sync($request->tags);
        }

        // Sync users
        if ($request->has('users')) {
            $blog->users()->sync($request->users);
        }

        // Handle image upload if exists
        if ($request->hasFile('image')) {   
            // Delete old image if exists
            if ($blog->image) {
                Storage::disk('minio')->delete($blog->image);
            }

            $imagePath = $request->file('image')->store('img', 'minio');
            $blog->image = $imagePath;
            $blog->save();
        }

        return redirect()->route('blogs.index')->with('success', 'Blog post updated successfully!');
    }

    public function destroy(Blog $blog)
    {
        // Delete image if exists
        if ($blog->image) {
            Storage::disk('minio')->delete($blog->image);
        }

        // Detach tags and users
        $blog->tags()->detach();
        $blog->users()->detach();

        // Delete the blog
        $blog->delete();

        return redirect()->route('blogs.index')->with('success', 'Blog post deleted successfully!');
    }

    public function show($id)
    {
        $post = Blog::with(['category', 'tags', 'taggedUsers'])->findOrFail($id);

        $post->increment('views');

        return view('blogs.show', compact('post'));
    }
}