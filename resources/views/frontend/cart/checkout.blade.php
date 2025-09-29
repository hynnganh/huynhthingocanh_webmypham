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

        <!-- Form KHÔNG có method/action sẵn -->
        <form id="checkoutForm">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Thông tin người mua -->
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Tên</label>
                        <input type="text" name="name" id="name" class="w-full p-2 border rounded-md"
                               value="{{ old('name', Auth::user()->name ?? '') }}" required>
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Số điện thoại</label>
                        <input type="text" name="phone" id="phone" class="w-full p-2 border rounded-md"
                               value="{{ old('phone', Auth::user()->phone ?? '') }}" required>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email (Tùy chọn)</label>
                        <input type="email" name="email" id="email" class="w-full p-2 border rounded-md"
                               value="{{ old('email', Auth::user()->email ?? '') }}">
                    </div>
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">Địa chỉ giao hàng</label>
                        <textarea name="address" id="address" class="w-full p-2 border rounded-md" rows="3" required>{{ old('address', Auth::user()->address ?? '') }}</textarea>
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
                        <span id="totalAmount" class="text-gray-800">
                            {{ number_format(array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart))) }} VNĐ
                        </span>
                    </div>
                </div>
            </div>

            <!-- Chọn hình thức thanh toán -->
            <div class="mt-6">
                <label class="block text-gray-700 font-medium mb-2">Chọn hình thức thanh toán:</label>
                <select id="paymentMethod" name="payment_method" class="w-full p-2 border rounded-md">
                    <option value="cod">Thanh toán khi nhận hàng (COD)</option>
                    <option value="bank">Ngân hàng (QR code)</option>
                </select>
            </div>

            <div class="mt-6 flex gap-4">
                <button type="submit" id="btnPay"
                        class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                    Thanh toán
                </button>
            </div>
        </form>

        <!-- Hiển thị QR Code -->
        <div id="qrContainer" class="mt-6 hidden text-center">
            <h3 class="text-lg font-semibold mb-2">Quét mã để thanh toán</h3>
            <img id="qrImage" src="" alt="QR Code" class="border p-2 rounded-lg mx-auto mb-2">
            <div class="font-bold text-xl text-red-600">
                Số tiền: <span id="qrAmount">
                    {{ number_format(array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart))) }} VNĐ
                </span>
            </div>

            <!-- Nút xác nhận chuyển tiền -->
            <div id="confirmContainer" class="mt-4 hidden">
                <button id="btnConfirmPayment" 
                        class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition">
                    Xác nhận đã chuyển tiền
                </button>
            </div>
        </div>
    </main>

    <script>
        const form = document.getElementById('checkoutForm');
        const paymentMethod = document.getElementById('paymentMethod');
        const qrContainer = document.getElementById('qrContainer');
        const qrImage = document.getElementById('qrImage');
        const qrAmount = document.getElementById('qrAmount');
        const confirmContainer = document.getElementById('confirmContainer');
        const btnConfirm = document.getElementById('btnConfirmPayment');
        let orderId = null;

        const totalAmount = parseInt("{{ array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart)) }}");

        form.addEventListener("submit", function (e) {
            e.preventDefault();

            const method = paymentMethod.value;

            if (method === "cod") {
                form.action = "{{ route('cart.storeOrder') }}";
                form.method = "POST";
                form.submit();
            } else {
                fetch("{{ route('cart.storeOrderOnline') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        name: form.name.value,
                        phone: form.phone.value,
                        email: form.email.value,
                        address: form.address.value,
                        note: form.note.value
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.order_id) {
                        orderId = data.order_id;
                        fetch(`/cart/qr-code/${orderId}?method=${method}`)
                        .then(res => res.json())
                        .then(qr => {
                            if (qr.url) {
                                qrImage.src = qr.url;
                                qrAmount.textContent = new Intl.NumberFormat("vi-VN").format(totalAmount) + " VNĐ";
                                qrContainer.classList.remove("hidden");
                                confirmContainer.classList.remove("hidden"); // hiện nút xác nhận
                            } else {
                                alert("Không tạo được QR code, vui lòng thử lại.");
                            }
                        });
                    } else {
                        alert("Tạo đơn hàng thất bại.");
                    }
                })
                .catch(() => alert("Có lỗi xảy ra, vui lòng thử lại."));
            }
        });

        btnConfirm.addEventListener('click', function() {
            if (!orderId) return;

            fetch(`/cart/confirm-payment/${orderId}`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    qrContainer.classList.add("hidden");
                    confirmContainer.classList.add("hidden");
                    window.location.href = "{{ route('cart.index') }}";
                } else {
                    alert(data.error);
                }
            });
        });
    </script>
</x-layout-site>
