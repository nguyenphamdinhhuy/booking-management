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
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'phone' => ['required', 'regex:/^[0-9]{10,11}$/'],
        ]);


        Contact::create([
            'user_id' => Auth::user()->id,
            'subject' => $request->subject,
            'message' => $request->message,
            'phone' => $request->phone,
        ]);

        return redirect()->back()->with('success', 'Gửi liên hệ thành công!');
    }
}
