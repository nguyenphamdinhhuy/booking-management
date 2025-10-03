<?php

namespace App\Http\Controllers\admin\service;

use App\Models\ServiceCategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class serviceCategory_cotroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ServiceCategory::whereNull('deleted_at');
        // loc theo loai
        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }
        // loc trang thai
        if ($request->filled('is_available')) {
            $query->where('is_available', $request->is_available);
        }
        // tim kiem 
        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }
        $categories = $query->paginate(10)->withQueryString();
        $service_Category = ServiceCategory::whereNull('deleted_at')->orderBy('name')->get();
        return view('admin.service.service_categories', compact('categories', 'service_Category'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.service.add_serviceCategory');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_available' => 'required|boolean',
        ]);
        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/service'), $fileName);
            $imagePath = 'uploads/service/' . $fileName;
        }
        // Lưu vào DB
        ServiceCategory::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $imagePath,
            'is_available' => $request->is_available,
        ]);

        return redirect()->route('service-categories.index')->with('success', 'Thêm danh mục thành công!');
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
    public function edit(string $id)
    {
        $category = ServiceCategory::findOrFail($id);
        return view('admin.service.edit_serviceCategory', compact('category'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:ipg,jpeg,png|max:2048',
            'is_available' => 'required|boolean',
        ]);

        $category = ServiceCategory::findOrFail($id);
        $imagePath = $category->image;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('upload/services'), $fileName);
            $imagePath = 'upload/services/' . $fileName;
        }
        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $imagePath,
            'is_available' => $request->is_available,
        ]);

        return redirect()->route('service-categories.index')->with('success', 'Cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = ServiceCategory::findOrFail($id);
        $category->update(
            ['deleted_at' => now()]
        );

        return redirect()->route('service-categories.index')->with('success', 'Xóa danh mục thành công!');
    }
}
