<x-layout-site>
    <x-slot:title>Xem đánh giá đơn hàng #{{ $order->id }}</x-slot:title>

    <main class="container mx-auto mt-10 mb-12 max-w-3xl p-4">
        <h1 class="text-3xl font-bold text-pink-600 mb-6 text-center">
            <i class="fas fa-eye text-green-500"></i> Đánh giá của bạn
        </h1>

        @foreach($order->orderDetails as $item)
            @php
                $review = $reviews[$item->product_id] ?? null;
            @endphp

            <div class="bg-white border border-pink-100 rounded-xl shadow-md p-4 mb-6">
                <div class="flex items-start space-x-4">
                    {{-- Ảnh sản phẩm --}}
                    <img src="{{ asset('assets/images/product/' . $item->product->thumbnail) }}" 
                         alt="{{ $item->product->name }}" 
                         class="w-20 h-20 rounded-lg border border-pink-200 object-cover">

                    <div class="flex-1">
                        <p class="font-semibold text-gray-800">{{ $item->product->name }}</p>

                        {{-- Nếu có đánh giá --}}
                        @if($review)
                            {{-- Hiển thị sao --}}
                            <div class="flex items-center mt-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                @endfor
                            </div>

                            {{-- Hiển thị ảnh đánh giá nếu có --}}
                            @if(!empty($review->image))
                                <div class="mt-3 flex flex-wrap gap-3">
                                    @foreach(explode(',', $review->image) as $img)
                                        <a href="{{ asset(trim($img)) }}" target="_blank">
    <img src="{{ asset(trim($img)) }}"
         class="w-28 h-28 object-cover rounded-lg border border-pink-200 shadow-sm hover:scale-105 transition-transform duration-200"
         alt="Ảnh đánh giá">
</a>

                                    @endforeach
                                </div>
                            @endif

                            {{-- Hiển thị nội dung đánh giá --}}
                            <p class="mt-3 text-gray-700 italic">“{{ $review->comment }}”</p>
                        @else
                            <p class="text-gray-500 mt-2">Bạn chưa đánh giá sản phẩm này.</p>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach

        {{-- Nút quay lại --}}
        <div class="flex justify-center">
            <a href="{{ route('account') }}" 
               class="bg-gray-400 hover:bg-gray-500 text-white font-semibold px-6 py-3 rounded-full shadow-lg transition duration-200">
                Quay lại
            </a>
        </div>
    </main>

    {{-- Font Awesome --}}
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</x-layout-site>
