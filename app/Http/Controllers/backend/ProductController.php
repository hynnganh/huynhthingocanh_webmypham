<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\backend\ProductImport;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    
    public function index()
    {
        $list = Product::select('product.id', 'product.name', 'category.name as categoryname', 'brand.name as brandname', 'thumbnail', 'product.status')
            ->join('category', 'product.category_id', '=', 'category.id')
            ->join('brand', 'product.brand_id', '=', 'brand.id') 
            ->orderBy('product.created_at', 'desc')
            ->paginate(5);
        return view('backend.product.index', compact('list'));
    }

    
    public function create()
    {
        $list_category = Category::all();
        $list_brand = Brand::all();
        return view('backend.product.create', compact('list_category', 'list_brand'));
    }

   
    public function store(StoreProductRequest $request)
    {
        $product = new Product();
        $product->name = $request->name;
        $product->slug = Str::of($request->name)->slug('-');
        $product->detail = $request->detail;
        $product->description = $request->description;
        $product->price_root = $request->price_root;
        $product->price_sale = $request->price_sale;
        $product->qty = $request->qty;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        // upload file
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $extension = $file->getClientOriginalExtension();
            $filename = $product->slug . '.' . $extension;
            $file->move(public_path('assets/images/product'), $filename);
            $product->thumbnail = $filename;
        }
        // end upload file

        $product->status = $request->status;
        $product->created_at = date('Y-m-d H:i:s');
        $product->created_by = Auth::id() ?? 1;
        $product->save();
        return redirect()->route('product.index')->with('success', 'Thêm sản phẩm thành công!');    }


    public function show($id)
    {
        $product = Product::with(['category', 'brand'])->findOrFail($id);
        return view('backend.product.show', compact('product'));
    }
        

   
    public function edit(string $id)
    {
        $product = Product::find($id);
        $list_category = Category::select('id', 'name')->orderBy('sort_order', 'asc')->get();
        $list_brand = Brand::select('id', 'name')->orderBy('sort_order', 'asc')->get();
        return view('backend.product.edit', compact('list_category', 'list_brand', 'product'));
    }

    
    public function update(UpdateProductRequest $request, string $id)
    {
        $product = Product::find($id);
        $slug = Str::of($request->name)->slug('-');
        $product->name = $request->name;
        $product->slug = $slug;
        $product->detail = $request->detail;
        $product->description = $request->description;
        $product->price_root = $request->price_root;
        $product->price_sale = $request->price_sale;
        $product->qty = $request->qty;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        $image_path = public_path('assets/images/product/' . $product->thumbnail);

        if ($request->hasFile('thumbnail')) {
            if (File::exists($image_path)) {
                File::delete($image_path);
            }

            $file = $request->file('thumbnail');
            $extension = $file->getClientOriginalExtension();
            $fileName = $slug . '.' . $extension;
            $file->move(public_path('assets/images/product'), $fileName);
            $product->thumbnail = $fileName;
        }

        $product->status = $request->status;
        $product->updated_by = Auth::id() ?? 1;
        $product->updated_at = date('Y-m-d H:i:s');
        $product->save();

        return redirect()->route('product.index')->with('success', 'Cập nhật sản phẩm thành công!');    }

    public function trash()
    {
        $list = Product::select('product.id', 'thumbnail', 'product.name', 'category.name as categoryname', 'brand.name as brandname', 'product.status')
            ->join('category', 'product.category_id', '=', 'category.id')
            ->join('brand', 'product.brand_id', '=', 'brand.id')
            ->orderBy('product.created_at', 'desc')
            ->onlyTrashed()
            ->paginate(5);
        return view('backend.product.trash', compact('list'));
    }

 
public function delete($id)
{
    $product = Product::find($id);

    if (!$product) {
        return redirect()->route('product.index')->with('error', 'Sản phẩm không tồn tại!');
    }

    // Kiểm tra sản phẩm có nằm trong chi tiết đơn hàng không
    $hasOrderDetails = \DB::table('orderdetail')->where('product_id', $id)->exists();

    if ($hasOrderDetails) {
        return redirect()->route('product.index')->with('error', 'Không thể xóa vì sản phẩm đã có trong đơn hàng!');
    }

    $product->delete(); // Soft delete
    return redirect()->route('product.index')->with('success', 'Sản phẩm đã được xóa!');
}


    
    public function status($id)
    {
        $product = Product::find($id);
        $product->status = ($product->status == 1) ? 0 : 1;
        $product->updated_by = Auth::id() ?? 1;
        $product->updated_at = date('Y-m-d H:i:s');
        $product->save(); // lưu cập nhật
        return redirect()->route('product.index')->with('success', 'Sản phẩm đã được cập nhập!');

}

    public function restore($id)
    {
        $product = Product::onlyTrashed()->find($id);
        $product->restore(); // khôi phục
        return redirect()->route('product.trash')->with('success', 'Sản phẩm đã được khôi phục!');    }

    public function destroy(string $id)
    {
        $product = Product::onlyTrashed()->find($id);
        $image_path = public_path('assets/images/product/' . $product->thumbnail);

        if ($product->forceDelete()) {
            if (File::exists($image_path)) {
                File::delete($image_path);
            }
        }

        return redirect()->route('product.trash')->with('success', 'Sản phẩm đã được xóa vĩnh viễn!');  
    }

    // Hiển thị danh sách tồn kho
    public function inventory()
    {
        $products = Product::all(); // hoặc paginate nếu nhiều sản phẩm
        return view('backend.inventory.index', compact('products'));
    }

    // Cập nhật tồn kho
    public function updateInventory(Request $request, Product $product)
    {
        $request->validate([
            'qty' => 'required|integer|min:0'
        ]);

        $product->qty = $request->qty;
        $product->save();

        return redirect()->route('inventory.index')->with('success', "Cập nhật tồn kho sản phẩm '{$product->name}' thành công!");
    }

     public function import(Request $request)
{
    Excel::import(new ProductImport, $request->file('file'));
    return back()->with('success', 'Nhập sản phẩm thành công!');
}
}