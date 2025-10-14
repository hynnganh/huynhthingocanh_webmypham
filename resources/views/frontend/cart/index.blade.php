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
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;
                        @endphp
                        <tr class="border-b hover:bg-gray-50" data-id="{{ $id }}">
                            <td class="py-3 px-4 font-medium">{{ $item['name'] }}</td>
                            <td class="py-3 px-4 text-pink-600 font-semibold">{{ number_format($item['price']) }} VND</td>
                            <td class="py-3 px-4">
                                <form action="{{ route('cart.update') }}" method="POST" onsubmit="event.preventDefault();">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $id }}">
                                    <input type="number" name="quantity"
                                        value="{{ $item['quantity'] }}"
                                        min="1"
                                        max="{{ \App\Models\Product::find($id)->qty ?? 1000 }}"
                                        class="border p-2 rounded-md w-16 text-center"
                                        oninput="updateQuantity({{ $id }}, this.value)">
                                </form>
                            </td>
                            <td class="py-3 px-4 subtotal font-semibold text-gray-700">
                                {{ number_format($subtotal) }} VND
                            </td>
                            <td class="py-3 px-4">
                                <form method="POST" action="{{ route('cart.remove') }}" onsubmit="event.preventDefault(); openConfirmModal(this);">
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

            <div class="mt-6 text-right">
                <p class="font-bold text-xl">Tổng cộng: <span id="cart-total">{{ number_format($total) }}</span> VNĐ</p>
            </div>

            <div class="mt-6 flex justify-between">
                <form method="POST" action="{{ route('cart.clear') }}" onsubmit="event.preventDefault(); openConfirmModal(this, true);">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600 transition">
                        Xóa tất cả
                    </button>
                </form>
                <a href="{{ route('cart.checkout') }}" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600 transition">
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

    <!-- Modal xác nhận xóa -->
    <div id="confirmModal" class="fixed inset-0 hidden items-center justify-center z-50 bg-black bg-opacity-50 transition-opacity duration-300" onclick="closeConfirmModal()">
        <div class="bg-white rounded-xl p-6 md:p-8 shadow-2xl max-w-sm w-full transform transition-all duration-300 scale-95" onclick="event.stopPropagation()">
            <h3 class="text-xl font-bold text-red-600 mb-4 flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i> Xác Nhận Xóa
            </h3>
            <p id="confirmMessage" class="text-gray-700 mb-6">Bạn có chắc chắn muốn xóa mục này khỏi giỏ hàng không?</p>
            <div class="flex justify-end space-x-4">
                <button onclick="closeConfirmModal()" class="px-5 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition">Hủy</button>
                <button id="confirmDeleteBtn" class="px-5 py-2 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600 transition shadow-md">Xác nhận Xóa</button>
            </div>
        </div>
    </div>

    <script>
        let formToSubmit = null;

        function openConfirmModal(formElement, isClearAll = false) {
            formToSubmit = formElement;
            const modal = document.getElementById('confirmModal');
            const message = document.getElementById('confirmMessage');

            message.textContent = isClearAll
                ? 'Bạn có chắc chắn muốn XÓA TOÀN BỘ giỏ hàng không?'
                : 'Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng không?';

            document.getElementById('confirmDeleteBtn').onclick = function() {
                if (formToSubmit) formToSubmit.submit();
                closeConfirmModal();
            };

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeConfirmModal() {
            const modal = document.getElementById('confirmModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            formToSubmit = null;
        }

        // Tự động cập nhật giá khi thay đổi số lượng
        function updateQuantity(id, newQty) {
            const row = document.querySelector(`tr[data-id="${id}"]`);
            const priceText = row.querySelector('td:nth-child(2)').textContent.replace(/[^\d]/g, '');
            const price = parseInt(priceText);
            const subtotal = price * newQty;

            // Cập nhật subtotal
            row.querySelector('.subtotal').textContent = subtotal.toLocaleString() + ' VND';

            // Cập nhật tổng cộng
            let total = 0;
            document.querySelectorAll('.subtotal').forEach(td => {
                total += parseInt(td.textContent.replace(/[^\d]/g, '')) || 0;
            });
            document.getElementById('cart-total').textContent = total.toLocaleString();

            // Gửi request cập nhật lên server
            fetch('{{ route('cart.update') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ id: id, quantity: newQty })
            }).catch(err => console.error('Lỗi cập nhật giỏ hàng:', err));
        }

        // Đóng modal khi nhấn ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeConfirmModal();
        });
    </script>
</x-layout-site>
