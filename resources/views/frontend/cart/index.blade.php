<x-layout-site>
    <x-slot:title>
        Giỏ hàng
    </x-slot:title>

    <main class="pt-6 pb-2 max-w-5xl mx-auto px-4">
        <h2 class="text-3xl font-bold text-gray-800 mb-8 border-b pb-2">
            <i class="fas fa-shopping-cart text-pink-500 mr-2"></i> Giỏ Hàng Của Bạn
        </h2>

        {{-- Hiển thị thông báo --}}
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
                            $product = \App\Models\Product::find($id);
                            $stock = $product->qty ?? 0;
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;
                        @endphp
                        <tr class="border-b hover:bg-gray-50 {{ $item['quantity'] > $stock ? 'bg-red-100' : '' }}" data-id="{{ $id }}">
                            <td class="py-3 px-4 font-medium">{{ $item['name'] }}</td>
                            <td class="py-3 px-4 text-pink-600 font-semibold">{{ number_format($item['price']) }} VND</td>
                            <td class="py-3 px-4">
                                <input type="number"
                                    value="{{ $item['quantity'] }}"
                                    min="1"
                                    max="{{ $stock }}"
                                    class="border p-2 rounded-md w-16 text-center"
                                    onchange="updateQuantity({{ $id }}, this, {{ $stock }})">
                                @if($item['quantity'] > $stock)
                                    <p class="text-red-600 text-sm mt-1">Chỉ còn {{ $stock }} sản phẩm!</p>
                                @endif
                            </td>
                            <td class="py-3 px-4 subtotal font-semibold text-gray-700">
                                {{ number_format($subtotal) }} VND
                            </td>
                            <td class="py-3 px-4">
                                <form method="POST" action="{{ route('cart.remove') }}" onsubmit="return confirm('Xóa sản phẩm này khỏi giỏ hàng?')">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $id }}">
                                    <button type="submit" class="bg-red-500 text-white p-2 rounded-md hover:bg-red-600 transition">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Tổng cộng --}}
            <div class="mt-6 text-right">
                <p class="font-bold text-xl">Tổng cộng: 
                    <span id="cart-total">{{ number_format($total) }}</span> VNĐ
                </p>
            </div>

            {{-- Kiểm tra vượt kho --}}
            @php
                $hasOverStock = collect($cart)->filter(function($item, $id){
                    $p = \App\Models\Product::find($id);
                    return $item['quantity'] > ($p->qty ?? 0);
                })->isNotEmpty();
            @endphp

            {{-- Nút thao tác --}}
            <div class="mt-6 flex justify-between">
                <form method="POST" action="{{ route('cart.clear') }}" onsubmit="return confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?')">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600 transition">
                        Xóa tất cả
                    </button>
                </form>

                <a href="{{ $hasOverStock ? '#' : route('cart.checkout') }}"
                   class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600 transition {{ $hasOverStock ? 'opacity-50 cursor-not-allowed' : '' }}"
                   @if($hasOverStock)
                       onclick="alert('⚠️ Vui lòng giảm số lượng vượt quá tồn kho trước khi thanh toán!')"
                   @endif>
                    Thanh toán
                </a>
            </div>

            <a href="{{ route('site.home') }}" class="inline-block mt-10 text-blue-500 hover:text-blue-700 transition font-medium">
                <i class="fas fa-chevron-left mr-1"></i> Tiếp tục mua sắm
            </a>

        @else
            <div class="bg-white p-10 rounded-xl shadow-xl text-center">
                <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
                <p class="text-xl text-gray-600 font-medium">Giỏ hàng của bạn hiện tại không có sản phẩm nào.</p>
                <a href="{{ route('site.home') }}" class="mt-6 inline-block bg-pink-500 text-white font-semibold py-2 px-6 rounded-xl hover:bg-pink-600 transition shadow-md">
                    Bắt đầu mua sắm ngay
                </a>
            </div>
        @endif
    </main>

    <script>
        // ✅ Hàm cập nhật số lượng giỏ hàng
        function updateQuantity(id, input, stock) {
            let qty = parseInt(input.value);
            if (qty > stock) {
                alert(`⚠️ Số lượng vượt quá tồn kho (${stock})!`);
                input.value = stock;
                qty = stock;
            }
            if (qty < 1 || isNaN(qty)) qty = 1;

            fetch('{{ route('cart.update') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ id: id, quantity: qty })
            })
            .then(response => {
                if (response.ok) window.location.reload();
            })
            .catch(() => alert('Lỗi khi cập nhật giỏ hàng!'));
        }
    </script>
</x-layout-site>
