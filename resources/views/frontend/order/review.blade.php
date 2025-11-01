<x-layout-site>
    <x-slot:title>Đánh giá đơn hàng #{{ $order->id }}</x-slot:title>

    <main class="container mx-auto mt-10 mb-12 max-w-3xl p-4">
        <h1 class="text-3xl font-bold text-pink-600 mb-6 text-center">
            <i class="fas fa-star text-yellow-400"></i> Đánh giá sản phẩm
        </h1>

        <form action="{{ route('order.review.submit', $order->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            @foreach($order->orderDetails as $item)
                @php
                    $reviewed = \App\Models\ProductReview::where('product_id', $item->product_id)
                                ->where('user_id', auth()->id())
                                ->exists();
                @endphp

                <div class="bg-white border border-pink-100 rounded-xl shadow-md p-4 mb-6 hover:shadow-lg transition">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:space-x-4">
                        <img src="{{ asset('assets/images/product/' . $item->product->thumbnail) }}" 
                             alt="{{ $item->product->name }}" 
                             class="w-24 h-24 rounded-lg border border-pink-200 object-cover mx-auto sm:mx-0">

                        <div class="flex-1 mt-4 sm:mt-0">
                            <p class="font-semibold text-gray-800 text-center sm:text-left">{{ $item->product->name }}</p>

                            @if($reviewed)
                                <p class="text-green-600 mt-2 text-center sm:text-left">
                                    ✅ Bạn đã đánh giá sản phẩm này.
                                </p>
                            @else
                                <div class="flex justify-center sm:justify-start items-center space-x-2 mt-2 rating-group" data-product="{{ $item->product_id }}">
    @for($i = 1; $i <= 5; $i++)
        <i class="fas fa-star text-gray-300 cursor-pointer text-xl transition rating-star"
           data-value="{{ $i }}"></i>
        <input type="radio" name="rating_{{ $item->product_id }}" value="{{ $i }}" class="hidden">
    @endfor
</div>


                                <textarea name="comment_{{ $item->product_id }}" rows="3"
                                          class="mt-3 w-full border border-pink-200 rounded-lg p-2 text-gray-700"
                                          placeholder="Nhập đánh giá của bạn..."></textarea>

                                <div class="mt-3">
                                    <label class="block text-sm text-gray-700 mb-1">📸 Thêm ảnh minh họa:</label>
                                    <input type="file" name="image_{{ $item->product_id }}"
                                           accept="image/*"
                                           class="w-full text-sm text-gray-600 border border-pink-200 rounded-lg p-2 cursor-pointer file:mr-3 file:py-1 file:px-3 file:rounded-full file:border-0 file:bg-pink-500 file:text-white file:cursor-pointer hover:file:bg-pink-600 preview-input"
                                           data-preview="preview_{{ $item->product_id }}">
                                    <div id="preview_{{ $item->product_id }}" class="mt-3 hidden">
                                        <img src="#" alt="preview" class="w-24 h-24 object-cover rounded-lg border border-pink-300 mx-auto sm:mx-0">
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="flex justify-center gap-4">
                <button type="submit"
                    class="bg-pink-500 hover:bg-pink-600 text-white font-semibold px-6 py-3 rounded-full shadow-lg transition duration-200">
                    Gửi đánh giá
                </button>
                <a href="{{ route('account') }}"
                    class="bg-gray-400 hover:bg-gray-500 text-white font-semibold px-6 py-3 rounded-full shadow-lg transition duration-200">
                    Quay lại
                </a>
            </div>
        </form>
    </main>

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    {{-- Script xem trước ảnh --}}
    <script>
        document.querySelectorAll('.preview-input').forEach(input => {
            input.addEventListener('change', (e) => {
                const previewId = e.target.dataset.preview;
                const previewContainer = document.getElementById(previewId);
                const file = e.target.files[0];

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        const img = previewContainer.querySelector('img');
                        img.src = event.target.result;
                        previewContainer.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                } else {
                    previewContainer.classList.add('hidden');
                }
            });
        });
    </script>
    <script>
document.querySelectorAll('.rating-group').forEach(group => {
    const stars = group.querySelectorAll('.rating-star');
    const inputName = group.dataset.product;
    
    stars.forEach(star => {
        star.addEventListener('click', () => {
            const rating = parseInt(star.dataset.value);

            // tô vàng tất cả sao <= rating
            stars.forEach((s, index) => {
                if (index < rating) {
                    s.classList.remove('text-gray-300');
                    s.classList.add('text-yellow-400');
                } else {
                    s.classList.remove('text-yellow-400');
                    s.classList.add('text-gray-300');
                }
            });

            // cập nhật giá trị radio
            const hiddenInputs = group.querySelectorAll(`input[name="rating_${inputName}"]`);
            hiddenInputs.forEach(input => {
                input.checked = parseInt(input.value) === rating;
            });
        });
    });
});
</script>
<script>
document.querySelector('form').addEventListener('submit', function(e) {
    let valid = true;
    const groups = document.querySelectorAll('.rating-group');

    groups.forEach(group => {
        const inputName = group.dataset.product;
        const checkedInput = group.querySelector(`input[name="rating_${inputName}"]:checked`);
        if (!checkedInput) {
            valid = false;
            // highlight nhóm sao chưa chọn
            group.classList.add('border', 'border-red-500', 'rounded-lg', 'p-1');
        } else {
            group.classList.remove('border', 'border-red-500', 'p-1');
        }
    });

    if (!valid) {
        e.preventDefault();
        alert('Vui lòng đánh giá số sao cho tất cả sản phẩm trước khi gửi!');
        return false;
    }
});
</script>

</x-layout-site>
