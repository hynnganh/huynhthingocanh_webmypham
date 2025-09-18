<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    /**
     * Hiển thị danh sách liên hệ.
     */
    public function index()
    {
        $list = Contact::select(
                'id',
                'name',
                'email',
                'phone',
                'title',
                'content',
                'status',
                'created_at',
                'reply_content'
            )
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('backend.contact.index', compact('list'));
    }

    public function show($id)
    {
        $contact = Contact::findOrFail($id);
        return view('backend.contact.show', compact('contact'));
    }


    /**
 * Hiển thị form trả lời liên hệ.
 */
public function reply($id)
{
    $contact = Contact::findOrFail($id);
    return view('backend.contact.reply', compact('contact'));
}

/**
 * Xử lý việc trả lời liên hệ (cập nhật trạng thái là đã trả lời).
 */
public function update(UpdateContactRequest $request, $id)
{
    $contact = Contact::findOrFail($id);
    
    $contact->reply_content = $request->input('reply_content');
    $contact->status = 1; // giả sử 1 = đã trả lời
    $contact->updated_by = Auth::id() ?? 1;
    $contact->save();

    return redirect()->route('contact.index')->with('success', 'Đã trả lời liên hệ');
}

    public function delete($id)
    {
        $contact = Contact::find($id);
        if (!$contact) {
            return redirect()->route('contact.index')->with('error', 'Không tìm thấy contact!');
        }

        $contact->delete(); // soft delete
        return redirect()->route('contact.index')->with('success', 'Contact đã được chuyển vào thùng rác');
    }

    public function trash()
    {
        $contacts = Contact::onlyTrashed()
            ->select('id',
                    'name',
                    'email',
                    'phone',
                    'title',
                    'content',
                    'status',
                    'created_at',
                    'reply_content')
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('backend.contact.trash', compact('contacts'));
    }



    public function restore($id)
    {
        $contact = Contact::onlyTrashed()->find($id);

        $contact->restore();
        return redirect()->route('contact.trash')->with('success', 'Khôi phục contact thành công');
    }


    public function destroy(string $id)
    {
        $contact = Contact::onlyTrashed()->find($id);

        $contact->forceDelete();
        return redirect()->route('contact.trash')->with('success', 'Xóa vĩnh viễn contact thành công');
    }



    public function status($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->status = $contact->status == 1 ? 0 : 1;
        $contact->updated_by = Auth::id() ?? 1;
        $contact->save();

        return redirect()->route('contact.index')->with('success', 'Đã cập nhật trạng thái');
    }
}
