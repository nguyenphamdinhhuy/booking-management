<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    // Trang hiển thị form liên hệ + lịch sử trò chuyện
    public function create()
    {
        $user = Auth::user();

        // Lấy các liên hệ của user và các phản hồi admin liên quan
        $contacts = Contact::where('user_id', $user->id)
            ->with('replies.admin') // cần có quan hệ replies -> admin
            ->orderBy('created_at', 'asc')
            ->get();

        $messages = [];

        foreach ($contacts as $contact) {
            // Tin nhắn từ user
            $messages[] = [
                'type' => 'user',
                'message' => $contact->message,
                'name' => $user->name,
                'created_at' => $contact->created_at
            ];

            // Phản hồi của admin
            foreach ($contact->replies as $reply) {
                $messages[] = [
                    'type' => 'admin',
                    'message' => $reply->reply,
                    'name' => $reply->admin->name ?? 'Admin',
                    'created_at' => $reply->created_at
                ];
            }
        }

        // Sắp xếp toàn bộ theo thời gian tăng dần
        usort($messages, function ($a, $b) {
            return strtotime($a['created_at']) <=> strtotime($b['created_at']);
        });

        return view('user.contact', compact('messages'));
    }

    // Xử lý lưu liên hệ mới
    public function store(Request $request)
    {
        $user = Auth::user();

        // Kiểm tra xem user đã từng gửi liên hệ chưa
        $oldContact = Contact::where('user_id', $user->id)->first();

        if ($oldContact) {
            // Nếu đã có, dùng phone + subject cũ
            $phone = $oldContact->phone;
            $subject = $oldContact->subject;

            // Riêng khi user gửi từ form lớn thì vẫn ưu tiên giá trị mới (nếu có)
            if ($request->has('phone') && $request->phone !== '') {
                $phone = $request->phone;
            }
            if ($request->has('subject') && $request->subject !== '') {
                $subject = $request->subject;
            }
        } else {
            // Nếu chưa từng liên hệ thì validate bắt buộc phone & subject
            $request->validate([
                'phone' => ['required', 'regex:/^[0-9]{10,11}$/'],
                'subject' => 'required|string|max:255'
            ]);

            $phone = $request->phone;
            $subject = $request->subject;
        }

        // Lưu liên hệ mới
        Contact::create([
            'user_id' => $user->id,
            'phone' => $phone,
            'subject' => $subject,
            'message' => $request->message,
        ]);

        return redirect()->back()->with('success', 'Gửi liên hệ thành công!');
    }
}
