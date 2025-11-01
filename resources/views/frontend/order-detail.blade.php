<x-layout-site>
    <x-slot:title>Chi tiết đơn hàng #{{ $order->id }}</x-slot:title>

    <main class="container mx-auto mt-8 mb-12 max-w-4xl space-y-8 p-4">
        
        <h1 class="text-4xl md:text-5xl font-extrabold text-pink-700 text-center tracking-tight mb-8">
            <i class="fas fa-box-open mr-2 text-pink-500"></i> ĐƠN HÀNG #{{ $order->id }}
        </h1>

        {{-- Stepper (Tiến trình đơn hàng) --}}
        <div class="bg-white shadow-xl rounded-2xl border border-pink-100 p-6 md:p-8">
            <h2 class="text-2xl font-bold text-pink-600 mb-6 flex items-center">
                <i class="fas fa-chart-line mr-3"></i> Trạng thái đơn hàng
            </h2>

            @php
                $steps = [
                    1 => ['label' => 'Chờ xác nhận', 'color' => 'yellow'],
                    2 => ['label' => 'Đang chuẩn bị hàng', 'color' => 'orange'],
                    3 => ['label' => 'Đang giao hàng', 'color' => 'indigo'],
                    4 => ['label' => 'Giao thành công', 'color' => 'green'],
                ];
                $canceled_step = 5;
                $current_step = $order->status;
                $is_canceled = $current_step == $canceled_step;
            @endphp

            @if($is_canceled)
                <div class="text-center py-4 bg-red-50 border border-red-300 rounded-lg text-red-700 font-semibold text-lg">
                    <i class="fas fa-times-circle mr-2"></i> Đơn hàng đã bị HỦY
                </div>
            @else
                <div class="relative flex justify-between items-center py-6 px-4 overflow-x-auto hide-scrollbar">
                    {{-- Dây nền --}}
                    <div class="absolute top-[46px] left-0 right-0 h-1 bg-gray-200 transform -translate-y-1/2 z-0"></div>

                    {{-- Dây hồng chạy full khi giao hàng thành công --}}
                    @if($current_step == 4)
                        <div class="absolute top-[46px] left-0 right-0 h-1 bg-pink-500 transform -translate-y-1/2 z-0 transition-all duration-700"></div>
                    @endif

                    @foreach($steps as $step_id => $step)
                        @php
                            $is_done = $current_step > $step_id;
                            $is_active = $current_step == $step_id;
                            $main_color = $step['color'];

                            $circle_class = $is_done
                                ? "bg-{$main_color}-500"
                                : ($is_active ? "bg-pink-500 ring-4 ring-pink-200" : "bg-gray-400");

                            $text_class = $is_done || $is_active ? "text-pink-600 font-bold" : "text-gray-500";
                        @endphp

                        <div class="relative flex flex-col items-center flex-1 z-10">
                            @if(!$loop->last)
                                <div class="absolute top-[20px] right-[-50%] w-full h-1 bg-gray-200 -z-10"></div>
                                @if($is_done || $is_active)
                                    <div class="absolute top-[20px] right-[-50%] w-full h-1 bg-pink-500 -z-10 transition-all duration-500"></div>
                                @endif
                            @endif

                            <div class="w-10 h-10 flex items-center justify-center rounded-full text-white text-base font-bold shadow-md transition-all duration-500 {{ $circle_class }}">
                                @if($is_done)
                                    <i class="fas fa-check"></i>
                                @elseif($is_active)
                                    <i class="fas fa-arrow-right"></i>
                                @else
                                    {{ $loop->index + 1 }}
                                @endif
                            </div>

                            <span class="mt-3 text-center text-xs md:text-sm whitespace-nowrap {{ $text_class }}">
                                {{ $step['label'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>


        {{-- Thông tin giao hàng và thanh toán --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white shadow-xl rounded-2xl border border-pink-100 p-6">
                <h3 class="text-2xl font-bold text-pink-600 mb-4 border-b border-pink-300 pb-2 flex items-center">
                    <i class="fas fa-user-circle mr-3"></i> Thông tin giao hàng
                </h3>
                <div class="space-y-3 text-gray-700">
                    <p class="font-semibold text-gray-800">{{ $order->name }}</p>
                    <p><i class="fas fa-envelope mr-2 text-pink-500"></i> {{ $order->email }}</p>
                    <p><i class="fas fa-phone-alt mr-2 text-pink-500"></i> {{ $order->phone }}</p>
                    <p><i class="fas fa-map-marker-alt mr-2 text-pink-500"></i> {{ $order->address }}</p>
                    <p class="text-sm border-t pt-3 mt-3"><span class="font-semibold">Ghi chú:</span> {{ $order->note ?? 'Không có.' }}</p>
                </div>
            </div>

            <div class="bg-white shadow-xl rounded-2xl border border-pink-100 p-6">
                <h3 class="text-2xl font-bold text-pink-600 mb-4 border-b border-pink-300 pb-2 flex items-center">
                    <i class="fas fa-credit-card mr-3"></i> Thanh toán
                </h3>
                <div class="space-y-3 text-gray-700">
                    <p><span class="font-semibold text-gray-800">Ngày đặt:</span> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    <p><span class="font-semibold text-gray-800">Phương thức:</span> <span class="text-pink-600 font-bold">{{ strtoupper($order->payment_method) }}</span></p>
                    @if($order->payment_proof)
                        <p>
                            <span class="font-semibold text-gray-800">Chứng từ:</span>
                            <a href="{{ asset($order->payment_proof) }}" target="_blank" class="text-blue-600 hover:text-blue-800 underline transition duration-200">Xem hình ảnh</a>
                        </p>
                    @endif
                    <p class="pt-3 border-t mt-3"><span class="font-semibold text-gray-800">Tổng cộng:</span> <span class="text-3xl text-red-600 font-extrabold">{{ number_format(collect($orderDetails)->sum('total'),0,',','.') }}₫</span></p>
                </div>
            </div>
        </div>

        {{-- Danh sách sản phẩm --}}
        <div class="bg-white shadow-xl rounded-2xl border border-pink-100 p-6 md:p-8">
            <h3 class="text-2xl font-bold text-pink-600 mb-6 border-b border-pink-300 pb-2 flex items-center">
                <i class="fas fa-shopping-basket mr-3"></i> Sản phẩm đã đặt
            </h3>

            <div class="space-y-4">
                @foreach($orderDetails as $item)
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between border border-pink-100 rounded-xl p-4 transition duration-300 transform hover:shadow-lg hover:border-pink-300 bg-pink-50/50">
                        <div class="flex items-start space-x-4">
                            <img src="{{ asset('assets/images/product/' . $item['product_image']) }}" 
                                 alt="{{ $item['product_name'] }}" 
                                 class="w-20 h-20 object-cover rounded-lg border border-pink-200 shadow-sm flex-shrink-0">
                            <div class="text-gray-700">
                                <p class="font-bold text-gray-800 text-lg">{{ $item['product_name'] }}</p>
                                <p class="text-sm text-gray-600">
                                    <span class="font-semibold">Đơn giá:</span> {{ number_format($item['price'],0,',','.') }}₫
                                </p>
                                <p class="text-sm text-gray-600">
                                    <span class="font-semibold">Số lượng:</span> <span class="text-pink-600">{{ $item['quantity'] }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="text-right font-extrabold text-pink-700 text-xl mt-3 md:mt-0 md:pl-4">
                            {{ number_format($item['total'],0,',','.') }}₫
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 text-right bg-pink-100/70 p-4 rounded-xl border-t-2 border-pink-300">
                <p class="text-2xl font-bold text-gray-800">
                    Tổng tiền thanh toán: 
                    <span class="text-red-600 text-4xl font-extrabold ml-3">
                        {{ number_format(collect($orderDetails)->sum('total'),0,',','.') }}₫
                    </span>
                </p>
            </div>
        </div>

<div class="flex flex-wrap justify-center gap-4 pt-4">
    <a href="{{ route('account') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-full shadow-lg text-white bg-gray-500 hover:bg-gray-600 transition duration-200">
        <i class="fas fa-arrow-left mr-2"></i> Quay lại Tài khoản
    </a>

    @if($order->status == 4)
        @if(!$hasReviewed)
            <a href="{{ route('order.review', $order->id) }}" 
               class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-full shadow-lg text-white bg-pink-500 hover:bg-pink-600 transition duration-200">
                <i class="fas fa-star mr-2"></i> Đánh giá sản phẩm
            </a>

            {{-- Chỉ cho trả hàng khi chưa đánh giá --}}
            <a href="{{ route('order.return', $order->id) }}" 
               class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-full shadow-lg text-white bg-red-500 hover:bg-red-600 transition duration-200">
                <i class="fas fa-undo-alt mr-2"></i> Trả hàng
            </a>
        @else
            <a href="{{ route('order.review.view', $order->id) }}" 
               class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-full shadow-lg text-white bg-green-500 hover:bg-green-600 transition duration-200">
                <i class="fas fa-eye mr-2"></i> Xem đánh giá
            </a>
            {{-- Khi đã đánh giá, không hiển thị nút trả hàng --}}
        @endif
    @endif
</div>

    </main>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <style>
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
            overflow-x: auto;
        }
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
    </style>
</x-layout-site>
