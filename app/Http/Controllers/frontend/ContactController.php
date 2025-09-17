<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('frontend.contact'); // Hiển thị form liên hệ
    }

    public function store(Request $request)
    {
        // Kiểm tra dữ liệu hợp lệ
        $request->validate([
            'contact_name' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:20',  // Kiểm tra số điện thoại
            'contact_subject' => 'required|string|max:255',
            'contact_message' => 'required|string',
        ]);

        // Lưu vào cơ sở dữ liệu
        $contact = new Contact();
        $contact->name = $request->input('contact_name');
        $contact->email = $request->input('contact_email');
        $contact->phone = $request->input('contact_phone');
        $contact->title = $request->input('contact_subject');
        $contact->content = $request->input('contact_message');
        $contact->user_id = auth()->id();
        $contact->status = 0;  // Mặc định là chưa xử lý
        $contact->created_by = auth()->id() ?? 0;  // Nếu có đăng nhập, lưu ID người dùng
        $contact->save();

        return redirect()->route('site.contact')->with('success', 'Cảm ơn bạn đã liên hệ!');
    }
}
