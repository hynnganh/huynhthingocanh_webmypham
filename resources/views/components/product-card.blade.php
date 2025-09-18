    <div class="left-sidebar p-6 bg-white border border-gray-200 rounded-lg shadow-lg hover:shadow-2xl transition-all duration-300">
            <div class="item-img mb-4 border border-gray-300 rounded-lg flex justify-center items-center">
                <img src="{{ asset('assets/images/product/'. $product->thumbnail) }}"  alt="{{ $product->thumbnail }}" class="w-full h-48 object-cover">
            </div>
            <div class="item-infor">
                <h6 class="text-xl font-semibold mb-3 text-gray-800">{{ $product->name }}</h6>
                <span class="block text-lg text-gray-600 mb-4 line-through">{{ $product->price_root }}đ</span>
                <span class="block text-lg text-gray-600 mb-4">{{ $product->price_sale }}đ </span>

                <div class="flex gap-4 mb-3">
                    <a href="{{ route('site.product-detail', ['slug' => $product->slug]) }}" class="w-2/4">
                        <button class="bg-[#FF66B2] text-white px-1 py-3 rounded-lg hover:bg-[#FF3399] transition-colors duration-300 w-full">
                            Chi tiết
                        </button>
                    </a>
                    <form action="{{ route('cart.add') }}" method="POST" class="w-2/4" id="add-to-cart-form">
                        @csrf
                        <input type="hidden" name="id" value="{{ $product->id }}">
                        <input type="hidden" name="name" value="{{ $product->name }}">
                        <input type="hidden" name="price" value="{{ $product->price_sale }}">
                        <!-- Số lượng mặc định 1 và ẩn -->
                        <input type="number" name="quantity" value="1" min="1" class="hidden">
                    
                        <button type="submit" class="bg-[#FF66B2] text-white px-1 py-3 rounded-lg hover:bg-[#FF3399] transition-colors duration-300 w-full">
                            Thêm vào giỏ
                        </button>
                    </form>
                </div>
            </div>
</div>