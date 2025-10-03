<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facade\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    /**
     * Hiển thị form đánh giá
     */
    public function review_mangament()
    {
        // Lấy tất cả review kèm theo thông tin khách hàng & phòng
        $reviews = \DB::table('reviews')
            ->join('booking', 'reviews.bs_id', '=', 'booking.b_id')
            ->join('booking_details', 'booking.b_id', '=', 'booking_details.b_id')
            ->join('rooms', 'booking_details.r_id', '=', 'rooms.r_id')
            ->select(
                'reviews.rv_id',
                'reviews.rating',
                'reviews.comments',
                'reviews.status',
                'reviews.created_at',
                'rooms.name as room_name'
            )
            ->orderBy('reviews.created_at', 'desc')
            ->get();

        return view('admin.review.index', compact('reviews'));
    }
    public function toggleStatus($id)
    {
        $review = \DB::table('reviews')->where('rv_id', $id)->first();

        if (!$review) {
            return redirect()->back()->with('error', 'Bình luận không tồn tại!');
        }

        $newStatus = $review->status == 1 ? 0 : 1;

        \DB::table('reviews')
            ->where('rv_id', $id)
            ->update(['status' => $newStatus]);

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công!');
    }

    public function showReviewForm($bookingId)
    {
        // Kiểm tra xem user có thể đánh giá booking này không
        if (!Review::canUserReview(Auth::id(), $bookingId)) {
            return redirect()->back()->with('error', 'Bạn không thể đánh giá đơn đặt phòng này');
        }

        // Lấy thông tin booking
        $booking = DB::table('booking as b')
            ->join('booking_details as bd', 'b.b_id', '=', 'bd.b_id')
            ->join('rooms as r', 'bd.r_id', '=', 'r.r_id')
            ->select(
                'b.b_id',
                'b.check_in_date',
                'b.check_out_date',
                'b.total_price',
                'r.name as room_name',
                'r.images',
                'r.description'
            )
            ->where('b.b_id', $bookingId)
            ->first();

        if (!$booking) {
            return redirect()->back()->with('error', 'Không tìm thấy đơn đặt phòng');
        }

        return view('user.review_form', compact('booking'));
    }

    /**
     * Xử lý đánh giá từ user
     */
    public function store(Request $request, $bookingId)
    {
        // Kiểm tra xem user có thể đánh giá booking này không
        if (!Review::canUserReview(Auth::id(), $bookingId)) {
            return redirect()->back()->with('error', 'Bạn không thể đánh giá đơn đặt phòng này');
        }

        // Validate dữ liệu
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comments' => 'required|string|min:10|max:1000'
        ], [
            'rating.required' => 'Vui lòng chọn số sao đánh giá',
            'rating.integer' => 'Số sao phải là số nguyên',
            'rating.min' => 'Số sao tối thiểu là 1',
            'rating.max' => 'Số sao tối đa là 5',
            'comments.required' => 'Vui lòng nhập nội dung đánh giá',
            'comments.min' => 'Nội dung đánh giá phải có ít nhất 10 ký tự',
            'comments.max' => 'Nội dung đánh giá không được quá 1000 ký tự'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Danh sách từ cấm
            $badWords = [
                // Tiếng Việt viết tắt
                'đm',
                'dm',
                'đcm',
                'cc',
                'cl',
                'vl',
                'vkl',
                'dkm',
                'đkm',
                'đụ',
                'địt',
                'lồn',
                'buồi',
                'cặc',
                'cặc',
                'cmm',
                'má mày',
                'mẹ mày',
                'con mẹ mày',
                'con mẹ nó',
                'óc chó',
                'óc lợn',
                'chó má',
                'thằng chó',

                // Tiếng Anh
                'fuck',
                'f*ck',
                'f**k',
                'shit',
                'bitch',
                'bastard',
                'asshole',
                'dick',
                'pussy',
                'slut',
                'whore',
                'motherfucker',
                'mf',
                'wtf'
            ];

            // Lấy nội dung comment
            $comment = $request->comments;

            // Thay thế các từ cấm bằng ***
            foreach ($badWords as $word) {
                $pattern = '/' . preg_quote($word, '/') . '/i'; // không phân biệt hoa thường
                $comment = preg_replace($pattern, str_repeat('*', mb_strlen($word)), $comment);
            }

            // Debug: Log dữ liệu trước khi tạo
            \Log::info('Creating review with data:', [
                'bs_id' => $bookingId,
                'c_id' => Auth::id(),
                'rating' => $request->rating,
                'comments' => $comment,
                'status' => 1
            ]);

            // Tạo đánh giá mới
            $review = Review::create([
                'bs_id' => $bookingId,
                'c_id' => Auth::id(),
                'rating' => $request->rating,
                'comments' => $comment,
                'status' => 1,
                'created_at' => now()
            ]);

            // Debug: Log kết quả
            \Log::info('Review created successfully:', $review->toArray());

            return redirect()->route('booking.history', ['userId' => Auth::id()])
                ->with('success', 'Cảm ơn bạn đã đánh giá! Đánh giá của bạn đã được ghi nhận.');
        } catch (\Exception $e) {
            \Log::error('Error creating review: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi lưu đánh giá: ' . $e->getMessage())
                ->withInput();
        }
    }


    /**
     * Hiển thị danh sách đánh giá của một phòng
     */
    public function showRoomReviews($roomId)
    {
        $reviews = Review::active()
            ->with('customer')
            ->whereExists(function ($subquery) use ($roomId) {
                $subquery->select(DB::raw(1))
                    ->from('booking')
                    ->join('booking_details', 'booking.b_id', '=', 'booking_details.b_id')
                    ->whereColumn('reviews.bs_id', 'booking.b_id')
                    ->where('booking_details.r_id', $roomId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Tính rating trung bình
        $avgRating = $reviews->avg('rating');
        $totalReviews = $reviews->count();

        return view('user.room_detail', compact('reviews', 'avgRating', 'totalReviews', 'roomId'));
    }

    /**
     * Hiển thị đánh giá của user
     */
    public function showUserReviews()
    {
        $reviews = Review::where('c_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.my_reviews', compact('reviews'));
    }

    /**
     * Cập nhật đánh giá của user
     */
    public function update(Request $request, $reviewId)
    {
        $review = Review::where('rv_id', $reviewId)
            ->where('c_id', Auth::id())
            ->first();

        if (!$review) {
            return redirect()->back()->with('error', 'Không tìm thấy đánh giá');
        }

        // Validate dữ liệu
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comments' => 'required|string|min:10|max:1000'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $review->update([
                'rating' => $request->rating,
                'comments' => $request->comments
            ]);

            return redirect()->back()->with('success', 'Cập nhật đánh giá thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi cập nhật đánh giá: ' . $e->getMessage());
        }
    }

    /**
     * Xóa đánh giá của user
     */
    public function destroy($reviewId)
    {
        $review = Review::where('rv_id', $reviewId)
            ->where('c_id', Auth::id())
            ->first();

        if (!$review) {
            return redirect()->back()->with('error', 'Không tìm thấy đánh giá');
        }

        try {
            $review->delete();
            return redirect()->back()->with('success', 'Xóa đánh giá thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi xóa đánh giá: ' . $e->getMessage());
        }
    }
}
