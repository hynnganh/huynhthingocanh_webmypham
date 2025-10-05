<x-layout-site>
    <x-slot:title>
        Chi tiết sản phẩm
    </x-slot:title>

    <main class="bg-pink-50 py-10">
        <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-lg grid grid-cols-4 gap-6">
            <!-- Sản phẩm chính -->
            <div class="col-span-3 border border-gray-300 rounded-lg p-4 flex">
                <!-- Ảnh -->
                <div class="w-1/4 flex justify-center items-start">
                    <img src="{{ asset('assets/images/product/' . $product->thumbnail) }}"  
                         alt="{{ $product->name }}"
                         class="rounded-lg w-full h-auto object-cover shadow-xl" />
                </div>

                <!-- Thông tin -->
                <div class="w-3/4 flex flex-col justify-start space-y-6 pl-4">
                    <h2 class="text-3xl text-center font-bold text-pink-600">{{ $product->name }}</h2>
                    <div class="flex items-center space-x-3">
                        <span class="text-xl font-bold text-red-600">
                            {{ number_format($product->price_sale, 0, ',', '.') }} VND
                        </span>
                        @if($product->price_sale < $product->price_root)
                            <span class="text-xs text-gray-500 line-through">
                                {{ number_format($product->price_root, 0, ',', '.') }} VND
                            </span>
                        @endif
                    </div>
                    <div>
                        @if($product->qty > 0)
                            <span class="text-green-600 font-medium">Còn hàng</span>
                        @else
                            <span class="text-red-600 font-medium">Hết hàng</span>
                        @endif
                    </div>
<div class="flex items-center space-x-2">
    <label class="text-lg font-medium text-gray-700">Số lượng:</label>
    <input id="product-quantity" name="quantity" type="number" value="1" min="1" max="{{ $product->qty }}"
           class="border border-gray-300 px-4 py-2 rounded-lg w-24 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
           oninput="this.value = Math.max(1, Math.min({{ $product->qty }}, this.value))" />
</div>
                    <!-- Nút thêm giỏ -->
                    <div class="flex space-x-4">
                        <!-- Thêm vào giỏ -->
                        <form action="{{ route('cart.add') }}" method="POST" class="w-2/4">
                            @csrf
                            <input type="hidden" name="id" value="{{ $product->id }}">
                            <input type="hidden" name="name" value="{{ $product->name }}">
                            <input type="hidden" name="price" value="{{ $product->price_sale }}">
                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->qty }}" class="hidden">
                            <button type="submit" class="bg-[#FF66B2] text-white px-4 py-2 rounded-lg hover:bg-[#FF3399] transition-colors duration-300 w-full"
                                    {{ $product->qty <= 0 ? 'disabled' : '' }}>
                                Thêm vào giỏ
                            </button>
                        </form>

                        <!-- Mua ngay -->
                  <form action="{{ route('cart.buyNow') }}" method="POST" class="w-1/2">
                    @csrf
                    <input type="hidden" name="id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit"
                            class="bg-pink-500 text-white px-4 py-2 rounded-lg w-full {{ $product->qty <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ $product->qty <= 0 ? 'disabled' : '' }}>
                        Mua ngay
                    </button>
                </form>


                    </div>

                    <div class="mt-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Chi tiết sản phẩm</h3>
                        <p class="text-lg text-gray-700">{{ $product->description }}</p>
                    </div>
                </div>
            </div>

            <!-- Sản phẩm tương tự -->
            <div class="col-span-1 border border-gray-300 rounded-lg p-4">
                <h3 class="text-xl font-semibold text-pink-600 mb-4 font-serif">Sản phẩm tương tự</h3>
                @forelse($product_list as $product_row)
                    <div class="flex items-center bg-gray-100 p-4 rounded-lg shadow-md mb-4">
                        <img src="{{ asset('assets/images/product/' . $product_row->thumbnail) }}"
                             class="rounded-lg w-20 h-20 object-cover shadow-xl mr-4"
                             alt="{{ $product_row->name }}" />
                        <div>
                            <a href="{{ route('site.product-detail', ['slug' => $product_row->slug]) }}"
                               class="text-gray-800 font-semibold block hover:text-pink-500">
                                {{ $product_row->name }}
                            </a>
                            <p class="text-sm text-gray-600">
                                {{ number_format($product_row->price_sale, 0, ',', '.') }} <sup>₫</sup>
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 italic">Không có sản phẩm tương tự nào.</p>
                @endforelse
            </div>
        </div>

        <!-- Đánh giá sản phẩm -->
        <div class="max-w-6xl mx-auto mt-10 bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Đánh giá sản phẩm</h3>

            @forelse($reviews as $review)
                <div class="border-b pb-3 mb-3">
                    <p class="font-semibold text-pink-600">
                        {{ $review->user->name ?? 'Khách' }} -
                        <span class="text-yellow-500">{{ str_repeat('★', $review->rating) }}</span>
                    </p>
                    <p class="text-gray-700">{{ $review->comment }}</p>
                    <span class="text-xs text-gray-500">{{ $review->created_at->format('d/m/Y') }}</span>
                </div>
            @empty
                <p class="text-gray-500 italic">Chưa có đánh giá nào.</p>
            @endforelse

            <!-- Form gửi đánh giá -->
            @if($canReview)
                <form action="{{ route('review.store') }}" method="POST" class="mt-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <label class="block mb-2">Số sao:</label>
                    <select name="rating" class="border rounded px-2 py-1 mb-3">
                        @for($i=1; $i<=5; $i++)
                            <option value="{{ $i }}">{{ $i }} ★</option>
                        @endfor
                    </select>
                    <textarea name="comment" rows="3" class="w-full border rounded p-2 mb-3" placeholder="Viết đánh giá..."></textarea>
                    <button type="submit" class="bg-pink-500 text-white px-4 py-2 rounded">Gửi đánh giá</button>
                </form>
            @else
                <p class="text-gray-500 italic">Bạn chỉ có thể đánh giá sản phẩm khi đơn hàng đã giao thành công.</p>
            @endif
        </div>
    </main>
</x-layout-site>
