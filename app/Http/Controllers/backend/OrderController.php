<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
{
    $sortBy = $request->get('sortBy', 'created_at');
    $sortType = $request->get('sortType', 'desc');

    $list = Order::orderBy($sortBy, $sortType)->paginate(5);

    // Lấy thông tin chi tiết đơn hàng
    foreach ($list as $order) {
        $order->details = $order->orderDetails->map(function($detail) {
            return [
                'product_name' => $detail->product->name,
                'product_image' => $detail->product->thumbnail, // Đổi thành trường thumbnail
                'price' => $detail->price_buy,
                'quantity' => $detail->qty,
                'total' => $detail->amount
            ];
        });
    }

    return view('backend.order.index', compact('list', 'sortBy', 'sortType'));
}



    public function delete($id)
    {
        $order = Order::findOrFail($id);
        $order->delete(); // soft delete
        return redirect()->route('order.index')->with('success', 'Đơn hàng đã được chuyển vào thùng rác.');
    }
    public function show($id)
{
    $order = Order::with('orderDetails.product')->findOrFail($id); 
    
    $orderDetails = $order->orderDetails->map(function($detail) {
        return [
            'product_name' => $detail->product->name,
            'product_image' => $detail->product->thumbnail, 
            'price' => $detail->price_buy,
            'quantity' => $detail->qty,
            'total' => $detail->amount
        ];
    });

    return view('backend.order.show', compact('order', 'orderDetails'));
}


    public function trash()
    {
        $list = Order::onlyTrashed()->orderBy('created_at', 'desc')->paginate(5);
        return view('backend.order.trash', compact('list'));
    }

    public function restore($id)
    {
        $order = Order::onlyTrashed()->findOrFail($id);
        $order->restore();
        return redirect()->route('order.trash')->with('success', 'Đơn hàng đã được khôi phục.');
    }

    public function destroy($id)
    {
        $order = Order::onlyTrashed()->findOrFail($id);
        $order->forceDelete();
        return redirect()->route('order.trash')->with('success', 'Đơn hàng đã bị xóa vĩnh viễn.');
    }

    public function status(Request $request, $id)
{
    $request->validate([
        'status' => 'required|integer|between:1,10',
    ]);

    $order = Order::findOrFail($id);
    $order->status = $request->status;
    $order->save();

    return redirect()->route('order.index')->with('success', 'Cập nhật trạng thái đơn hàng thành công.');
}
public function editStatus($id)
{
    $order = Order::with('orderDetails.product')->findOrFail($id);    
    return view('backend.order.update', compact('order'));
}
}
