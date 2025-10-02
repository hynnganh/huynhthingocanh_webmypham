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
        <div class="bg-white shadow-2xl rounded-xl border border-pink-100 p-6">
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
                                    <td class="p-3 border-b">
                                        @if($order->status == 0)
                                            Chờ xử lý
                                        @elseif($order->status == 1)
                                            Đã thanh toán
                                        @elseif($order->status == 2)
                                            Chờ xác thực
                                        @endif
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
    </main>
</x-layout-site>
