<x-layout-site>
    <x-slot:title>
        Chi tiết sản phẩm
    </x-slot:title>

    <main class="bg-pink-50 py-10">
        <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-lg grid grid-cols-4 gap-6">
            <!-- Sản phẩm chính -->
            <div class="col-span-3 border border-gray-300 rounded-lg p-4 flex">
                <div class="w-1/4 flex justify-center items-start">
                    <img src="{{ asset('assets/images/product/' . $product->thumbnail) }}"  
                         alt="{{ $product->name }}"
                         class="rounded-lg w-full h-auto object-cover shadow-xl" />
                </div>

                <div class="w-3/4 flex flex-col justify-start space-y-6 pl-4">
                    <div class="flex flex-col space-y-3">
                        <h2 class="text-3xl text-center font-bold text-pink-600">{{ $product->name }}</h2>
                        <div class="flex items-center space-x-3">
                            <h2 class="text-lg font-medium text-gray-700">Giá:</h2>
                            <span class="text-xl font-bold text-red-600">{{ number_format($product->price_sale) }} VND</span>
s                            @if($product->price_sale < $product->price_root)
                                <span class="text-xs text-gray-500 line-through">{{ number_format($product->price_root) }} VND</span>
                            @endif
                        </div>
                        
                    </div>

                    <div class="flex items-center space-x-2">
                        <label class="text-lg font-medium text-gray-700">Số lượng:</label>
                        <input name="quantity" type="number" value="1" min="1"
                               class="border border-gray-300 px-4 py-2 rounded-lg w-24 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>

                    <div class="flex space-x-4">
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

                        <button type="submit" class="bg-pink-400 text-white px-4 py-2 rounded-lg w-1/2">
                            <i class="fa fa-credit-card"></i>
                            <span>Mua ngay</span>
                        </button>
                        
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
            
                @if($product_list->count() > 0)
                    @foreach($product_list as $product_row)
                        <div class="flex items-center bg-gray-100 p-4 rounded-lg shadow-md mb-4">
                            <img src="{{ asset('assets/images/product/' . $product_row->thumbnail) }}"
     class="rounded-lg w-20 h-20 object-cover shadow-xl mr-4"
     alt="{{ $product_row->name }}" />

                            <div>
                                <a href="{{ route('site.product-detail', ['slug' => $product_row->slug]) }}"
                                   class="text-gray-800 font-semibold block hover:text-pink-500">
                                    {{ $product_row->name }}
                                </a>
                                <p class="text-sm text-gray-600">{{ number_format($product_row->sale_price, 0, ',', '.') }} <sup>₫</sup></p>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-gray-500 italic">Không có sản phẩm tương tự nào.</p>
                @endif
            </div>
            
        </div>
    </main>
</x-layout-site>
