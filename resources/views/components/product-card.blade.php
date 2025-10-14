<div class="product-card">
    {{-- Card bao ngoài: Bo góc lớn hơn và Shadow nhẹ nhàng, hiệu ứng hover nhẹ --}}
    <div class="p-4 bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100">
        
        {{-- Ảnh Sản Phẩm --}}
        <div class="item-img mb-4 relative overflow-hidden rounded-lg">
            <a href="{{ route('site.product-detail', ['slug' => $product->slug]) }}">
                <img src="{{ asset('assets/images/product/'. $product->thumbnail) }}" 
                     id="product-image-{{ $product->id }}" 
                     alt="{{ $product->thumbnail }}" 
                     class="w-full h-56 object-cover transform hover:scale-105 transition duration-500 ease-in-out">
            </a>
            
            {{-- Thẻ giảm giá (Tùy chọn) --}}
            @if ($product->price_root > $product->price_sale)
                @php 
                    $discount = round((($product->price_root - $product->price_sale) / $product->price_root) * 100);
                @endphp
                <span class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow-md">
                    GIẢM {{ $discount }}%
                </span>
            @endif
        </div>
        
        {{-- Thông tin Sản Phẩm --}}
        <div class="item-infor text-left"> {{-- Đổi text-center thành text-left cho chi tiết --}}
            
            {{-- Tên Sản phẩm --}}
            <a href="{{ route('site.product-detail', ['slug' => $product->slug]) }}">
                 <h3 class="text-xl font-bold mb-2 text-gray-800 line-clamp-2 hover:text-[#FF3399] transition duration-200">
                    {{ $product->name }}
                </h3>
            </a>

            {{-- 2. Tóm tắt ngắn (Summary) --}}
            <p class="text-sm text-gray-600 mb-3 line-clamp-3">
                {{ $product->description ?? 'Thiết kế độc đáo, chất lượng cao, mang lại trải nghiệm tuyệt vời cho người dùng.' }}
            </p>

            {{-- Khối Giá và Trạng thái hàng (Flexbox cho bố cục gọn) --}}
            <div class="mb-4 flex justify-between items-center pt-3 border-t border-gray-100">
                
                {{-- Trạng thái hàng (Stock Status) --}}
                <div class="text-sm font-medium">
                    @if (($product->qty ?? 1) > 0)
                        <span class="text-green-600 font-semibold">Còn hàng</span>
                    @else
                        <span class="text-red-600 font-semibold">Hết hàng</span>
                    @endif
                </div>

                {{-- Khối Giá --}}
                <div class="text-right">
                    @if ($product->price_root > $product->price_sale)
                        <span class="block text-sm text-gray-500 line-through">
                            {{ number_format($product->price_root, 0, ',', '.') }}đ
                        </span>
                    @endif
                    <span class="block text-xl font-extrabold text-[#FF3399]">
                        {{ number_format($product->price_sale, 0, ',', '.') }}đ
                    </span>
                </div>
            </div>

            {{-- Khối Button Chi tiết & Thêm vào giỏ --}}
            <div class="flex items-center gap-2 mt-4">
    <!-- Nút Chi tiết -->
    <a href="{{ route('site.product-detail', ['slug' => $product->slug]) }}" class="flex-1">
        <button
            class="bg-white border border-[#FF3399] text-[#FF3399] py-1.5 px-3 rounded-full 
                   hover:bg-[#FFF0F5] transition duration-300 w-full text-sm font-semibold">
            Chi tiết
        </button>
    </a>

    <!-- Nút Thêm vào giỏ -->
    <form action="{{ route('cart.add') }}" method="POST" class="flex-1 add-to-cart-form"
          id="add-to-cart-form-{{ $product->id }}">
        @csrf
        <input type="hidden" name="id" value="{{ $product->id }}">
        <input type="hidden" name="name" value="{{ $product->name }}">
        <input type="hidden" name="price" value="{{ $product->price_sale }}">
        <input type="number" name="quantity" value="1" min="1" class="hidden">

        <button type="submit" data-product-id="{{ $product->id }}"
                class="bg-[#FF3399] text-white py-1.5 px-3 rounded-full 
                       hover:bg-[#FF0077] transition duration-300 w-full text-sm font-semibold js-add-to-cart-btn
                       {{ ($product->qty ?? 1) <= 0 ? 'opacity-60 cursor-not-allowed' : '' }}"
                @if (($product->qty ?? 1) <= 0) disabled @endif>
            {{ ($product->qty ?? 1) > 0 ? 'Thêm vào giỏ' : 'Hết hàng' }}
        </button>
    </form>

    <!-- Nút Yêu thích -->
    @auth
        <button 
            class="w-10 h-10 flex items-center justify-center bg-white border border-gray-300 rounded-full 
                   shadow hover:bg-pink-100 transition duration-300"
            onclick="toggleWishlist({{ $product->id }})"
            title="Yêu thích">
            <i class="fas fa-heart text-[#FF3399] text-lg"></i>
        </button>
    @endauth
</div>

        </div>
    </div>
</div>