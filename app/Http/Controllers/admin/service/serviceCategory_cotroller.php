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
    public function index()
    {
        $categories = ServiceCategory::all(); // lấy tất cả danh mục
        return view('admin.service.service_categories', compact('categories'));
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
        ]);

        // Lưu vào DB
        ServiceCategory::create([
            'name' => $request->name,
            'description' => $request->description,
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
        ]);

        $category = ServiceCategory::findOrFail($id);
        $category->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('service-categories.index')->with('success', 'Cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = ServiceCategory::findOrFail($id);
        $category->delete();

        return redirect()->route('service-categories.index')->with('success', 'Xóa danh mục thành công!');
    }
}
