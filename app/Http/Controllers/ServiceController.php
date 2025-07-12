<?php

namespace App\Http\Controllers;
use App\Models\ServiceCategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $categories = ServiceCategory::with('services')->get(); // Lấy tất cả danh mục + dịch vụ theo từng danh mục

        return view('user.Service.service', compact('categories'));
    }
}
