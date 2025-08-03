<?php

namespace App\Http\Controllers\admin;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class vouchers_controller extends Controller
{
    public function management(Request $request)
    {
        try {
            // Khởi tạo query builder
            $query = DB::table('vouchers');

            // Lọc theo trạng thái
            if ($request->has('status') && $request->status !== '') {
                $query->where('status', $request->status);
            }

            // Tìm kiếm theo mã voucher hoặc mô tả
            if ($request->has('search') && $request->search !== '') {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('v_code', 'LIKE', "%{$search}%")
                        ->orWhere('description', 'LIKE', "%{$search}%");
                });
            }

            // Lấy dữ liệu và sắp xếp theo ID mới nhất
            $vouchers = $query->orderBy('v_id', 'desc')->get();

            return view('admin.vouchers.vouchers_management', compact('vouchers'));
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi tải dữ liệu voucher: ' . $e->getMessage());
        }
    }

    /**
     * Hiển thị form tạo voucher mới
     */
    public function create()
    {
        return view('admin.vouchers.add_vouchers');
    }

    /**
     * Lưu voucher mới
     */
    public function store(Request $request)
    {
        try {
            // Validate dữ liệu
            $validated = $request->validate([
                'v_code' => 'required|string|max:10|unique:vouchers,v_code',
                'discount_percent' => 'required|numeric|min:0|max:100',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'status' => 'required|boolean',
                'description' => 'nullable|string'
            ], [
                'v_code.required' => 'Mã voucher là bắt buộc',
                'v_code.unique' => 'Mã voucher đã tồn tại',
                'v_code.max' => 'Mã voucher không được quá 10 ký tự',
                'discount_percent.required' => 'Phần trăm giảm giá là bắt buộc',
                'discount_percent.min' => 'Phần trăm giảm giá phải lớn hơn 0',
                'discount_percent.max' => 'Phần trăm giảm giá không được quá 100',
                'start_date.required' => 'Ngày bắt đầu là bắt buộc',
                'end_date.required' => 'Ngày kết thúc là bắt buộc',
                'end_date.after' => 'Ngày kết thúc phải sau ngày bắt đầu',
                'status.required' => 'Trạng thái là bắt buộc'
            ]);

            // Chèn dữ liệu vào database
            DB::table('vouchers')->insert([
                'v_code' => strtoupper($validated['v_code']),
                'discount_percent' => $validated['discount_percent'],
                'start_date' => Carbon::parse($validated['start_date'])->format('Y-m-d H:i:s'),
                'end_date' => Carbon::parse($validated['end_date'])->format('Y-m-d H:i:s'),
                'status' => $validated['status'],
                'description' => $validated['description']
            ]);

            return redirect()->route('vouchers.management')
                ->with('success', 'Tạo voucher mới thành công!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Có lỗi xảy ra khi tạo voucher: ' . $e->getMessage());
        }
    }

    /**
     * Hiển thị form chỉnh sửa voucher
     */
    public function edit($id)
    {
        try {
            $voucher = DB::table('vouchers')->where('v_id', $id)->first();

            if (!$voucher) {
                return redirect()->route('vouchers.management')
                    ->with('error', 'Không tìm thấy voucher!');
            }

            return view('admin.vouchers.edit_vouchers', compact('voucher'));
        } catch (\Exception $e) {
            return redirect()->route('vouchers.management')
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Cập nhật voucher
     */
    public function update(Request $request, $id)
    {
        try {
            // Kiểm tra voucher có tồn tại
            $voucher = DB::table('vouchers')->where('v_id', $id)->first();
            if (!$voucher) {
                return redirect()->route('vouchers.management')
                    ->with('error', 'Không tìm thấy voucher!');
            }

            // Validate dữ liệu
            $validated = $request->validate([
                'v_code' => 'required|string|max:10|unique:vouchers,v_code,' . $id . ',v_id',
                'discount_percent' => 'required|numeric|min:0|max:100',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'status' => 'required|boolean',
                'description' => 'nullable|string'
            ], [
                'v_code.required' => 'Mã voucher là bắt buộc',
                'v_code.unique' => 'Mã voucher đã tồn tại',
                'v_code.max' => 'Mã voucher không được quá 10 ký tự',
                'discount_percent.required' => 'Phần trăm giảm giá là bắt buộc',
                'discount_percent.min' => 'Phần trăm giảm giá phải lớn hơn 0',
                'discount_percent.max' => 'Phần trăm giảm giá không được quá 100',
                'start_date.required' => 'Ngày bắt đầu là bắt buộc',
                'end_date.required' => 'Ngày kết thúc là bắt buộc',
                'end_date.after' => 'Ngày kết thúc phải sau ngày bắt đầu',
                'status.required' => 'Trạng thái là bắt buộc'
            ]);

            // Cập nhật dữ liệu
            DB::table('vouchers')
                ->where('v_id', $id)
                ->update([
                    'v_code' => strtoupper($validated['v_code']),
                    'discount_percent' => $validated['discount_percent'],
                    'start_date' => Carbon::parse($validated['start_date'])->format('Y-m-d H:i:s'),
                    'end_date' => Carbon::parse($validated['end_date'])->format('Y-m-d H:i:s'),
                    'status' => $validated['status'],
                    'description' => $validated['description']
                ]);

            return redirect()->route('vouchers.management')
                ->with('success', 'Cập nhật voucher thành công!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật voucher: ' . $e->getMessage());
        }
    }

    /**
     * Xóa voucher
     */
    public function delete($id)
    {
        try {
            $voucher = DB::table('vouchers')->where('v_id', $id)->first();

            if (!$voucher) {
                return redirect()->route('vouchers.management')
                    ->with('error', 'Không tìm thấy voucher!');
            }

            // Xóa voucher
            DB::table('vouchers')->where('v_id', $id)->delete();

            return redirect()->route('vouchers.management')
                ->with('success', 'Xóa voucher thành công!');
        } catch (\Exception $e) {
            return redirect()->route('vouchers.management')
                ->with('error', 'Có lỗi xảy ra khi xóa voucher: ' . $e->getMessage());
        }
    }

    /**
     * AJAX tìm kiếm voucher (tùy chọn)
     */
    public function search(Request $request)
    {
        try {
            $query = DB::table('vouchers');

            // Lọc theo trạng thái
            if ($request->has('status') && $request->status !== '') {
                $query->where('status', $request->status);
            }

            // Tìm kiếm
            if ($request->has('search') && $request->search !== '') {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('v_code', 'LIKE', "%{$search}%")
                        ->orWhere('description', 'LIKE', "%{$search}%");
                });
            }

            $vouchers = $query->orderBy('v_id', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $vouchers,
                'count' => $vouchers->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
    public function user_vouchers(Request $request)
    {
        try {
            // Khởi tạo query builder
            $query = DB::table('vouchers')
                ->where('status', 1) // Chỉ lấy voucher đang hoạt động
                ->where('end_date', '>=', Carbon::now()); // Chỉ lấy voucher chưa hết hạn

            // Lọc theo loại voucher nếu có
            if ($request->has('type') && $request->type !== '') {
                // Giả sử có field 'type' trong database
                $query->where('type', $request->type);
            }

            // Tìm kiếm theo mã voucher hoặc mô tả
            if ($request->has('search') && $request->search !== '') {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('v_code', 'LIKE', "%{$search}%")
                        ->orWhere('description', 'LIKE', "%{$search}%");
                });
            }

            // Sắp xếp theo discount_percent giảm dần (voucher có giá trị cao nhất trước)
            $vouchers = $query->orderBy('discount_percent', 'desc')
                ->orderBy('end_date', 'asc')
                ->get();

            // Phân loại vouchers
            $featured_vouchers = $vouchers->where('discount_percent', '>=', 20)->take(3); // Voucher nổi bật
            $all_vouchers = $vouchers;

            return view('user.vouchers', compact('vouchers', 'featured_vouchers', 'all_vouchers'));
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi tải dữ liệu voucher: ' . $e->getMessage());
        }
    }

    /**
     * Sao chép mã voucher (AJAX)
     */
    public function copyVoucherCode(Request $request)
    {
        try {
            $voucher = DB::table('vouchers')
                ->where('v_id', $request->voucher_id)
                ->where('status', 1)
                ->where('end_date', '>=', Carbon::now())
                ->first();

            if (!$voucher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Voucher không tồn tại hoặc đã hết hạn!'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Đã sao chép mã voucher: ' . $voucher->v_code,
                'code' => $voucher->v_code
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}
