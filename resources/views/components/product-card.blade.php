<div class="product-card">
    <div class="p-4 bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100">
        
        {{-- Ảnh Sản Phẩm --}}
        <div class="item-img mb-4 relative overflow-hidden rounded-lg">
            <a href="{{ route('site.product-detail', ['slug' => $product->slug]) }}">
                <img id="product-image-{{ $product->id }}" 
     src="{{ asset('assets/images/product/'. $product->thumbnail) }}" 
     alt="{{ $product->name }}" 
     class="w-full h-56 object-cover transform hover:scale-105 transition duration-500 ease-in-out">

            </a>

            {{-- Giảm giá --}}
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
        <div class="item-infor text-left">
            <a href="{{ route('site.product-detail', ['slug' => $product->slug]) }}">
                <h3 class="text-xl font-bold mb-2 text-gray-800 line-clamp-2 hover:text-[#FF3399] transition duration-200">
                    {{ $product->name }}
                </h3>
            </a>

            <p class="text-sm text-gray-600 mb-3 line-clamp-3">
                {{ $product->description ?? 'Thiết kế độc đáo, chất lượng cao, mang lại trải nghiệm tuyệt vời cho người dùng.' }}
            </p>

            <div class="mb-4 flex justify-between items-center pt-3 border-t border-gray-100">
                <div class="text-sm font-medium">
                    @if (($product->qty ?? 1) > 0)
                        <span class="text-green-600 font-semibold">Còn hàng</span>
                    @else
                        <span class="text-red-600 font-semibold">Hết hàng</span>
                    @endif
                </div>

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

            <div class="flex items-center gap-2 mt-4">
                
                {{-- Nút Chi tiết --}}
                <a href="{{ route('site.product-detail', ['slug' => $product->slug]) }}" class="flex-1">
                    <button
                        class="bg-white border border-[#FF3399] text-[#FF3399] py-1.5 px-3 rounded-full 
                            hover:bg-[#FFF0F5] transition duration-300 w-full text-sm font-semibold">
                        Chi tiết
                    </button>
                </a>

                <form action="{{ route('cart.add') }}" method="POST" class="flex-1">
    @csrf
    <input type="hidden" name="id" value="{{ $product->id }}">
    <input type="hidden" name="name" value="{{ $product->name }}">
    <input type="hidden" name="price" value="{{ $product->price_sale }}">
    <input type="hidden" name="quantity" value="1">
    <button type="submit"
        data-product-id="{{ $product->id }}"
        class="js-add-to-cart-btn bg-[#FF3399] text-white py-1.5 px-3 rounded-full hover:bg-[#FF0077] transition duration-300 w-full text-sm font-semibold
        {{ ($product->qty ?? 1) <= 0 ? 'opacity-60 cursor-not-allowed' : '' }}"
        @if (($product->qty ?? 1) <= 0) disabled @endif>
        @if(($product->qty ?? 1) > 0)
            <i class="fas fa-shopping-cart text-white text-base"></i>
        @else
            <i class="fas fa-lock text-white text-base"></i>
        @endif
    </button>
</form>


                @auth <form action="{{ route('wishlist.toggle') }}" method="POST"> @csrf <input type="hidden" name="product_id" value="{{ $product->id }}"> <button type="submit" class="wishlist-btn flex items-center justify-center w-9 h-9 bg-white border border-gray-200 rounded-full shadow-sm hover:bg-pink-50 transition duration-300" title="Yêu thích"> @if(auth()->user()->wishlist->contains('product_id', $product->id)) <i class="fas fa-heart text-[#FF3399] text-lg"></i> @else <i class="far fa-heart text-gray-400 text-lg"></i> @endif </button> </form> @endauth
            </div>
        </div>
    </div>
</div>

    <script>
        setTimeout(() => {
            const toast = document.getElementById('toast');
            if (toast) {
                toast.classList.add('opacity-0', 'transition', 'duration-500');
                setTimeout(() => toast.remove(), 500);
            }
        }, 2000);
    </script>


