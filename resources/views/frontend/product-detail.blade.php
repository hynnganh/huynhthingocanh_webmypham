<x-layout-site>
    <x-slot:title>
        Chi tiết sản phẩm
    </x-slot:title>

    <main class="bg-gray-100 py-12">
        <div class="max-w-7xl mx-auto px-2">
            <button type="button" onclick="history.back()"
                class="bg-gray-300 text-gray-800 px-5 py-2 rounded-lg hover:bg-gray-400 transition">
                Quay lại
            </button>
            <br><br>
        </div>

        <div class="max-w-7xl mx-auto bg-white p-8 rounded-2xl shadow-2xl space-y-12 px-4">

            {{-- ======================= THÔNG TIN SẢN PHẨM ======================= --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 border-b border-pink-100 pb-8">
                {{-- Ảnh --}}
                <div class="lg:col-span-4 flex flex-col items-center">
                    <div class="w-full max-w-sm mx-auto relative overflow-hidden rounded-xl shadow-xl border border-pink-100 p-2 bg-white">
                        <img src="{{ asset('assets/images/product/' . $product->thumbnail) }}"
                             alt="{{ $product->name }}"
                             class="w-full h-auto object-cover rounded-lg" />
                    </div>
                </div>

                {{-- Thông tin --}}
                <div class="lg:col-span-5 flex flex-col justify-start space-y-5">
                    <h1 class="text-3xl font-extrabold text-gray-800 border-b border-pink-200 pb-3">
                        {{ $product->name }}
                    </h1>

                    <div class="flex items-baseline space-x-4 bg-pink-50 p-3 rounded-lg border border-pink-200">
                        <span class="text-3xl font-bold text-red-600">
                            {{ number_format($product->price_sale, 0, ',', '.') }} <sup>₫</sup>
                        </span>
                        @if($product->price_sale < $product->price_root)
                            <span class="text-lg text-gray-500 line-through">
                                {{ number_format($product->price_root, 0, ',', '.') }} <sup>₫</sup>
                            </span>
                            @php
                                $discount = round((($product->price_root - $product->price_sale) / $product->price_root) * 100);
                            @endphp
                            <span class="text-base font-bold text-white bg-red-500 px-2 py-1 rounded-full">
                                -{{ $discount }}%
                            </span>
                        @endif
                    </div>

                    <div class="flex flex-col space-y-4 pt-2">
                        <div class="flex items-center justify-start">
                            <label class="text-lg font-semibold text-gray-700 mr-2">SL:</label>
                            <input id="product-quantity" name="quantity" type="number" value="1" min="1" max="{{ $product->qty }}"
                                   class="border border-pink-300 px-3 py-2 rounded-lg w-16 text-center text-gray-700 focus:ring-pink-400 focus:border-pink-400"
                                   oninput="this.value = Math.max(1, Math.min({{ $product->qty }}, this.value))" />
                        </div>

                        <div class="flex items-center justify-start">
                            <div class="text-lg font-semibold">
                                Tình trạng:
                                @if($product->qty > 0)
                                    <span class="text-green-600 font-bold">Còn hàng ({{ $product->qty }} sp)</span>
                                @else
                                    <span class="text-red-600 font-bold">Hết hàng</span>
                                @endif
                            </div>
                        </div>

                        {{-- Nút --}}
                        <div class="flex space-x-4 justify-start">
                            {{-- Giỏ hàng --}}
                            <form id="add-to-cart-form" action="{{ route('cart.add') }}" method="POST" class="w-full max-w-[150px]">
                                @csrf
                                <input type="hidden" name="id" value="{{ $product->id }}">
                                <input type="hidden" name="name" value="{{ $product->name }}">
                                <input type="hidden" name="price" value="{{ $product->price_sale }}">
                                <input type="hidden" id="cart-quantity-input" name="quantity" value="1">

                                <button type="submit" class="bg-pink-500 text-white font-bold text-base px-4 py-3 rounded-xl hover:bg-pink-600 transition duration-300 w-full shadow-lg shadow-pink-200"
                                        {{ $product->qty <= 0 ? 'disabled' : '' }}>
                                    <i class="fa fa-shopping-cart mr-1"></i> Giỏ hàng
                                </button>
                            </form>

                            {{-- Mua ngay --}}
                            <form action="{{ route('cart.buyNow') }}" method="POST" class="w-full max-w-[150px]">
                                @csrf
                                <input type="hidden" name="id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" id="buy-now-quantity-input" value="1">
                                <button type="submit"
                                        class="bg-red-500 text-white font-bold text-base px-4 py-3 rounded-xl w-full hover:bg-red-600 transition duration-300 shadow-lg shadow-red-200 {{ $product->qty <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                        {{ $product->qty <= 0 ? 'disabled' : '' }}>
                                    Mua ngay
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Mã khuyến mãi --}}
                <div class="lg:col-span-3 flex flex-col justify-start space-y-5">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center border-b border-pink-200 pb-5">
                        <i class="fa fa-tag text-pink-500 mr-2 text-sm"></i> Mã Khuyến Mãi
                    </h3>
                    <div class="grid grid-cols-1 gap-4">
                        @php
                            $coupons = [
                                ['code' => 'COCOLUX25K', 'discount' => '25.000đ', 'condition' => 'Đơn hàng từ 299K'],
                                ['code' => 'FREESHIP', 'discount' => 'Miễn phí ship', 'condition' => 'Đơn hàng từ 400K'],
                                ['code' => 'VIP10', 'discount' => 'Giảm 10%', 'condition' => 'Chỉ áp dụng cho thành viên VIP'],
                            ];
                        @endphp
                        @foreach($coupons as $coupon)
                            <div class="p-4 border border-pink-300 rounded-xl shadow-lg bg-white flex flex-col justify-between">
                                <div class="mb-3">
                                    <h4 class="text-lg font-bold text-red-600">{{ $coupon['discount'] }}</h4>
                                    <p class="text-sm text-gray-500 line-clamp-2">{{ $coupon['condition'] }}</p>
                                </div>
                                <div class="flex space-x-2 mt-auto">
                                    <button onclick="openModal('{{ $coupon['code'] }}', '{{ $coupon['discount'] }}', '{{ $coupon['condition'] }}')"
                                            class="flex-1 border border-pink-500 text-pink-500 font-semibold text-sm px-2 py-1 rounded-lg hover:bg-pink-50 transition duration-150">
                                        Chi tiết
                                    </button>
                                    <button onclick="copyCode('{{ $coupon['code'] }}')"
                                            class="flex-1 bg-pink-500 text-white font-bold text-sm px-2 py-1 rounded-lg flex items-center justify-center hover:bg-pink-600 transition duration-150">
                                        <span class="mr-1">Sao chép</span><i class="fa fa-copy text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ======================= MÔ TẢ ======================= --}}
            <div class="pt-4 border-b border-pink-100 pb-8">
                <h3 class="text-2xl font-bold text-gray-800 border-b-2 border-pink-400 pb-2 mb-6">Mô Tả Chi Tiết</h3>
                <div class="prose max-w-none text-gray-700 product-detail leading-relaxed">
                    {!! $product->detail !!}
                </div>
            </div>

            {{-- ======================= ĐÁNH GIÁ SẢN PHẨM ======================= --}}
            <div class="pt-8">
                <h3 class="text-2xl font-bold text-gray-800 border-b-2 border-pink-400 pb-2 mb-6">
                    Đánh Giá Sản Phẩm
                </h3>

                @php
                    $user = Auth::user();
                    $canReview = false;
                    if ($user) {
                        $canReview = DB::table('order')
                            ->join('orderdetail', 'order.id', '=', 'orderdetail.order_id')
                            ->where('order.user_id', $user->id)
                            ->where('orderdetail.product_id', $product->id)
                            ->where('order.status', 5)
                            ->exists();
                    }
                @endphp

                @if($user && $canReview)
                    {{-- ✅ Form đánh giá --}}
                    <form action="{{ route('review.store') }}" method="POST" enctype="multipart/form-data"
                          class="space-y-6 bg-pink-50 p-6 rounded-xl border border-pink-200 shadow-md">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        {{-- Rating --}}
                        <div>
                            <label class="font-semibold text-gray-700 block mb-2">Đánh giá của bạn:</label>
                            <div class="flex space-x-2 text-2xl text-gray-400" id="rating-stars">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fa-solid fa-star cursor-pointer hover:scale-110 transition" data-value="{{ $i }}"></i>
                                @endfor
                            </div>
                            <input type="hidden" name="rating" id="rating" required>
                        </div>

                        {{-- Comment --}}
                        <div>
                            <label class="font-semibold text-gray-700 block mb-2">Bình luận:</label>
                            <textarea name="comment" rows="3"
                                      class="w-full border border-pink-300 rounded-lg p-2 focus:ring-pink-400 focus:border-pink-400"
                                      placeholder="Chia sẻ cảm nhận của bạn..."></textarea>
                        </div>

                        {{-- Ảnh và video --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="font-semibold text-gray-700 block mb-2">Ảnh minh họa:</label>
                                <input type="file" name="image" id="image" accept="image/*"
                                       class="w-full border border-pink-300 rounded-lg p-2 focus:ring-pink-400">
                                <img id="image-preview" class="hidden mt-3 w-32 h-32 rounded-lg border border-pink-300 object-cover" />
                            </div>
                            <div>
                                <label class="font-semibold text-gray-700 block mb-2">Video minh họa:</label>
                                <input type="file" name="video" id="video" accept="video/*"
                                       class="w-full border border-pink-300 rounded-lg p-2 focus:ring-pink-400">
                                <video id="video-preview" class="hidden mt-3 w-60 rounded-lg border border-pink-300" controls></video>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-pink-500 text-white font-bold px-6 py-2 rounded-lg hover:bg-pink-600 transition shadow-md">
                                Gửi đánh giá
                            </button>
                        </div>
                    </form>
                @elseif($user)
                    <p class="text-gray-600 italic">
                        ⚠️ Bạn chỉ có thể đánh giá sản phẩm sau khi đơn hàng được giao thành công.
                    </p>
                @else
                    <p class="text-gray-600 italic">
                        Vui lòng <a href="{{ route('login') }}" class="text-pink-500 font-semibold hover:underline">đăng nhập</a> để gửi đánh giá.
                    </p>
                @endif

                {{-- Danh sách đánh giá --}}
                <div class="mt-8 space-y-6">
                    @forelse ($product->reviews as $review)
                        <div class="border border-pink-100 p-4 rounded-lg bg-white shadow-sm">
                            <div class="flex items-center justify-between">
                                <div class="font-bold text-gray-800">
                                    {{ $review->user->name ?? 'Người dùng ẩn danh' }}
                                </div>
                                <div class="text-yellow-500">
                                    @for ($i = 0; $i < $review->rating; $i++)
                                        ★
                                    @endfor
                                    @for ($i = $review->rating; $i < 5; $i++)
                                        ☆
                                    @endfor
                                </div>
                            </div>

                            <p class="text-gray-700 mt-2">{{ $review->comment }}</p>

                            @if($review->image)
                                <div class="mt-3">
                                    <img src="{{ asset($review->image) }}" alt="Ảnh đánh giá"
     class="rounded-lg border border-pink-200 max-w-[200px]">

                                </div>
                            @endif

                            @if($review->video)
                                <div class="mt-3">
                                    <video width="320" controls class="rounded-lg border border-pink-200">
<source src="{{ asset($review->video) }}" type="video/mp4">
                                    </video>
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-gray-500 italic">Chưa có đánh giá nào cho sản phẩm này.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </main>

    {{-- Script Preview & Rating --}}
    <script>
    document.getElementById('image').addEventListener('change', e => {
        const file = e.target.files[0];
        if (file) {
            const preview = document.getElementById('image-preview');
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('hidden');
        }
    });
    document.getElementById('video').addEventListener('change', e => {
        const file = e.target.files[0];
        if (file) {
            const preview = document.getElementById('video-preview');
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('hidden');
        }
    });

    const stars = document.querySelectorAll('#rating-stars i');
    const ratingInput = document.getElementById('rating');
    stars.forEach(star => {
        star.addEventListener('click', () => {
            const value = parseInt(star.dataset.value);
            ratingInput.value = value;
            stars.forEach(s => s.classList.toggle('text-yellow-400', s.dataset.value <= value));
        });
    });
    </script>
</x-layout-site>
