<x-layout-site>
    <x-slot:title>
        Thanh toán
    </x-slot:title>

    <main class="p-6">
        <h2 class="text-2xl font-semibold mb-6">Thanh toán</h2>

        @if (session('error'))
            <p class="text-red-600 mb-4">{{ session('error') }}</p>
        @endif

        @if (session('success'))
            <p class="text-green-600 mb-4">{{ session('success') }}</p>
        @endif

        <form action="{{ route('cart.storeOrder') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Thông tin người mua -->
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Tên</label>
                        <input type="text" name="name" id="name" class="w-full p-2 border rounded-md" value="{{ old('name', Auth::user()->name ?? '') }}" required>
                        @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Số điện thoại</label>
                        <input type="text" name="phone" id="phone" class="w-full p-2 border rounded-md" value="{{ old('phone', Auth::user()->phone ?? '') }}" required>
                        @error('phone') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email (Tùy chọn)</label>
                        <input type="email" name="email" id="email" class="w-full p-2 border rounded-md" value="{{ old('email', Auth::user()->email ?? '') }}">
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">Địa chỉ giao hàng</label>
                        <textarea name="address" id="address" class="w-full p-2 border rounded-md" rows="3" required>{{ old('address', Auth::user()->address ?? '') }}</textarea>
                        @error('address') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="note" class="block text-sm font-medium text-gray-700">Ghi chú (Tùy chọn)</label>
                        <textarea name="note" id="note" class="w-full p-2 border rounded-md" rows="3">{{ old('note') }}</textarea>
                    </div>
                </div>

                <!-- Giỏ hàng -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800">Sản phẩm trong giỏ</h3>
                    <ul class="space-y-2">
                        @foreach ($cart as $item)
                            <li class="flex justify-between items-center py-2 border-b border-gray-200">
                                <span class="text-gray-800">{{ $item['name'] }} ({{ $item['quantity'] }})</span>
                                <span class="text-gray-600">{{ number_format($item['price'] * $item['quantity']) }} VNĐ</span>
                            </li>
                        @endforeach
                    </ul>
                    <div class="flex justify-between font-bold py-2 mt-4 bg-gray-100 rounded-lg">
                        <span class="text-gray-800">Tổng cộng</span>
                        <span class="text-gray-800">{{ number_format(array_sum(array_map(function ($item) { return $item['price'] * $item['quantity']; }, $cart))) }} VNĐ</span>
                    </div>
                </div>
                
            </div>

            <div class="mt-6">
                <button type="submit" class="bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600 transition">Xác nhận thanh toán</button>
            </div>
        </form>
    </main>
</x-layout-site>
