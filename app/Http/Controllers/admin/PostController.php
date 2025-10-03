<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::query();

        // Lọc theo trạng thái (0/1, bỏ qua nếu để trống)
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', (int)$request->status);
        }

        // Lọc theo danh mục
        if ($request->filled('category')) {
            $query->where('category', 'like', '%' . $request->category . '%');
        }

        // Lọc theo tiêu đề hoặc tác giả
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('author', 'like', '%' . $request->search . '%');
            });
        }

        // Phân trang thay vì lấy tất cả
        $posts = $query->orderBy('p_id', 'desc')->paginate(10)->appends($request->query());

        return view('admin.post.post', compact('posts'));
    }

    public function create()
    {
        return view('admin.post.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'category' => 'required',
            'status' => 'required|boolean',
            'author' => 'required',
            'published_at' => 'required|date',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5120',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
        }

        Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'category' => $request->category,
            'images' => $imagePath,
            'status' => $request->status,
            'author' => $request->author,
            'published_at' => $request->published_at,
        ]);

        return redirect()->route('admin.post.index')->with('success', 'Thêm bài viết thành công!');
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return view('admin.post.edit', compact('post'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'category' => 'required',
            'status' => 'required|boolean',
            'author' => 'required',
            'published_at' => 'required|date',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5120',
        ]);

        $post = Post::findOrFail($id);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
} else {
            $imagePath = $post->images;
        }

        $post->update([
            'title' => $request->title,
            'content' => $request->content,
            'category' => $request->category,
            'images' => $imagePath,
            'status' => $request->status,
            'author' => $request->author,
            'published_at' => $request->published_at,
        ]);

        return redirect()->route('admin.post.index')->with('success', 'Cập nhật bài viết thành công!');
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return redirect()->route('admin.post.index')->with('success', 'Xóa bài viết thành công!');
    }

    public function getAll()
    {
        $posts = Post::where('status', 1)
            ->orderBy('p_id', 'desc')
            ->get();

        return view('user.post', compact('posts'));
    }
}
