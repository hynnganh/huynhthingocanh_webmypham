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
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 border-b border-pink-100 pb-8">
                
                {{-- CỘT 1 (lg:col-span-4): Ảnh sản phẩm --}}
                <div class="lg:col-span-4 flex flex-col items-center">
                    <div class="w-full max-w-sm mx-auto relative overflow-hidden rounded-xl shadow-xl border border-pink-100 p-2 bg-white">
                        <img src="{{ asset('assets/images/product/' . $product->thumbnail) }}" 
                             alt="{{ $product->name }}"
                             class="w-full h-auto object-cover rounded-lg" />
                    </div>
                </div>

                {{-- CỘT 2 (lg:col-span-5): Thông tin, Giá, SL, Nút mua hàng --}}
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
                    
                    {{-- KHỐI THÔNG TIN MUA HÀNG (SL, Tình trạng, Nút) --}}
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

                        <div class="flex space-x-4 justify-start"> 
                            
                            {{-- Thêm vào giỏ --}}
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

                    <script>
                        // Logic đồng bộ số lượng (Giữ nguyên)
                        document.addEventListener('DOMContentLoaded', () => {
                            const quantityInput = document.getElementById('product-quantity');
                            const cartQuantityInput = document.getElementById('cart-quantity-input');
                            const buyNowQuantityInput = document.getElementById('buy-now-quantity-input');
                            if (quantityInput) {
                                quantityInput.addEventListener('input', () => {
                                    const value = quantityInput.value;
                                    cartQuantityInput.value = value;
                                    buyNowQuantityInput.value = value;
                                });
                            }
                        });
                    </script>
                </div>

                {{-- CỘT 3 (lg:col-span-3): Mã khuyến mãi --}}
                <div class="lg:col-span-3 flex flex-col justify-start space-y-5">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center border-b border-pink-200 pb-5">
                        <i class="fa fa-tag text-pink-500 mr-2 text-sm"></i> Mã Khuyến Mãi
                    </h3>
                    
                    <div class="grid grid-cols-1 gap-4">
                        @php
                            // GIẢ LẬP DỮ LIỆU MÃ VOUCHER
                            $coupons = [
                                ['code' => 'COCOLUX25K', 'discount' => '25.000đ', 'condition' => 'Đơn hàng từ 299K'],
                                ['code' => 'FREESHIP', 'discount' => 'Miễn phí ship', 'condition' => 'Đơn hàng từ 400K'],
                                ['code' => 'VIP10', 'discount' => 'Giảm 10%', 'condition' => 'Chỉ áp dụng cho thành viên VIP'],
                            ];
                        @endphp

                        @foreach($coupons as $coupon)
                            {{-- Coupon Card --}}
                            <div class="p-4 border border-pink-300 rounded-xl shadow-lg bg-white flex flex-col justify-between">
                                
                                <div class="mb-3">
                                    <h4 class="text-lg font-bold text-red-600">{{ $coupon['discount'] }}</h4>
                                    <p class="text-sm text-gray-500 line-clamp-2">{{ $coupon['condition'] }}</p>
                                </div>
                                
                                <div class="flex space-x-2 mt-auto">
                                    
                                    {{-- Nút Chi tiết (Mở Modal) --}}
                                    <button onclick="openModal('{{ $coupon['code'] }}', '{{ $coupon['discount'] }}', '{{ $coupon['condition'] }}')"
                                            class="flex-1 border border-pink-500 text-pink-500 font-semibold text-sm px-2 py-1 rounded-lg hover:bg-pink-50 transition duration-150">
                                        Chi tiết
                                    </button>

                                    {{-- Nút Sao chép Mã Voucher --}}
                                    <button onclick="copyCode('{{ $coupon['code'] }}')" 
                                            class="flex-1 bg-pink-500 text-white font-bold text-sm px-2 py-1 rounded-lg flex items-center justify-center hover:bg-pink-600 transition duration-150">
                                        <span class="mr-1">Sao chép</span>
                                        <i class="fa fa-copy text-xs"></i>
                                    </button>
                                    
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="pt-4 border-b border-pink-100 pb-8">
                <h3 class="text-2xl font-bold text-gray-800 border-b-2 border-pink-400 pb-2 mb-6">Mô Tả Chi Tiết</h3>
                <div class="prose max-w-none text-gray-700 product-detail leading-relaxed">
                    {!! $product->detail !!}
                </div>
            </div>
            
            <div class="pt-8">
                <h3 class="text-2xl font-bold text-gray-800 border-b-2 border-pink-400 pb-2 mb-6">Sản Phẩm Tương Tự</h3>
                
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                     @forelse($product_list as $product_row)
                         <x-product-card :productrow="$product_row" />
                     @empty
                        <p class="text-gray-500 italic text-center col-span-full">Không có sản phẩm tương tự nào.</p>
                    @endforelse
                </div>
            </div>
            <div class="pt-8">
                <h3 class="text-2xl font-bold text-gray-800 border-b-2 border-pink-400 pb-2 mb-6">Đánh Giá Sản Phẩm</h3>
                </div>

        </div>
    </main>


    {{-- START: Modal Chi tiết Mã Khuyến Mãi (Giữ nguyên) --}}
    <div id="coupon-detail-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden justify-center items-center p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6 relative">
            
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h3 class="text-xl font-bold text-gray-800">Chi tiết Mã khuyến mại</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-700">
                    <i class="fa fa-times text-2xl"></i>
                </button>
            </div>

            <div class="space-y-4">
                
                <div class="pb-3 border-b">
                    <p class="text-lg font-bold">Giảm <span class="text-red-600" id="modal-discount-amount"></span></p>
                    <p class="text-sm text-gray-500" id="modal-condition-summary"></p>
                </div>
                
                <div class="flex items-center justify-between border p-3 rounded-lg bg-gray-50">
                    <p class="text-xl font-bold text-pink-600" id="modal-coupon-code"></p>
                    <button onclick="copyCode(document.getElementById('modal-coupon-code').textContent)" class="text-pink-500 hover:text-pink-700 ml-4">
                        <i class="fa fa-copy text-lg"></i>
                    </button>
                </div>

                <div>
                    <p class="font-semibold text-gray-700">Áp dụng từ</p>
                    <p class="text-gray-600">2025-04-04 – 2025-09-30</p>
                </div>

                <div class="border-t pt-4">
                    <p class="font-semibold text-gray-700 mb-2">Chi tiết</p>
                    <ul class="list-disc list-inside text-gray-600 space-y-1 text-sm pl-4">
                        <li>Điều kiện chi tiết sẽ được tải vào đây dựa trên mã voucher.</li>
                    </ul>
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t">
                <button onclick="closeModal()" class="bg-gray-200 text-gray-700 font-semibold px-5 py-2 rounded-lg hover:bg-gray-300">
                    Đóng
                </button>
                <button onclick="copyCode(document.getElementById('modal-coupon-code').textContent); closeModal();" class="bg-pink-500 text-white font-semibold px-5 py-2 rounded-lg hover:bg-pink-600">
                    Sao chép
                </button>
            </div>

        </div>
    </div>


    <!-- ✅ Toast thông báo -->
