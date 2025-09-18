<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    // Danh sách người dùng
    public function index()
    {
        $list = User::select('user.id', 'user.name', 'user.email', 'user.phone','user.roles', 'user.username', 'user.address', 'user.avatar', 'user.status', 'user.created_at')
            ->orderBy('user.created_at', 'desc')
            ->paginate(5);

        return view('backend.user.index', compact('list'));
    }
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('backend.user.show', compact('user'));
    }
    // Hiển thị form tạo mới
    public function create()
    {
        return view('backend.user.create');
    }

    // Lưu người dùng mới
    public function store(StoreUserRequest $request)
{
    $user = new User();
    $user->name = $request->name;
    $user->username = $request->username;
    $user->email = $request->email;
    $user->phone = $request->phone;
    $user->address = $request->address;

    $allowedRoles = ['customer', 'admin'];
    $user->roles = in_array($request->roles, $allowedRoles) ? $request->roles : 'customer';

    $user->password = bcrypt($request->password);
    $slug = Str::slug($request->name);

    if ($request->hasFile('avatar')) {
        $file = $request->file('avatar');
        if ($file->isValid()) {
            $extension = $file->getClientOriginalExtension();
            $filename = $slug . '-' . time() . '.' . $extension;
            $file->move(public_path('assets/images/user'), $filename);
            $user->avatar = $filename;
        } else {
            return back()->with('error', 'Tập tin ảnh không hợp lệ!');
        }
    } else {
        $user->avatar = 'default.png';
    }

    $user->status = 1;
    $user->created_at = now();
    $user->created_by = Auth::id() ?? 1;

    $user->save();

    return redirect()->route('user.index')->with('success', 'Thêm người dùng thành công!');
}


    // Hiển thị form chỉnh sửa
    public function edit($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('user.index')->with('error', 'Người dùng không tồn tại!');
        }

        return view('backend.user.edit', compact('user'));
    }

    // Cập nhật người dùng
    public function update(UpdateUserRequest $request, $id)
{
    $user = User::find($id);
    if (!$user) {
        return redirect()->route('user.index')->with('error', 'Người dùng không tồn tại!');
    }

    $user->name = $request->name;
    $user->username = $request->username;
    $user->email = $request->email;
    $user->phone = $request->phone;
    $user->address = $request->address;

    $allowedRoles = ['customer', 'admin'];
    $user->roles = in_array($request->roles, $allowedRoles) ? $request->roles : 'customer';

    if ($request->filled('password')) {
        $user->password = bcrypt($request->password);
    }

    $slug = Str::slug($request->name);

    if ($request->hasFile('avatar')) {
        $file = $request->file('avatar');
        if ($file->isValid()) {
            $oldImage = public_path('assets/images/user/' . $user->avatar);
            if ($user->avatar && File::exists($oldImage)) {
                File::delete($oldImage);
            }

            $extension = $file->getClientOriginalExtension();
            $filename = $slug . '-' . time() . '.' . $extension;
            $file->move(public_path('assets/images/user'), $filename);
            $user->avatar = $filename;
        } else {
            return back()->with('error', 'Tập tin ảnh không hợp lệ!');
        }
    }

    $user->status = 1;
    $user->updated_at = now();
    $user->updated_by = Auth::id() ?? 1;

    $user->save();

    return redirect()->route('user.index')->with('success', 'Cập nhật người dùng thành công!');
}


    // Chuyển người dùng vào thùng rác
    public function delete($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('user.index')->with('error', 'Người dùng không tồn tại!');
        }

        $user->delete();
        return redirect()->route('user.index')->with('success', 'Đã chuyển vào thùng rác!');
    }

    // Danh sách người dùng bị xóa mềm
    public function trash()
    {
        $list = User::onlyTrashed()
            ->select('user.id', 'user.name', 'user.email', 'user.phone','user.roles', 'user.username', 'user.address', 'user.avatar', 'user.status', 'user.created_at')
            ->orderBy('user.created_at', 'desc')
            ->paginate(5);

        return view('backend.user.trash', compact('list'));
    }

    // Khôi phục người dùng
    public function restore($id)
    {
        $user = User::onlyTrashed()->find($id);
        if (!$user) {
            return redirect()->route('user.trash')->with('error', 'Người dùng không tồn tại!');
        }

        $user->restore();
        return redirect()->route('user.trash')->with('success', 'Khôi phục người dùng thành công!');
    }

    // Xóa vĩnh viễn
    public function destroy($id)
    {
        $user = User::onlyTrashed()->find($id);
        if (!$user) {
            return redirect()->route('user.trash')->with('error', 'Người dùng không tồn tại!');
        }

        $image_path = public_path('assets/images/user/' . $user->avatar);

        if ($user->forceDelete()) {
            if ($user->avatar && File::exists($image_path)) {
                File::delete($image_path);
            }
        }

        return redirect()->route('user.trash')->with('success', 'Đã xóa vĩnh viễn người dùng!');
    }

    // Thay đổi trạng thái hoạt động
    public function status($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('user.index')->with('error', 'Người dùng không tồn tại!');
        }

        $user->status = ($user->status == 1) ? 0 : 1;
        $user->updated_by = Auth::id() ?? 1;
        $user->updated_at = now();
        $user->save();

        return redirect()->route('user.index')->with('success', 'Cập nhật trạng thái thành công!');
    }
}
