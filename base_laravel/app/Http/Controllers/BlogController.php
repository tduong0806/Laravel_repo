<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Tag;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class BlogController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $posts = Blog::with(['category', 'tags', 'taggedUsers', 'author'])
                    ->where('status', 'public')
                    ->where('is_approved', true)
                    ->get();
    
        $tags = Tag::all();
        $categories = Category::all();
        $users = User::all();
    
        return view('blogs.index', compact('posts', 'categories', 'tags', 'users'));
    }

    public function drafts()
    {
        $id = Auth::user()->id;
        $user = User::find($id);
        if ($user->hasRole('sysadmin') || $user->hasRole('manager')) {
            $posts = Blog::with(['category', 'tags', 'taggedUsers', 'author'])
            ->where('status', 'draft')
            ->get();
        } else {
            $posts = Blog::with(['category', 'tags', 'taggedUsers', 'author'])
                        ->where('status', 'draft')
                        ->where('user_id', $id)
                        ->get();
        }

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
            'is_approved' => $request->status == 'public' ? false : true,
        ]);

        if ($request->has('tags')) {
            $blog->tags()->sync($request->tags);
        }

        if ($request->has('users')) {
            $blog->users()->sync($request->users);
        }

        return redirect()->route('blogs.index')->with('success', 'Post created successfully.');
    }

    public function edit($id)
    {
        // Tìm bài viết theo ID, nếu không tồn tại sẽ trả về lỗi 404
        $post = Blog::findOrFail($id);
        $this->authorize('access', $post);
        $tags = Tag::all();
        $categories = Category::all();
        $users = User::all();
    
        // Trả về view 'blogs.edit' và truyền dữ liệu $post vào view
        return view('blogs.edit', compact('post', 'tags', 'categories', 'users'));
    }

    private function checkBadWords($title, $content)
    {
        $badWords = config('blacklist.badwords');

        foreach ($badWords as $badWord) {
            if (stripos($title, $badWord) !== false || stripos($content, $badWord) !== false) {
                return true;
            }
        }
    
        return false;
    }

    public function update(Request $request, $id)
    {

        if ($this->checkBadWords($request->input('title'), $request->input('content'))) {
            return redirect()->back()->withErrors(['message' => 'Content contains inappropriate words.']);
        }
    
        // Tìm bài viết theo ID, nếu không tồn tại sẽ trả về lỗi 404
        $post = Blog::findOrFail($id);
        $this->authorize('access', $post);
    
        // Xác thực dữ liệu đầu vào
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,public',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
            'users' => 'array',
            'users.*' => 'exists:users,id',
            'image' => 'nullable|image|max:2048', // Kiểm tra file ảnh nếu có
        ]);
    
        // Cập nhật thông tin bài viết
        $post->title = $validatedData['title'];
        $post->content = $validatedData['content'];
        $post->status = $validatedData['status'];
        $post->category_id = $validatedData['category_id'];
        $post->is_approved = $request->status == 'public' ? false : true;
    
        // Cập nhật tags nếu có
        if (isset($validatedData['tags'])) {
            $post->tags()->sync($validatedData['tags']);
        }
    
        // Cập nhật tagged users nếu có
        if (isset($validatedData['users'])) {
            $post->taggedUsers()->sync($validatedData['users']);
        }
    
        // Kiểm tra và cập nhật hình ảnh nếu có
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('img', 'minio');
            $post->image = $imagePath;
        }
    
        // Lưu bài viết
        $post->save();
    
        // Chuyển hướng về trang chi tiết bài viết với thông báo thành công
        return redirect()->route('blogs.show', $post->id)->with('success', 'Blog post updated successfully!');
    }

    public function destroy($id)
    {
        $post = Blog::findOrFail($id);
        $this->authorize('access', $post);
        // Delete image if exists
        if ($post->image) {
            Storage::disk('minio')->delete($post->image);
        }

        // Detach tags and users
        $post->tags()->detach();
        $post->users()->detach();

        // Delete the blog
        $post->delete();

        return redirect()->route('blogs.index')->with('success', 'Blog post deleted successfully!');
    }

    public function show($id)
    {
        $post = Blog::with(['category', 'tags', 'taggedUsers'])->findOrFail($id);
        $this->authorize('access', $post);

        $post->increment('views');

        return view('blogs.show', compact('post'));
    }

    public function approve($id)
    {
        // Kiểm tra quyền của người dùng, chỉ cho phép sysadmin và manager phê duyệt
        $post = Blog::findOrFail($id);
        $this->authorize('approve', $post);

        $post->is_approved = true;
        $post->save();

        return redirect()->route('blogs.pending')->with('success', 'Blog post approved successfully.');
    }

    public function pending()
    {
        $post = new Blog();
        $this->authorize('approve', $post);
        $posts = Blog::PendingApproval()->get();

        return view('blogs.pending', compact('posts'));
    }
}