<div id="toast" class="fixed bottom-5 right-5 hidden text-white px-4 py-2 rounded-lg shadow-lg z-50 transition-opacity duration-300"></div>

<script>
    const modal = document.getElementById('coupon-detail-modal');
    const modalCouponCode = document.getElementById('modal-coupon-code');
    const modalDiscountAmount = document.getElementById('modal-discount-amount');
    const modalConditionSummary = document.getElementById('modal-condition-summary');

    function openModal(code, discount, condition) {
        modalCouponCode.textContent = code;
        modalDiscountAmount.textContent = discount;
        modalConditionSummary.textContent = condition;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function showToast(message, success = true) {
        const toast = document.getElementById('toast');
        if (!toast) return;

        toast.textContent = message;
        toast.className = `fixed bottom-5 right-5 px-4 py-2 rounded-lg shadow-lg z-50 text-white transition-opacity duration-300 ${
            success ? 'bg-green-500' : 'bg-red-500'
        }`;

        toast.classList.remove('hidden', 'opacity-0');
        toast.classList.add('opacity-100');

        setTimeout(() => {
            toast.classList.remove('opacity-100');
            toast.classList.add('opacity-0');
        }, 2000);

        setTimeout(() => {
            toast.classList.add('hidden');
        }, 2300);
    }

    function copyCode(code) {
        navigator.clipboard.writeText(code).then(() => {
            showToast("Đã sao chép mã: " + code, true);
        }).catch(err => {
            console.error('Lỗi sao chép:', err);
            showToast("Không thể sao chép. Vui lòng thử lại", false);
        });
    }

    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeModal();
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });
</script>


</x-layout-site>