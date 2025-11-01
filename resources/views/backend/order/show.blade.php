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
                            @case(1)<span class="text-yellow-600 font-semibold">Chờ xác nhận</span>@break
                            @case(2)<span class="text-blue-600 font-semibold">Đang chuẩn bị</span>@break
                            @case(3)<span class="text-orange-600 font-semibold">Đang giao hàng</span>@break
                            @case(4)<span class="text-green-600 font-semibold">Giao thành công</span>@break
                            @case(5)<span class="text-red-600 font-semibold">Đã hủy</span>@break
                            @case(6)<span class="text-purple-600 font-semibold">Trả hàng</span>@break
                        @endswitch
                    </div>
                </div>
            </div>

            <div class="mt-8 overflow-x-auto">
                <h3 class="text-xl font-bold mb-4">Chi tiết sản phẩm</h3>
                <table class="w-full border border-gray-300 rounded-lg overflow-hidden">
                    <thead class="bg-gray-200 text-left">
                        <tr>
                            <th class="p-2 border">Ảnh</th>
                            <th class="p-2 border">Sản phẩm</th>
                            <th class="p-2 border text-center">Giá</th>
                            <th class="p-2 border text-center">Số lượng</th>
                            <th class="p-2 border text-center">Tổng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orderDetails as $detail)
                            <tr class="hover:bg-gray-50">
                                <td class="border p-2">
                                    <img src="{{ asset('assets/images/product/' . $detail['product_image']) }}" class="w-16 h-auto rounded mx-auto">
                                </td>
                                <td class="border p-2">{{ $detail['product_name'] }}</td>
                                <td class="border p-2 text-center">{{ number_format($detail['price'], 0, ',', '.') }} đ</td>
                                <td class="border p-2 text-center">{{ $detail['quantity'] }}</td>
                                <td class="border p-2 text-center text-red-600 font-semibold">{{ number_format($detail['total'], 0, ',', '.') }} đ</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Chỉ hiển thị đánh giá khi đơn đã giao thành công -->
            @if($order->status == 4)
                <div class="mt-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h3 class="text-lg font-bold mb-2">Đánh giá sản phẩm</h3>

                    @foreach($orderDetails as $detail)
                        <div class="mb-4 p-3 border border-gray-200 rounded bg-white">
                            <div class="flex items-center space-x-4">
                                <img src="{{ asset('assets/images/product/' . $detail['product_image']) }}" class="w-12 h-auto rounded">
                                <div>
                                    <p class="font-semibold">{{ $detail['product_name'] }}</p>
                                    @if($detail['review'])
                                        <p class="text-yellow-500">⭐ {{ $detail['review']->rating }}/5</p>
                                        <p>{{ $detail['review']->comment }}</p>
                                        @if($detail['review']->image)
                                            <img src="{{ asset($detail['review']->image) }}" class="w-24 mt-2 rounded">
                                        @endif
                                    @else
                                        <p class="text-gray-400 italic">Chưa có đánh giá</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Hiển thị lý do trả hàng nếu status = 6 -->
            @if($order->status == 6)
    <div class="mt-6 bg-red-50 p-4 rounded-lg border border-red-200">
        <h3 class="text-lg font-bold mb-2 text-red-600">Lý do trả hàng</h3>
        <p>{{ $order->note ?? 'Không có' }}</p>

        @php
            // Tên file ảnh trả hàng nếu có
            $returnImage = 'assets/images/returns/' . ($order->return_image ?? null);
        @endphp

        @if(isset($order->return_image) && file_exists(public_path($returnImage)))
            <img src="{{ asset($returnImage) }}" class="w-32 mt-2 rounded">
        @endif
    </div>
@endif


        </div>
    </div>
</x-layout-admin>
