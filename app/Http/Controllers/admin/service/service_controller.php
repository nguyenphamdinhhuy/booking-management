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
        $query = Service::with('category')
            ->whereHas('category', function ($q) {
                $q->whereNull('deleted_at')->where('is_available', 1);
            });
        // loc theo trang thai 
        if ($request->filled('is_available')) {
            $query->where('is_available', $request->is_available);
        }
        // loc danh muc nha may em
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        // lọc theo giá
        if ($request->filled('price_range')) {
            $range = $request->price_range;

            if ($range === '0-500000') {
                $query->whereBetween('price', [0, 500000]);
            } elseif ($range === '500000-1000000') {
                $query->whereBetween('price', [500000, 1000000]);
            } elseif ($range === '1000000-2000000') {
                $query->whereBetween('price', [1000000, 2000000]);
            } elseif ($range === '2000000+') {
                $query->where('price', '>=', 2000000);
            }
        }
        //tim kiem
        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        // phaan trang
        $services = $query->paginate(10)->withQueryString();
        $categories = ServiceCategory::whereNull('deleted_at')->where('is_available', 1)->orderBy('name')->get();
        return view('admin.service.service', compact('services', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ServiceCategory::whereNull('deleted_at')->where('is_available', 1)->orderBy('name')->get(); // để chọn danh mục
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
        $categories = ServiceCategory::whereNull('deleted_at')->where('is_available', 1)->orderBy('name')->get();
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
        $service->update(
            [
                'deleted_at' => now(),
            ]
        );
        return redirect()->route('services.index')->with('success', 'Xóa thành công!');
    }

    public function trashCan(Request $request)
    {
        $query = Service::onlyTrashed()->with('category')
            ->whereHas('category', function ($q) {
                $q->whereNull('deleted_at')->where('is_available', 1);
            });
        // loc theo trang thai 

        if ($request->filled('deleted_from') && $request->filled('deleted_to')) {
            if ($request->deleted_from <= $request->deleted_to) {
                $query->whereBetween('deleted_at', [
                    $request->deleted_from . ' 00:00:00',
                    $request->deleted_to . ' 23:59:59',
                ]);
            }
        } elseif ($request->filled('deleted_from')) {
            $query->where('deleted_at', '>=', $request->deleted_from . ' 00:00:00');
        } elseif ($request->filled('deleted_to')) {
            $query->where('deleted_at', '<=', $request->deleted_to . ' 23:59:59');
        }
        // loc danh muc nha may em
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        // lọc theo giá
        if ($request->filled('price_range')) {
            $range = $request->price_range;

            if ($range === '0-500000') {
                $query->whereBetween('price', [0, 500000]);
            } elseif ($range === '500000-1000000') {
                $query->whereBetween('price', [500000, 1000000]);
            } elseif ($range === '1000000-2000000') {
                $query->whereBetween('price', [1000000, 2000000]);
            } elseif ($range === '2000000+') {
                $query->where('price', '>=', 2000000);
            }
        }
        //tim kiem
        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        // phaan trang
        $services = $query->paginate(10)->withQueryString();
        $categories = ServiceCategory::whereNull('deleted_at')->where('is_available', 1)->orderBy('name')->get();
        return view('admin.service.trashCan_service', compact('services', 'categories'));
    }

    // nguoi dung
    public function serviceDetails($id)
    {
        $services = Service::where('category_id', $id)->where('is_available', 1)->get();
        $categories = ServiceCategory::all();
        $currentCategoryId = $id;
        return view('user.Service.serviceDetails', compact('services', 'categories', 'currentCategoryId'));
    }

    // xóa tất cả 
    public function delete_all()
    {
        Service::whereNotNull('deleted_at')->forceDelete();
        return redirect()->route('service.trashCan')->with('success', 'Xóa thành công!');
    }
    // khôi phục tất cả
    public function restore_all()
    {
        Service::whereNotNull('deleted_at')->restore();
        return redirect()->route('service.trashCan')->with('success', 'khôi phục thành công!');
    }
    public function deleted($s_id)
    {
        $service = Service::withTrashed()->find($s_id); // tìm bản ghi theo id
        if ($service) {
            $service->forceDelete(); // xóa mềm
            return redirect()->back()->with('success', 'Đã xóa sản phẩm.');
        }
        return redirect()->back()->with('error', 'Sản phẩm không tồn tại.');
    }
    public function restore($s_id)
    {
        $service = Service::withTrashed()->find($s_id);
        if ($service) {
            $service->restore();
            return redirect()->back()->with('success', 'Sản phẩm đã được khôi phục.');

        }
        return redirect()->back()->with('error', 'Sản phẩm đã được khôi phục.');
    }







}
