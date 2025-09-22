<x-layout-admin>
    <div class="content-wrapper">
        <div class="border border-blue-100 mb-6 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold text-blue-600">CHI TIẾT ĐƠN HÀNG</h2>
                <a href="{{ route('order.index') }}" class="bg-sky-500 px-4 py-2 rounded-lg text-white hover:bg-sky-600 transition">
                    <i class="fa fa-arrow-left"></i> Quay lại danh sách
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="font-semibold">Họ tên khách hàng:</label>
                    <div class="p-3 border border-gray-300 bg-gray-50 rounded">{{ $order->name }}</div>
                </div>
                <div>
                    <label class="font-semibold">Số điện thoại:</label>
                    <div class="p-3 border border-gray-300 bg-gray-50 rounded">{{ $order->phone }}</div>
                </div>
                <div>
                    <label class="font-semibold">Email:</label>
                    <div class="p-3 border border-gray-300 bg-gray-50 rounded">{{ $order->email }}</div>
                </div>
                <div>
                    <label class="font-semibold">Địa chỉ:</label>
                    <div class="p-3 border border-gray-300 bg-gray-50 rounded">{{ $order->address }}</div>
                </div>
                <div>
                    <label class="font-semibold">Ghi chú:</label>
                    <div class="p-3 border border-gray-300 bg-gray-50 rounded">{{ $order->note ?? 'Không có' }}</div>
                </div>
                <div>
                    <label class="font-semibold">Trạng thái:</label>
                    <div class="p-3 border border-gray-300 bg-gray-50 rounded">
                        @switch($order->status)
                            @case(1)
                                <span class="text-blue-600 font-semibold">Chờ xác nhận</span>
                                @break
                            @case(2)
                                <span class="text-green-600 font-semibold">Đang xử lý</span>
                                @break
                            @case(3)
                                <span class="text-yellow-600 font-semibold">Đang chuẩn bị hàng</span>
                                @break
                            @case(4)
                                <span class="text-orange-600 font-semibold">Đang giao hàng</span>
                                @break
                            @case(5)
                                <span class="text-teal-600 font-semibold">Giao thành công</span>
                                @break
                            @case(6)
                                <span class="text-red-600 font-semibold">Đã hủy</span>
                                @break
                            @case(7)
                                <span class="text-pink-600 font-semibold">Hoàn trả</span>
                                @break
                            @case(8)
                                <span class="text-indigo-600 font-semibold">Đổi hàng</span>
                                @break
                            @case(9)
                                <span class="text-gray-600 font-semibold">Từ chối</span>
                                @break
                            @default
                                <span class="text-gray-500 font-semibold">Chưa xác định</span>
                        @endswitch
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <h3 class="text-xl font-bold mb-4">Chi tiết sản phẩm</h3>
                <table class="w-full border border-gray-300 rounded-lg overflow-hidden">
                    <thead class="bg-gray-200 text-left">
                        <tr>
                            <th class="p-2 border">Ảnh</th>
                            <th class="p-2 border">Sản phẩm</th>
                            <th class="p-2 border">Giá</th>
                            <th class="p-2 border">Số lượng</th>
                            <th class="p-2 border">Tổng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orderDetails as $detail)
                            <tr>
                                <td class="border p-2">
                                    <img src="{{ asset('assets/images/product/' . $detail['product_image']) }}" class="w-16 h-auto rounded">
                                </td>
                                <td class="border p-2">{{ $detail['product_name'] }}</td>
                                <td class="border p-2">{{ number_format($detail['price'], 0, ',', '.') }} đ</td>
                                <td class="border p-2">{{ $detail['quantity'] }}</td>
                                <td class="border p-2 text-red-600 font-semibold">{{ number_format($detail['total'], 0, ',', '.') }} đ</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout-admin>
