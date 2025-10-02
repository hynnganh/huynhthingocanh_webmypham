<x-layout-site>
    <x-slot:title>Chi tiết đơn hàng #{{ $order->id }}</x-slot:title>

    <main class="container mx-auto mt-8 mb-8 max-w-4xl">
        {{-- Thông tin đơn hàng --}}
        <div class="bg-white shadow-2xl rounded-xl border border-pink-100 p-6 mb-8">
            <h2 class="text-3xl font-bold text-pink-600 mb-6 border-b border-pink-200 pb-3">
                Chi tiết đơn hàng #{{ $order->id }}
            </h2>

            <div class="space-y-2 text-gray-700">
                <p><span class="font-semibold text-pink-600">Tên khách hàng:</span> {{ $order->name }}</p>
                <p><span class="font-semibold text-pink-600">Email:</span> {{ $order->email }}</p>
                <p><span class="font-semibold text-pink-600">SĐT:</span> {{ $order->phone }}</p>
                <p><span class="font-semibold text-pink-600">Địa chỉ:</span> {{ $order->address }}</p>
                <p><span class="font-semibold text-pink-600">Ghi chú:</span> {{ $order->note ?? '-' }}</p>
                <p><span class="font-semibold text-pink-600">Phương thức thanh toán:</span> {{ strtoupper($order->payment_method) }}</p>
                <p><span class="font-semibold text-pink-600">Trạng thái:</span>
                    @if($order->status == 0)
                        Chờ xử lý
                    @elseif($order->status == 1)
                        Đã thanh toán
                    @elseif($order->status == 2)
                        Chờ xác thực
                    @endif
                </p>
                @if($order->payment_proof)
                    <p><span class="font-semibold text-pink-600">Chứng từ thanh toán:</span>
                        <a href="{{ asset($order->payment_proof) }}" target="_blank" class="text-blue-600 underline">Xem</a>
                    </p>
                @endif
            </div>
        </div>

        {{-- Danh sách sản phẩm trong đơn --}}
        <div class="bg-white shadow-2xl rounded-xl border border-pink-100 p-6">
            <h3 class="text-2xl font-bold text-pink-600 mb-4 border-b border-pink-200 pb-2">Sản phẩm trong đơn</h3>

            <div class="overflow-x-auto">
                <table class="w-full text-left border border-gray-200 rounded-lg">
                    <thead class="bg-pink-50">
                        <tr>
                            <th class="p-3 border-b">Sản phẩm</th>
                            <th class="p-3 border-b">Hình ảnh</th>
                            <th class="p-3 border-b">Số lượng</th>
                            <th class="p-3 border-b">Giá</th>
                            <th class="p-3 border-b">Tổng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orderDetails as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="p-3 border-b">{{ $item['product_name'] }}</td>
                                <td class="p-3 border-b">
                                    <img src="{{ asset('assets/images/product/' . $item['product_image']) }}" 
                                         alt="{{ $item['product_name'] }}" 
                                         class="w-16 h-16 object-cover rounded">
                                </td>
                                <td class="p-3 border-b">{{ $item['quantity'] }}</td>
                                <td class="p-3 border-b">{{ number_format($item['price'],0,',','.') }}₫</td>
                                <td class="p-3 border-b">{{ number_format($item['total'],0,',','.') }}₫</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Tổng đơn hàng --}}
            <div class="mt-4 text-right text-lg font-semibold text-gray-800">
                Tổng đơn hàng: {{ number_format(collect($orderDetails)->sum('total'),0,',','.') }}₫
            </div>
        </div>
    </main>
</x-layout-site>
