<x-layout-site>
    <x-slot:title>
        Sản phẩm
    </x-slot:title>
    <main>
        <div class="container mx-auto my-8 px-2">
            <div class="flex flex-col lg:flex-row gap-6">
                <aside class="w-full lg:w-1/3 bg-white p-4 rounded-lg shadow-md border max-h-[600px] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300">
                    <h2 class="text-xl font-bold mb-4 text-pink-700">Bộ Lọc</h2>

                    {{-- Form này sẽ chứa tất cả các bộ lọc: Giá, Danh mục, Thương hiệu --}}
                    <form method="GET" action="{{ route('site.product') }}" id="filterForm" class="space-y-4">
                        {{-- Giữ lại các query strings hiện có (trừ min, max, category_slug, brand_slug đã được xử lý bên dưới) --}}
                        {{-- Giữ lại tham số sắp xếp (sort) --}}
                        @if(request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif

                        <details class="mb-4 border rounded-lg overflow-hidden" open>
                            <summary class="flex justify-between items-center p-3 bg-gray-50 cursor-pointer font-semibold text-pink-600">
                                Lọc theo giá
                                <svg class="w-4 h-4 text-pink-600 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </summary>
                            <div class="p-4 space-y-4">
                                <div id="price-slider" class="mt-2"></div>

                                <input type="hidden" id="min_price" name="min" value="{{ request('min', 0) }}">
                                <input type="hidden" id="max_price" name="max" value="{{ request('max', 10000000) }}">

                                <div class="flex justify-between text-sm font-medium mt-3 text-gray-600">
                                    <span id="min-label" class="text-pink-700 font-bold"></span>
                                    <span id="max-label" class="text-pink-700 font-bold"></span>
                                </div>
                            </div>
                        </details>

                        <details class="mb-4 border rounded-lg overflow-hidden" open>
                            <summary class="flex justify-between items-center p-3 bg-gray-50 cursor-pointer font-semibold text-pink-600">
                                Danh mục sản phẩm
                                <svg class="w-4 h-4 text-pink-600 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </summary>
                            {{-- Giả định x-category-list chứa các input có name="category_slug[]" --}}
                            <div class="p-4">
                                <x-category-list />
                            </div>
                        </details>

                        <details class="mb-4 border rounded-lg overflow-hidden" open>
                            <summary class="flex justify-between items-center p-3 bg-gray-50 cursor-pointer font-semibold text-pink-600">
                                Thương hiệu
                                <svg class="w-4 h-4 text-pink-600 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </summary>
                            {{-- Giả định x-brand-list chứa các input có name="brand_slug[]" --}}
                            <div class="p-4">
                                <x-brand-list />
                            </div>
                        </details>
                        
                        {{-- Nút lọc chung cho tất cả bộ lọc --}}
                        <div class="p-2 pt-0">
                            <button type="submit" class="w-full bg-gradient-to-r from-pink-500 to-pink-600 text-white font-semibold py-2 rounded-lg shadow hover:from-pink-600 hover:to-pink-700 transition">
                                Áp dụng Bộ lọc
                            </button>
                        </div>
                    </form>
                </aside>
                
                <section class="w-full lg:w-2/3">
                    <div class="flex justify-between items-center mb-6 bg-white p-4 rounded-lg shadow-md">
                        <h1 class="text-3xl font-mono text-pink-600">Sản phẩm</h1>

                        {{-- Dùng form riêng cho Sắp xếp để không cần nút submit, tự động gửi khi thay đổi --}}
                        <form method="GET" action="{{ route('site.product') }}" id="sortForm" class="flex items-center space-x-2">
                            <label for="sort" class="text-sm text-gray-600 hidden sm:block">Sắp xếp theo:</label>
                            <select name="sort" id="sort" onchange="this.form.submit()"
                                class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-pink-400">
                                <option value="">Mặc định</option>
                                <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                                <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
                            </select>

                            {{-- GIỮ LẠI CÁC THAM SỐ LỌC KHI THAY ĐỔI SẮP XẾP --}}
                            @if(request('category_slug'))
                                {{-- Lặp qua mảng nếu category_slug là mảng --}}
                                @foreach((array)request('category_slug') as $slug)
                                    <input type="hidden" name="category_slug[]" value="{{ $slug }}">
                                @endforeach
                            @endif
                            @if(request('brand_slug'))
                                {{-- Lặp qua mảng nếu brand_slug là mảng --}}
                                @foreach((array)request('brand_slug') as $slug)
                                    <input type="hidden" name="brand_slug[]" value="{{ $slug }}">
                                @endforeach
                            @endif
                            @if(request('min'))
                                <input type="hidden" name="min" value="{{ request('min') }}">
                            @endif
                            @if(request('max'))
                                <input type="hidden" name="max" value="{{ request('max') }}">
                            @endif
                        </form>
                    </div>

                    @if ($product_list->count())
                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                            @foreach ($product_list as $product)
                                <x-product-card :productrow="$product" />
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $product_list->withQueryString()->links() }}
                        </div>
                    @else
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <p class="text-center text-gray-500 py-10">
                                😔 Rất tiếc, không có sản phẩm nào phù hợp với bộ lọc của bạn. Vui lòng thử lại!
                            </p>
                        </div>
                    @endif
                </section>
            </div>

            <link href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.css" rel="stylesheet">
            <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.js"></script>

            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    let slider = document.getElementById('price-slider');
                    let minInput = document.getElementById('min_price');
                    let maxInput = document.getElementById('max_price');
                    let minLabel = document.getElementById('min-label');
                    let maxLabel = document.getElementById('max-label');

                    // Lấy giá trị ban đầu, đảm bảo là số
                    let startMin = Number(minInput.value) || 0;
                    let startMax = Number(maxInput.value) || 10000000;

                    noUiSlider.create(slider, {
                        start: [startMin, startMax],
                        connect: true,
                        step: 10000,
                        range: {
                            'min': 0,
                            'max': 10000000
                        },
                        format: {
                            to: value => Math.round(value),
                            from: value => Number(value)
                        }
                    });

                    // Định dạng tiền tệ
                    const formatter = new Intl.NumberFormat('vi-VN', {
                        style: 'currency',
                        currency: 'VND',
                        minimumFractionDigits: 0,
                    });

                    // Hàm cập nhật nhãn và input
                    const updateLabels = (values) => {
                        minInput.value = values[0];
                        maxInput.value = values[1];
                        minLabel.innerText = formatter.format(values[0]);
                        maxLabel.innerText = formatter.format(values[1]);
                    };
                    
                    // Cập nhật giá trị ban đầu khi slider được tạo
                    updateLabels(slider.noUiSlider.get());


                    // Update khi kéo
                    slider.noUiSlider.on('update', function(values) {
                        updateLabels(values);
                    });
                    
                    // Thêm class cho details để xử lý animation/icon nếu cần
                    document.querySelectorAll('details').forEach(detail => {
                        const summary = detail.querySelector('summary');
                        summary.addEventListener('click', () => {
                            // Cập nhật icon mũi tên xoay
                            const icon = summary.querySelector('svg');
                            if (detail.open) {
                                icon.classList.remove('rotate-180');
                            } else {
                                icon.classList.add('rotate-180');
                            }
                        });
                        // Đặt trạng thái ban đầu cho icon nếu details mở
                        if (detail.open) {
                            summary.querySelector('svg').classList.add('rotate-180');
                        }
                    });
                });
            </script>

            <style>
                /* Style cho noUiSlider */
                #price-slider .noUi-connect {
                    background: linear-gradient(to right, #ec4899, #db2777); /* màu hồng */
                }
                #price-slider .noUi-handle {
                    border-radius: 50%;
                    background: #fff;
                    border: 2px solid #ec4899;
                    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
                    width: 18px;
                    height: 18px;
                }
                #price-slider {
                    margin-top: 15px;
                }
                /* Style cho Accordion (Details) */
                details summary {
                    list-style: none; /* Ẩn dấu mũi tên mặc định của trình duyệt */
                }
                details summary::-webkit-details-marker {
                    display: none;
                }
                details summary svg {
                    transition: transform 0.3s ease;
                }
                details[open] summary svg {
                    transform: rotate(180deg);
                }
            </style>
        </div>
    </main>
</x-layout-site>