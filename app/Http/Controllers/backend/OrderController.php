<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    // Danh sách đơn hàng
    public function index(Request $request)
    {
        $sortBy = $request->get('sortBy', 'created_at');
        $sortType = $request->get('sortType', 'desc');

        $orders = Order::orderBy($sortBy, $sortType)
            ->with('orderDetails.product')
            ->paginate(5);

        return view('backend.order.index', compact('orders', 'sortBy', 'sortType'));
    }

    // Xem chi tiết đơn hàng
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
    // Chuyển vào thùng rác (soft delete)
    public function delete($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('order.index')->with('success', 'Đơn hàng đã được chuyển vào thùng rác.');
    }

    // Danh sách thùng rác
    public function trash()
    {
        $orders = Order::onlyTrashed()->with('orderDetails.product')->orderBy('created_at', 'desc')->paginate(5);
        return view('backend.order.trash', compact('orders'));
    }

    // Khôi phục đơn hàng từ thùng rác
    public function restore($id)
    {
        $order = Order::onlyTrashed()->findOrFail($id);
        $order->restore();

        return redirect()->route('order.trash')->with('success', 'Đơn hàng đã được khôi phục.');
    }

    // Xóa vĩnh viễn
    public function destroy($id)
    {
        $order = Order::onlyTrashed()->findOrFail($id);
        $order->forceDelete();

        return redirect()->route('order.trash')->with('success', 'Đơn hàng đã bị xóa vĩnh viễn.');
    }

    // Cập nhật trạng thái đơn hàng
    public function status(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|integer|between:0,5', // Ví dụ: 0=Chưa thanh toán,1=Đã thanh toán,...
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return redirect()->route('order.index')->with('success', 'Cập nhật trạng thái đơn hàng thành công.');
    }

    // Form cập nhật trạng thái
    public function editStatus($id)
    {
        $order = Order::with('orderDetails.product')->findOrFail($id);
        return view('backend.order.update', compact('order'));
    }

    public function confirmPayment(Order $order)
    {
        if ($order->status == 1) {
            return redirect()->back()->with('error', 'Đơn hàng đã được thanh toán.');
        }

        $order->status = 1; // đánh dấu đã thanh toán
        $order->save();

        return redirect()->back()->with('success', 'Xác nhận thanh toán thành công.');
    }

    
}
