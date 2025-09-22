<x-layout-admin>
    <div class="content-wrapper">
        <div class="border border-blue-100 mb-6 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold text-blue-600">CẬP NHẬT TRẠNG THÁI ĐƠN HÀNG</h2>
                <a href="{{ route('order.index') }}" class="bg-sky-500 px-4 py-2 rounded-lg text-white hover:bg-sky-600 transition">
                    <i class="fa fa-arrow-left"></i> Quay lại danh sách
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('order.status', ['order' => $order->id]) }}" method="POST">
                @csrf
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


                </div>

                <div class="mt-6">
                    <label class="font-semibold">Cập nhật trạng thái:</label>
                    <select name="status" class="w-full p-3 border border-gray-300 bg-gray-50 rounded-md">
                        @foreach ([
                            1 => 'Chờ xác nhận',
                            2 => 'Đã xác nhận',
                            3 => 'Đang chuẩn bị hàng',
                            4 => 'Đang giao hàng',
                            5 => 'Giao thành công',
                            6 => 'Đã hủy',
                            7 => 'Hoàn trả',
                            8 => 'Đổi hàng',
                            9 => 'Từ chối',
                            10 => 'Khác'
                        ] as $value => $label)
                            <option value="{{ $value }}" {{ $order->status == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-300">
                        Cập nhật trạng thái
                    </button>
                </div>
            </form>

            <!-- Hiển thị chi tiết sản phẩm -->
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
                        @foreach($order->orderDetails as $detail)
                            <tr>
                                <td class="border p-2">
                                    <img src="{{ asset('assets/images/product/' . $detail->product->thumbnail) }}" class="w-16 h-auto rounded">
                                </td>
                                <td class="border p-2">{{ $detail->product->name }}</td>
                                <td class="border p-2">{{ number_format($detail->price_buy, 0, ',', '.') }} đ</td>
                                <td class="border p-2">{{ $detail->qty }}</td>
                                <td class="border p-2 text-red-600 font-semibold">{{ number_format($detail->amount, 0, ',', '.') }} đ</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout-admin>
