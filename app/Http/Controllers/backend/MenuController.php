<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    public function index()
    {
        $list = Menu::select('id', 'name', 'link', 'table_id', 'position', 'parent_id', 'type', 'status')
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('backend.menu.index', compact('list'));
    }


    public function show($id)
    {
        $menu = Menu::findOrFail($id);

        return view('backend.menu.show', compact('menu'));
    }


    public function create()
    {
        $menuTypes = [
            'custom' => 'custom',
            'category' => 'category',
            'brand' => 'brand',
            'page' => 'page',
            'topic' => 'topic',
        ];

        $menus = Menu::where('status', 1)->get();

        return view('backend.menu.create', compact('menuTypes', 'menus'));
    }

    public function store(StoreMenuRequest $request)
    {
        $menu = new Menu();
        $menu->name = $request->name;
        $menu->link = $request->link;
        $menu->table_id = $request->table_id ?? 0;
        $menu->parent_id = $request->parent_id ?? 0;
        $menu->type = $request->type ?? 'custom';
        $menu->status = $request->status;
        $menu->created_by = Auth::id() ?? 1;
        $menu->created_at = date('Y-m-d H:i:s');
        $menu->position = $request->position ?? 'mainmenu';
        $menu->save();

        return redirect()->route('menu.index')->with('success', 'Thêm menu thành công');
    }

    public function edit($id)
    {
        $menu = Menu::find($id);
        if (!$menu) {
            return redirect()->route('menu.index')->with('error', 'Không tìm thấy menu!');
        }

        $menuTypes = [
            'custom' => 'custom',
            'category' => 'category',
            'brand' => 'brand',
            'page' => 'page',
            'topic' => 'topic',
        ];

        $menus = Menu::where('status', 1)->where('id', '!=', $id)->get();

        return view('backend.menu.edit', compact('menu', 'menuTypes', 'menus'));
    }

    public function update(UpdateMenuRequest $request, $id)
    {
        $menu = Menu::find($id);
        if (!$menu) {
            return redirect()->route('menu.index')->with('error', 'Không tìm thấy menu!');
        }

        $menu->name = $request->name;
        $menu->link = $request->link;
        $menu->table_id = $request->table_id ?? 0;
        $menu->parent_id = $request->parent_id ?? 0;
        $menu->type = $request->type ?? 'custom';
        $menu->status = $request->status;
        $menu->updated_by = Auth::id() ?? 1;
        $menu->updated_at = date('Y-m-d H:i:s');
        $menu->position = $request->position ?? 'mainmenu';
        $menu->save();

        return redirect()->route('menu.index')->with('success', 'Cập nhật menu thành công');
    }

    public function delete($id)
    {
        $menu = Menu::find($id);
        if (!$menu) {
            return redirect()->route('menu.index')->with('error', 'Không tìm thấy menu!');
        }

        $menu->delete(); // soft delete
        return redirect()->route('menu.index')->with('success', 'Menu đã được chuyển vào thùng rác');
    }

    public function trash()
    {
        $list = Menu::onlyTrashed()
            ->select('id', 'name', 'link', 'table_id', 'position','parent_id', 'type', 'status')
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('backend.menu.trash', compact('list'));
    }

    public function restore($id)
    {
        $menu = Menu::onlyTrashed()->find($id);
        if (!$menu) {
            return redirect()->route('menu.trash')->with('error', 'Không tìm thấy menu để khôi phục!');
        }

        $menu->restore();
        return redirect()->route('menu.trash')->with('success', 'Khôi phục menu thành công');
    }

    public function destroy(string $id)
    {
        $menu = Menu::onlyTrashed()->find($id);
        if (!$menu) {
            return redirect()->route('menu.trash')->with('error', 'Không tìm thấy menu để xoá vĩnh viễn!');
        }

        $menu->forceDelete();
        return redirect()->route('menu.trash')->with('success', 'Xóa vĩnh viễn menu thành công');
    }

    public function status($id)
    {
        $menu = Menu::find($id);
        if (!$menu) {
            return redirect()->route('menu.index')->with('error', 'Không tìm thấy menu!');
        }

        $menu->status = ($menu->status == 1) ? 0 : 1;
        $menu->updated_by = Auth::id() ?? 1;
        $menu->updated_at = date('Y-m-d H:i:s');
        $menu->save();

        return redirect()->route('menu.index')->with('success', 'Cập nhật trạng thái thành công');
    }
}
