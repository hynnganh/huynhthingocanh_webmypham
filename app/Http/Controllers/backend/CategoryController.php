<?php

namespace App\Http\Controllers\backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Support\Facades\File;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $list = Category::select('category.id', 'category.name', 'category.slug','image', 'category.status')
            ->orderBy('category.created_at', 'desc')
            ->paginate(5);
            
        return view('backend.category.index', compact('list'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    return view('backend.category.create');
}

public function store(StoreCategoryRequest $request)
{
    // Xử lý dữ liệu nhập vào
    $category = new Category();
    $category->name = $request->name;
    $category->slug = Str::of($request->name)->slug('-');
    $category->description = $request->description ?: null;
    $category->parent_id = $request->parent_id ?? 0;
    $category->sort_order = $request->sort_order ?? 0;
    // Upload ảnh
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $extension = $file->getClientOriginalExtension();
        $filename = $category->slug . '.' . $extension;
        $file->move(public_path('assets/images/categories'), $filename);
        $category->image = $filename;
    }
    $category->status = $request->status;
    $category->created_at = date('Y-m-d H:i:s');
    $category->created_by = Auth::id() ?? 1;
    $category->save();
    return redirect()->route('category.index')->with('success', 'Thêm danh mục thành công');
}


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = category::findOrFail($id);
        return view('backend.category.show', compact('category'));
    }

    public function edit(string $id)
    {
        $category = category::find($id);
        $list_category = Category::select('id', 'name')->orderBy('sort_order', 'asc')->get();
        // $list_brand = Brand::select('id', 'name')->orderBy('sort_order', 'asc')->get();
        return view('backend.category.edit', compact('list_category', 'category'));
    }

    
    public function update(UpdateCategoryRequest $request, string $id)
    {
        $category = category::find($id);
        $category->name = $request->name;
        $slug = Str::of($request->name)->slug('-');
        $category->description = $request->description ?: null;
        $category->parent_id = $request->parent_id ?? 0;
        $category->sort_order = $request->sort_order ?? 0;
        $category->slug = $slug;
        $image_path = public_path('assets/images/categories/' . $category->image);

        if ($request->hasFile('image')) {
            if (File::exists($image_path)) {
                File::delete($image_path);
            }

            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName = $slug . '.' . $extension;
            $file->move(public_path('assets/images/categories'), $fileName);
            $category->image = $fileName;
        }

        $category->status = $request->status;
        $category->updated_by = Auth::id() ?? 1;
        $category->updated_at = date('Y-m-d H:i:s');
        $category->save();

        return redirect()->route('category.index')->with('success', 'Cập nhập danh mục thành công');
    }

    public function trash()
    {
        $list = Category::select('category.id', 'category.name', 'category.slug','image', 'category.status')
        ->orderBy('category.created_at', 'desc')
        ->onlyTrashed()
        ->paginate(5);
        
    return view('backend.category.trash', compact('list'));
    }

 
    public function delete($id)
    {
        $category = category::find($id);
        $category->delete(); // soft delete
        return redirect()->route('category.index')->with('success', 'Xóa danh mục thành công');
    }

    
    public function status($id)
    {
        $category = category::find($id);
        $category->status = ($category->status == 1) ? 0 : 1;
        $category->updated_by = Auth::id() ?? 1;
        $category->updated_at = date('Y-m-d H:i:s');
        $category->save(); // lưu cập nhật
        return redirect()->route('category.index')->with('success', 'Thay đổi trạng thái danh mục thành công');

}

    public function restore($id)
    {
        $category = category::onlyTrashed()->find($id);
        $category->restore(); // khôi phục
        return redirect()->route('category.trash')->with('success', 'Khôi phục danh mục thành công');
    }

    public function destroy(string $id)
    {
        $category = category::onlyTrashed()->find($id);
        $image_path = public_path('assets/images/categories/' . $category->image);

        if ($category->forceDelete()) {
            if (File::exists($image_path)) {
                File::delete($image_path);
            }
        }

        return redirect()->route('category.trash')->with('success', 'Danh mục đã được xóa vĩnh viễn');
    }
}
