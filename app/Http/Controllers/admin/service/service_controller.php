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
            'is_available' => 'required|boolean',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'max_quantity' => 'nullable|integer',
            'service_time' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'note' => 'nullable|string',
        ]);
        $data = $request->only([
            'name',
            'price',
            'unit',
            'category_id',
            'is_available',
            'description',
            'max_quantity',
            'service_time',
            'location',
            'note',
        ]);
        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/services'), $fileName);
            $data['image'] = 'uploads/services/' . $fileName;
        }



        Service::create($data);

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
            'is_available' => 'required|boolean',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'max_quantity' => 'nullable|integer',
            'service_time' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'note' => 'nullable|string',
        ]);
        $data = $request->only([
            'name',
            'price',
            'unit',
            'category_id',
            'is_available',
            'description',
            'max_quantity',
            'service_time',
            'location',
            'note',
        ]);

        $service = Service::findOrFail($id);

        $imagePath = $service->image;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/services'), $fileName);
            $data['image'] = 'uploads/services/' . $fileName;
        }

        $service->update($data);

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



    // nguoi dung
    public function serviceDetails($id)
    {
        $services = Service::where('category_id', $id)->where('is_available', 1)->get();
        $categories = ServiceCategory::all();
        $currentCategoryId = $id;
        return view('user.Service.serviceDetails', compact('services', 'categories', 'currentCategoryId'));
    }





}
