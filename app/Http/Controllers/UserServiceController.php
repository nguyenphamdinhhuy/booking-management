<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class UserServiceController extends Controller
{
    /**
     * Hiển thị danh sách tất cả dịch vụ
     */
    public function index(Request $request)
    {
        $query = Service::with('category')->where('is_available', 1);

        // Lọc theo danh mục
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Tìm kiếm theo tên
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $services = $query->paginate(12);
        $categories = ServiceCategory::all();

        return view('user.services.index', compact('services', 'categories'));
    }

    /**
     * Hiển thị chi tiết dịch vụ
     */
    public function show($id)
    {
        $service = Service::with('category')->findOrFail($id);
        $relatedServices = Service::where('category_id', $service->category_id)
            ->where('s_id', '!=', $id)
            ->where('is_available', 1)
            ->limit(4)
            ->get();

        return view('user.services.show', compact('service', 'relatedServices'));
    }

    /**
     * Hiển thị dịch vụ theo danh mục
     */
    public function category($categoryId)
    {
        $category = ServiceCategory::findOrFail($categoryId);
        $services = Service::with('category')
            ->where('category_id', $categoryId)
            ->where('is_available', 1)
            ->paginate(12);

        return view('user.services.category', compact('services', 'category'));
    }
}