<x-layout-site>
    <x-slot:title>
        Thông tin tài khoản
    </x-slot:title>

    <main class="p-8 max-w-4xl mx-auto mt-8 mb-8">
        {{-- Thông tin tài khoản --}}
        <div class="bg-white shadow-2xl rounded-xl border border-pink-100 p-6 mb-8">
            <h2 class="text-3xl font-bold text-pink-600 mb-6 border-b border-pink-200 pb-3">Thông tin tài khoản</h2>

            <div class="flex items-center space-x-6 mb-6">
                <img src="{{ asset('assets/images/user/' . $user->avatar) }}" 
                     alt="Avatar" 
                     class="w-28 h-28 rounded-full object-cover border-4 border-pink-300 shadow-sm">

                <div>
                    <p class="text-2xl font-semibold text-gray-800">{{ $user->name }}</p>
                    <p class="text-md text-gray-500">Tên đăng nhập: {{ $user->username }}</p>
                </div>
            </div>

            <div class="space-y-4 text-lg text-gray-700 leading-relaxed">
                <div>
                    <p class="font-semibold text-pink-600">Email</p>
                    <p class="bg-pink-50 p-3 rounded-md border border-pink-100">{{ $user->email }}</p>
                </div>

                <div>
                    <p class="font-semibold text-pink-600">Số điện thoại</p>
                    <p class="bg-pink-50 p-3 rounded-md border border-pink-100">{{ $user->phone }}</p>
                </div>

                <div>
                    <p class="font-semibold text-pink-600">Địa chỉ</p>
                    <p class="bg-pink-50 p-3 rounded-md border border-pink-100">{{ $user->address }}</p>
                </div>
            </div>
        </div>

        {{-- Danh sách đơn hàng --}}
        <div class="bg-white shadow-2xl rounded-xl border border-pink-100 p-6 mb-8">
            <h3 class="text-2xl font-bold text-pink-600 mb-4 border-b border-pink-200 pb-2">Đơn hàng đã đặt</h3>

            @if($orders->isEmpty())
                <p class="text-gray-500">Bạn chưa có đơn hàng nào.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border border-gray-200 rounded-lg">
                        <thead class="bg-pink-50">
                            <tr>
                                <th class="p-3 border-b">Mã đơn</th>
                                <th class="p-3 border-b">Ngày đặt</th>
                                <th class="p-3 border-b">Trạng thái</th>
                                <th class="p-3 border-b">Phương thức</th>
                                <th class="p-3 border-b">Chi tiết</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr class="hover:bg-gray-50">
                                    <td class="p-3 border-b">{{ $order->id }}</td>
                                    <td class="p-3 border-b">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="border border-gray-300 p-2 text-center">
                                        @switch($order->status)
                                            @case(1) <span class="text-yellow-600">Chờ xác nhận</span> @break
                                            @case(2) <span class="text-blue-600">Đã xác nhận</span> @break
                                            @case(3) <span class="text-orange-600">Đang chuẩn bị hàng</span> @break
                                            @case(4) <span class="text-green-600">Đang giao hàng</span> @break
                                            @case(5) <span class="text-teal-600">Giao thành công</span> @break
                                            @case(6) <span class="text-red-600">Đã hủy</span> @break
                                            @case(7) <span class="text-purple-600">Hoàn trả</span> @break
                                            @case(8) <span class="text-indigo-600">Đổi hàng</span> @break
                                            @case(9) <span class="text-gray-600">Từ chối</span> @break
                                            @case(10) <span class="text-pink-600">Khác</span> @break
                                            @default <span class="text-gray-500">Chưa xác định</span>
                                        @endswitch
                                    </td>
                                    <td class="p-3 border-b">{{ strtoupper($order->payment_method) }}</td>
                                    <td class="p-3 border-b">
                                        <a href="{{ route('account.order.detail', $order->id) }}" class="text-pink-600 hover:underline">Xem</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>


        {{-- Sản phẩm đã đánh giá --}}
        <div class="bg-white shadow-2xl rounded-xl border border-pink-100 p-6">
            <h3 class="text-2xl font-bold text-pink-600 mb-4 border-b border-pink-200 pb-2">Sản phẩm đã đánh giá</h3>

            @if($user->reviews->isEmpty())
                <p class="text-gray-500">Bạn chưa đánh giá sản phẩm nào.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border border-gray-200 rounded-lg">
                        <thead class="bg-pink-50">
                            <tr>
                                <th class="p-3 border-b">Sản phẩm</th>
                                <th class="p-3 border-b">Đánh giá (★)</th>
                                <th class="p-3 border-b">Nội dung</th>
                                <th class="p-3 border-b">Ngày đánh giá</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->reviews as $review)
                                <tr class="hover:bg-gray-50">
                                    <td class="p-3 border-b">{{ $review->product->name ?? 'Sản phẩm đã xóa' }}</td>
                                    <td class="p-3 border-b">{{ $review->rating }}</td>
                                    <td class="p-3 border-b">{{ $review->comment }}</td>
                                    <td class="p-3 border-b">{{ $review->created_at->format('d/m/Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </main>
</x-layout-site>
