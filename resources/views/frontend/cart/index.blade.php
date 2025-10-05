<x-layout-site>
    <x-slot:title>
        Giỏ hàng
    </x-slot:title>

    <main class="p-6">
        <h2 class="text-2xl font-semibold mb-6">Giỏ hàng của bạn</h2>

        @if (session('success'))
            <p class="text-green-600 mb-4">{{ session('success') }}</p>
        @endif

        @if (session('error'))
            <p class="text-red-600 mb-4">{{ session('error') }}</p>
        @endif

        @if(count($cart) > 0)
            <table class="min-w-full bg-white shadow-lg rounded-lg overflow-hidden">
                <thead class="bg-gray-100">
                    <tr class="text-left">
                        <th class="py-3 px-4 border-b">Tên sản phẩm</th>
                        <th class="py-3 px-4 border-b">Giá</th>
                        <th class="py-3 px-4 border-b">Số lượng</th>
                        <th class="py-3 px-4 border-b">Tổng</th>
                        <th class="py-3 px-4 border-b">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach ($cart as $id => $item)
                        @php
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;
                        @endphp
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4">{{ $item['name'] }}</td>
                            <td class="py-3 px-4">{{ number_format($item['price']) }} VND</td>
                            <td class="py-3 px-4">
                            <form action="{{ route('cart.update') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $id }}">
                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" 
                                    max="{{ \App\Models\Product::find($id)->qty ?? 1000 }}" 
                                    class="border p-2 rounded-md w-16 text-center">
                                <button type="submit" class="ml-2 bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600 transition">Cập nhật</button>
                            </form>
                            </td>
                            <td class="py-3 px-4">{{ number_format($subtotal) }} VND</td>
                            <td class="py-3 px-4">
                                <form action="{{ route('cart.remove') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $id }}">
                                    <button type="submit" class="bg-red-500 text-white p-2 rounded-md hover:bg-red-600 transition">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-6 text-right">
                <p class="font-bold text-xl">Tổng cộng: {{ number_format($total) }} VNĐ</p>
            </div>

            <div class="mt-6 flex justify-between">
                <a href="{{ route('cart.clear') }}" class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600 transition">Xóa toàn bộ giỏ hàng</a>
                <a href="{{ route('cart.checkout') }}" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600 transition">Thanh toán</a>
            </div>
        @else
            <p>Giỏ hàng của bạn hiện tại không có sản phẩm nào.</p>
        @endif
    </main>
</x-layout-site>
