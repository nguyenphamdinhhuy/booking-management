<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\ContactReply;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

class contactAdmin_Controller extends Controller
{
    public function index()
    {
        // Lấy id liên hệ mới nhất cho từng user
        $latestContactIds = Contact::select(DB::raw('MAX(id) as id'))
            ->groupBy('user_id')
            ->pluck('id');

        // Lấy danh sách liên hệ dựa trên các id đó
        $contacts = Contact::with('user')
            ->whereIn('id', $latestContactIds)
            ->orderByDesc('created_at')
            ->get();

        return view('admin.contact.contact', compact('contacts'));
    }

    public function show($id)
    {
        $contact = Contact::with(['user', 'replies.admin'])->findOrFail($id);

        // Đánh dấu tất cả tin nhắn của user này là đã xem
        Contact::where('user_id', $contact->user_id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $userId = $contact->user_id;

        $userMessages = Contact::with('user')
            ->where('user_id', $userId)
            ->get()
            ->map(function ($msg) {
                return [
                    'type' => 'user',
                    'name' => $msg->user->name ?? 'Người dùng',
                    'message' => $msg->message,
                    'created_at' => $msg->created_at,
                ];
            });

        $adminReplies = ContactReply::with('admin')
            ->whereIn('contact_id', Contact::where('user_id', $userId)->pluck('id'))
            ->get()
            ->map(function ($reply) {
                return [
                    'type' => 'admin',
                    'name' => $reply->admin->name ?? 'Admin',
                    'message' => $reply->reply,
                    'created_at' => $reply->created_at,
                ];
            });

        $messages = $userMessages->concat($adminReplies)->sortBy('created_at')->values();

        return view('admin.contact.show', compact('contact', 'messages'));
    }




    public function reply(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|string|max:1000',
        ]);

        ContactReply::create([
            'contact_id' => $id,
            'admin_id' => Auth::id(),
            'reply' => $request->reply,
        ]);

        return redirect()->route('admin.contacts.show', $id)->with('success', 'Phản hồi đã được gửi.');
    }
}