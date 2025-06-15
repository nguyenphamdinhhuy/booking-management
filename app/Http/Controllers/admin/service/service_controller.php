<?php

namespace App\Http\Controllers\admin\service;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class service_controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Service::with('category');
        // loc danh muc nha may em
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $services = $query->get();
        $categories = ServiceCategory::all();

        return view('admin.service.service', compact('services', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ServiceCategory::all(); // để chọn danh mục
        return view('admin.service.add_service', compact('categories'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'unit' => 'required|string|max:50',
            'category_id' => 'required|exists:service_categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'description' => 'nullable|string',
        ]);
        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/services'), $fileName);
            $imagePath = 'uploads/services/' . $fileName;
        }

        Service::create([
            'name' => $request->name,
            'price' => $request->price,
            'unit' => $request->unit,
            'category_id' => $request->category_id,
            'is_available' => $request->has('is_available') ? 1 : 0,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return redirect()->route('services.index')->with('success', 'Thêm dịch vụ thành công!');
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
        $service = Service::findOrFail($id);
        $categories = ServiceCategory::all();
        return view('admin.service.edit_service', compact('service', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'unit' => 'required|string|max:50',
            'category_id' => 'required|exists:service_categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'description' => 'nullable|string',
        ]);

        $service = Service::findOrFail($id);

        $imagePath = $service->image;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/services'), $fileName);
            $imagePath = 'uploads/services/' . $fileName;
        }

        $service->update([
            'name' => $request->name,
            'price' => $request->price,
            'unit' => $request->unit,
            'category_id' => $request->category_id,
            'is_available' => $request->has('is_available') ? 1 : 0,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return redirect()->route('services.index')->with('success', 'Cập nhật dịch vụ thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $service = Service::findOrFail($id);
        $service->delete();
        return redirect()->route('services.index')->with('success', 'Xóa thành công!');
    }
}
