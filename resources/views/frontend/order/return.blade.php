<x-layout-site>
    <x-slot:title>Yêu cầu trả hàng #{{ $order->id }}</x-slot:title>

    <main class="container mx-auto mt-10 mb-12 max-w-3xl p-4">
        <h1 class="text-3xl font-bold text-pink-600 mb-6 text-center">
            🛍️ Yêu cầu trả hàng
        </h1>

        <form action="{{ route('order.return.submit', $order->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="bg-white border border-pink-100 rounded-xl shadow-md p-6">
                <p class="text-lg font-semibold mb-3">Lý do trả hàng <span class="text-red-500">*</span></p>
                <textarea name="reason" rows="4" class="w-full border border-pink-200 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-pink-400" placeholder="Nhập lý do trả hàng..."></textarea>

                <p class="mt-5 text-lg font-semibold mb-3">Hình ảnh minh chứng (nếu có)</p>
                <input type="file" name="image" accept="image/*" class="w-full border border-pink-200 rounded-lg p-2" id="return-image-input">

                <!-- Thêm thẻ img để hiển thị preview -->
                <img id="return-image-preview" src="#" alt="Preview" class="mt-3 max-h-64 rounded-lg hidden border border-gray-200 shadow-sm">

                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const input = document.getElementById('return-image-input');
                    const preview = document.getElementById('return-image-preview');

                    input.addEventListener('change', function() {
                        const file = this.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                preview.src = e.target.result;
                                preview.classList.remove('hidden'); // hiển thị ảnh
                            }
                            reader.readAsDataURL(file);
                        } else {
                            preview.src = '#';
                            preview.classList.add('hidden'); // ẩn nếu bỏ chọn
                        }
                    });
                });
                </script>

                <button type="submit" class="mt-6 w-full bg-pink-500 hover:bg-pink-600 text-white font-semibold py-3 rounded-full shadow-md transition duration-200">
                    Gửi yêu cầu
                </button>

                <a href="{{ route('account') }}" class="block text-center mt-4 text-gray-600 hover:text-pink-500">← Quay lại tài khoản</a>
            </div>
        </form>
    </main>
</x-layout-site>
