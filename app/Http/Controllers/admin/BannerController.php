<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Banner;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Lấy tất cả banner và group theo album_code
        $albums = Banner::orderBy('created_at', 'desc')
            ->get()
            ->groupBy('album_code');

        return view('admin.banner.index', compact('albums'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.banner.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title.*' => 'required|string|max:255',
            'description.*' => 'nullable|string',
            'images_path.*' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
            'status' => 'required|boolean',
        ]);

        $albumCode = uniqid('ALB');

        foreach ($request->file('images_path') as $key => $file) {
            $imagePath = $file->store('uploads/banners', 'public');

            Banner::create([
                'album_code' => $albumCode,
                'title' => $request->title[$key],
                'description' => $request->description[$key] ?? null,
                'images_path' => $imagePath,
                'status' => $request->status,
            ]);
        }

        return redirect()->route('admin.banner.index')->with('success', 'Album banner đã được thêm thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Banner $banner)
    {
        return view('admin.banner.show', compact('banner'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Banner $banner)
    {
        return view('admin.banner.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'status' => 'required|boolean',
        ]);

        $banner->title = $request->title;
        $banner->description = $request->description;
        $banner->status = $request->status;

        if ($request->hasFile('image')) {
            $banner->images_path = $request->file('image')->store('uploads/banners', 'public');
        }

        $banner->save();

        return redirect()->route('admin.banner.index')->with('success', 'Banner đã được cập nhật!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner)
    {
        $banner->delete();
        return redirect()->route('admin.banner.index')->with('success', 'Banner đã bị xóa!');
    }

    /**
     * Update toàn bộ album 3 banner cùng lúc
     */
    public function updateAlbum(Request $request, $albumCode)
    {
        $request->validate([
            'title.*' => 'required|string|max:255',
            'description.*' => 'nullable|string',
            'image.*' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'status' => 'required|boolean',
        ]);

        $banners = Banner::where('album_code', $albumCode)->get();

        foreach ($banners as $key => $banner) {
            $banner->title = $request->title[$key];
            $banner->description = $request->description[$key] ?? null;
            $banner->status = $request->status;  // <-- Sửa ở đây

            if ($request->hasFile('image') && isset($request->image[$key])) {
                $banner->images_path = $request->image[$key]->store('uploads/banners', 'public');
            }

            $banner->save();
        }

        if ($request->status == 1) {
            Banner::where('album_code', '!=', $albumCode)->update(['status' => 0]);
        }

        return redirect()->route('admin.banner.index')->with('success', 'Album banner đã được cập nhật!');
    }
}
