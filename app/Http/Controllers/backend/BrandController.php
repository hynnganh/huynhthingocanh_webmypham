<?php

namespace App\Http\Controllers\backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use Illuminate\Support\Facades\File;


class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $list = Brand::select('brand.id', 'brand.name', 'brand.slug','image', 'brand.status')
            ->orderBy('brand.created_at', 'desc')
            ->paginate(5);
            
        return view('backend.brand.index', compact('list'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    return view('backend.brand.create');
}

public function store(StoreBrandRequest $request)
{
    // Xử lý dữ liệu nhập vào
    $brand = new Brand();
    $brand->name = $request->name;
    $brand->slug = Str::of($request->name)->slug('-');
    $brand->description = $request->description ?: null;
    $brand->sort_order = $request->sort_order ?? 0;
    // Upload ảnh
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $extension = $file->getClientOriginalExtension();
        $filename = $brand->slug . '.' . $extension;
        $file->move(public_path('assets/images/brand'), $filename);
        $brand->image = $filename;
    }
    $brand->status = $request->status;
    $brand->created_at = date('Y-m-d H:i:s');
    $brand->created_by = Auth::id() ?? 1;
    $brand->save();
    return redirect()->route('brand.index')->with('success', 'Thêm thương hiệu thành công');}


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $brand = Brand::findOrFail($id);
        return view('backend.brand.show', compact('brand'));
    }


    public function edit(string $id)
    {
        $brand = Brand::find($id);
        $list_brand = Brand::select('id', 'name')->orderBy('sort_order', 'asc')->get();
        // $list_brand = Brand::select('id', 'name')->orderBy('sort_order', 'asc')->get();
        return view('backend.brand.edit', compact('list_brand', 'brand'));
    }

    
    public function update(UpdateBrandRequest $request, string $id)
    {
        $brand = Brand::find($id);
        $brand->name = $request->name;
        $slug = Str::of($request->name)->slug('-');
        $brand->description = $request->description ?: null;
        $brand->sort_order = $request->sort_order ?? 0;
        $brand->slug = $slug;
        $image_path = public_path('assets/images/brand/' . $brand->image);

        if ($request->hasFile('image')) {
            if (File::exists($image_path)) {
                File::delete($image_path);
            }

            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName = $slug . '.' . $extension;
            $file->move(public_path('assets/images/brand'), $fileName);
            $brand->image = $fileName;
        }

        $brand->status = $request->status;
        $brand->updated_by = Auth::id() ?? 1;
        $brand->updated_at = date('Y-m-d H:i:s');
        $brand->save();

        return redirect()->route('brand.index')->with('success', 'Cập nhập thương hiệu thành công');    }

    public function trash()
    {
        $list = Brand::select('brand.id', 'brand.name', 'brand.slug','image', 'brand.status')
        ->orderBy('brand.created_at', 'desc')
        ->onlyTrashed()
        ->paginate(5);
        
    return view('backend.brand.trash', compact('list'));
    }

 
    public function delete($id)
    {
        $brand = Brand::find($id);
        $brand->delete(); // soft delete
        return redirect()->route('brand.index')->with('success', 'Xóa thương hiệu thành công');    }

    
    public function status($id)
    {
        $brand = Brand::find($id);
        $brand->status = ($brand->status == 1) ? 0 : 1;
        $brand->updated_by = Auth::id() ?? 1;
        $brand->updated_at = date('Y-m-d H:i:s');
        $brand->save(); // lưu cập nhật
        return redirect()->route('brand.index')->with('success', 'Cập nhập trạng thái thương hiệu thành công');
}

    public function restore($id)
    {
        $brand = Brand::onlyTrashed()->find($id);
        $brand->restore(); // khôi phục
        return redirect()->route('brand.trash')->with('success', 'Khôi phục thương hiệu thành công');
    }

    public function destroy(string $id)
    {
        $brand = Brand::onlyTrashed()->find($id);
        $image_path = public_path('assets/images/brand/' . $brand->image);

        if ($brand->forceDelete()) {
            if (File::exists($image_path)) {
                File::delete($image_path);
            }
        }

        return redirect()->route('brand.trash')->with('success', 'Thương hiệu đã được xóa vĩnh viễn');
    }
}
