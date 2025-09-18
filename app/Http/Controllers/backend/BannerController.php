<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Http\Requests\StoreBannerRequest;
use App\Http\Requests\UpdateBannerRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BannerController extends Controller
{
    public function index()
    {
        $list = Banner::select('banner.id', 'banner.name', 'banner.image','banner.position', 'banner.status', 'banner.created_at')
            ->orderBy('banner.created_at', 'desc')
            ->paginate(5);

        return view('backend.banner.index', compact('list'));
    }

    
    public function show($id)
    {
        $banner = Banner::find($id);
    
        if (!$banner) {
            return redirect()->route('banner.index')->with('error', 'Không tìm thấy danh mục!');
        }
    
        return view('backend.banner.show', compact('banner'));
    }
    

    public function create()
    {
        return view('backend.banner.create');
    }

    public function store(StoreBannerRequest $request)
    {
        $banner = new Banner();
        $banner->name = $request->name;
        $banner->description = $request->description;
        $banner->status = $request->status ?? 1; 
        $banner->position = $request->position;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('assets/images/banner'), $filename);
            $banner->image = $filename;
        } else {
            $banner->image = ''; 
        }

        $banner->created_at = now();
        $banner->created_by = Auth::id() ?? 1;
        $banner->save(); 

        return redirect()->route('banner.index')->with('success', 'Thêm banner thành công');
    }

    public function edit(string $id)
    {
        $banner = Banner::find($id);
        $list_banner = Banner::select('id', 'name')->orderBy('sort_order', 'asc')->get();
        return view('backend.banner.edit', compact('list_banner', 'banner'));
    }

    public function update(UpdateBannerRequest $request, string $id)
    {
        $banner = Banner::find($id);
        $banner->name = $request->name;
        $banner->description = $request->description;
        $slug = Str::slug($request->name);
        $image_path = public_path('assets/images/banner/' . $banner->image);

        if ($request->hasFile('image')) {
            if (File::exists($image_path)) {
                File::delete($image_path);
            }

            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName = $slug . '.' . $extension;
            $file->move(public_path('assets/images/banner'), $fileName);
            $banner->image = $fileName;
        }

        $banner->position = $request->position;
        $banner->status = $request->status;
        $banner->updated_by = Auth::id() ?? 1;
        $banner->updated_at = now();
        $banner->save();

        return redirect()->route('banner.index')->with('success', 'Cập nhật banner thành công');
    }

    public function trash()
    {
        $list = Banner::select('banner.id', 'banner.name', 'banner.image','banner.position', 'banner.status', 'banner.created_at')
            ->orderBy('banner.created_at', 'desc')
            ->onlyTrashed()
            ->paginate(5);
        
        return view('backend.banner.trash', compact('list'));
    }

    public function delete($id)
    {
        $banner = Banner::find($id);
        $banner->delete();
        return redirect()->route('banner.index')->with('success', 'Đã chuyển banner vào thùng rác');
    }

    public function status($id)
    {
        $banner = Banner::find($id);
        $banner->status = ($banner->status == 1) ? 0 : 1;
        $banner->updated_by = Auth::id() ?? 1;
        $banner->updated_at = now();
        $banner->save();

        return redirect()->route('banner.index')->with('success', 'Cập nhật trạng thái banner thành công');
    }

    public function restore($id)
    {
        $banner = Banner::onlyTrashed()->find($id);
        $banner->restore();
        return redirect()->route('banner.trash')->with('success', 'Banner đã được khôi phục');
    }

    public function destroy(string $id)
    {
        $banner = Banner::onlyTrashed()->find($id);
        $image_path = public_path('assets/images/banner/' . $banner->image);

        if ($banner->forceDelete()) {
            if (File::exists($image_path)) {
                File::delete($image_path);
            }
        }

        return redirect()->route('banner.trash')->with('success', 'Banner đã bị xóa vĩnh viễn');
    }
}
