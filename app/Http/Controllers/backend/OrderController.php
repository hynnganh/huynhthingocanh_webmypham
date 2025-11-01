<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\ProductReview;

class OrderController extends Controller
{
    // -------------------
    // DANH SÁCH ĐƠN HÀNG
    // -------------------
    public function index(Request $request)
    {
        $sortBy = $request->get('sortBy', 'created_at');
        $sortType = $request->get('sortType', 'desc');

        $orders = Order::with('orderDetails.product', 'user')
                    ->orderBy($sortBy, $sortType)
                    ->paginate(10);

        return view('backend.order.index', compact('orders', 'sortBy', 'sortType'));
    }

    // -------------------
    // CHI TIẾT ĐƠN HÀNG
    // -------------------
    public function show($id)
    {
        $order = Order::with(['orderDetails.product', 'user'])->findOrFail($id);

        $orderDetails = $order->orderDetails->map(function($detail) use ($order) {
            $review = $detail->product->reviews()
                            ->where('user_id', $order->user_id)
                            ->first(); // review của user này
            return [
                'product_name' => $detail->product->name,
                'product_image' => $detail->product->thumbnail,
                'price' => $detail->price_buy,
                'quantity' => $detail->qty,
                'total' => $detail->amount,
                'note' => $detail->note,
                'review' => $review,
            ];
        });

        // Nếu status = 6 thì xem lý do trả hàng trong note
        $orderReturn = $order->status == 6 ? $order->note : null;

        return view('backend.order.show', compact('order', 'orderDetails', 'orderReturn'));
    }

    // -------------------
    // CHUYỂN VÀO THÙNG RÁC
    // -------------------
    public function delete($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('admin.order.index')->with('success', 'Đơn hàng đã được chuyển vào thùng rác.');
    }

    // -------------------
    // DANH SÁCH THÙNG RÁC
    // -------------------
    public function trash()
    {
        $orders = Order::onlyTrashed()->with('orderDetails.product', 'user')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
        return view('backend.order.trash', compact('orders'));
    }

    // -------------------
    // KHÔI PHỤC ĐƠN HÀNG
    // -------------------
    public function restore($id)
    {
        $order = Order::onlyTrashed()->findOrFail($id);
        $order->restore();

        return redirect()->route('admin.order.trash')->with('success', 'Đơn hàng đã được khôi phục.');
    }

    // -------------------
    // XÓA VĨNH VIỄN
    // -------------------
    public function destroy($id)
    {
        $order = Order::onlyTrashed()->findOrFail($id);
        $order->forceDelete();

        return redirect()->route('admin.order.trash')->with('success', 'Đơn hàng đã bị xóa vĩnh viễn.');
    }

    // -------------------
    // CẬP NHẬT TRẠNG THÁI ĐƠN HÀNG
    // -------------------
    public function status(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|integer|between:0,6',
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return redirect()->route('admin.order.index')->with('success', 'Cập nhật trạng thái đơn hàng thành công.');
    }

    // -------------------
    // FORM CẬP NHẬT TRẠNG THÁI
    // -------------------
    public function editStatus($id)
    {
        $order = Order::with('orderDetails.product')->findOrFail($id);
        return view('backend.order.update', compact('order'));
    }

    // -------------------
    // XÁC NHẬN THANH TOÁN
    // -------------------
    public function confirmPayment(Order $order)
    {
        if ($order->status == 1) {
            return redirect()->back()->with('error', 'Đơn hàng đã được thanh toán.');
        }

        $order->status = 1;
        $order->save();

        return redirect()->back()->with('success', 'Xác nhận thanh toán thành công.');
    }

}
