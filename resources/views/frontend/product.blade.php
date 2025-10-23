<x-layout-site>
    <x-slot:title>
        Sản phẩm
    </x-slot:title>
    <main>
        <div class="container mx-auto my-8 px-2">
            
            <div class="bg-white p-4 rounded-xl shadow-lg mb-8 border border-gray-100">
                
                {{-- Tiêu đề và nút mở bộ lọc (cho mobile) --}}
                <div class="flex justify-between items-center mb-4 lg:hidden">
                    <h1 class="text-2xl font-bold text-pink-600">Sản phẩm</h1>
                    <button id="openFilterBtn" onclick="document.getElementById('filter-wrapper').classList.toggle('hidden')" class="bg-pink-500 text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-pink-600 transition flex items-center gap-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707v3.586L9.414 20a1 1 0 01-.293.707l-6.414-6.414A1 1 0 013 13.586V4z"></path></svg>
                        Lọc
                    </button>
                </div>

                {{-- Bộ lọc được ẩn trên mobile và hiển thị trên màn hình lớn --}}
                <div id="filter-wrapper" class="hidden lg:block">
                    <form method="GET" action="{{ route('site.product') }}" id="filterForm" class="space-y-4 lg:space-y-0 lg:flex lg:gap-4 lg:items-end">
                        
                        {{-- Giữ lại tham số sắp xếp (sort) --}}
                        @if(request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif

                        <div class="lg:w-1/4 flex-shrink-0">
                            <details class="border rounded-lg overflow-hidden bg-white" open>
                                <summary class="flex justify-between items-center p-3 bg-gray-50 cursor-pointer font-semibold text-pink-600 hover:bg-gray-100 transition">
                                    Lọc theo giá
                                    <svg class="w-4 h-4 text-pink-600 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </summary>
                                <div class="p-4 space-y-4">
                                    <div id="price-slider" class="mt-2"></div>
                                    <input type="hidden" id="min_price" name="min" value="{{ request('min', 0) }}">
                                    <input type="hidden" id="max_price" name="max" value="{{ request('max', 10000000) }}">
                                    <div class="flex justify-between text-sm font-medium text-gray-600 mt-4">
                                        <span id="min-label" class="text-pink-700 font-bold"></span>
                                        <span id="max-label" class="text-pink-700 font-bold"></span>
                                    </div>
                                </div>
                            </details>
                        </div>

                        <div class="lg:w-1/4 flex-shrink-0">
                            <details class="border rounded-lg overflow-hidden bg-white">
                                <summary class="flex justify-between items-center p-3 bg-gray-50 cursor-pointer font-semibold text-pink-600 hover:bg-gray-100 transition">
                                    Danh mục
                                    <svg class="w-4 h-4 text-pink-600 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </summary>
                                {{-- x-category-list cần trả về input name="category_slug[]" --}}
                                <div class="p-4 max-h-48 overflow-y-auto space-y-2">
                                    <x-category-list /> 
                                </div>
                            </details>
                        </div>

                        <div class="lg:w-1/4 flex-shrink-0">
                            <details class="border rounded-lg overflow-hidden bg-white">
                                <summary class="flex justify-between items-center p-3 bg-gray-50 cursor-pointer font-semibold text-pink-600 hover:bg-gray-100 transition">
                                    Thương hiệu
                                    <svg class="w-4 h-4 text-pink-600 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </summary>
                                {{-- x-brand-list cần trả về input name="brand_slug[]" --}}
                                <div class="p-4 max-h-48 overflow-y-auto space-y-2">
                                    <x-brand-list />
                                </div>
                            </details>
                        </div>
                        
                        {{-- Nút lọc chung --}}
                        <div class="lg:w-1/4">
                            <button type="submit" class="w-full bg-gradient-to-r from-pink-500 to-pink-600 text-white font-semibold py-3 px-6 rounded-lg shadow-md hover:from-pink-600 hover:to-pink-700 transition lg:mt-0">
                                Áp dụng Bộ lọc
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            
            <section class="w-full">
                <div class="flex justify-between items-center mb-6 px-1">
                    {{-- Tiêu đề chỉ hiện trên màn hình lớn --}}
                    <h2 class="text-2xl font-mono text-gray-700 hidden lg:block">Hiển thị {{ $product_list->count() }} sản phẩm</h2>

                    <form method="GET" action="{{ route('site.product') }}" id="sortForm" class="flex items-center space-x-2 ml-auto">
                        <label for="sort" class="text-sm text-gray-600">Sắp xếp theo:</label>
                        <select name="sort" id="sort" onchange="this.form.submit()"
                            class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-pink-400">
                            <option value="">Mới nhất</option>
                            <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                            <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
                        </select>

                        {{-- GIỮ LẠI CÁC THAM SỐ LỌC KHI THAY ĐỔI SẮP XẾP --}}
                        @foreach(request()->except(['sort', 'page']) as $key => $value)
                            @if(is_array($value))
                                @foreach($value as $v)
                                    <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                    </form>
                </div>

                @if ($product_list->count())
                    {{-- Sử dụng grid 4 cột trên màn hình lớn --}}
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                        @foreach ($product_list as $product)
                            <x-product-card :productrow="$product" />
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $product_list->withQueryString()->links() }}
                    </div>
                @else
                    <div class="bg-white p-6 rounded-xl shadow-md">
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

                const formatter = new Intl.NumberFormat('vi-VN', {
                    style: 'currency',
                    currency: 'VND',
                    minimumFractionDigits: 0,
                });

                const updateLabels = (values) => {
                    minInput.value = values[0];
                    maxInput.value = values[1];
                    minLabel.innerText = formatter.format(values[0]);
                    maxLabel.innerText = formatter.format(values[1]);
                };
                
                updateLabels(slider.noUiSlider.get());

                slider.noUiSlider.on('update', function(values) {
                    updateLabels(values);
                });
                
                // Xử lý icon xoay và trạng thái mở/đóng của details
                document.querySelectorAll('details').forEach(detail => {
                    const summary = detail.querySelector('summary');
                    const icon = summary.querySelector('svg');
                    
                    if (detail.open) {
                        icon.classList.add('rotate-180');
                    }

                    detail.addEventListener('toggle', () => {
                        if (detail.open) {
                            icon.classList.add('rotate-180');
                        } else {
                            icon.classList.remove('rotate-180');
                        }
                    });
                });
            });
        </script>

        <style>
            /* Style cho noUiSlider */
            #price-slider .noUi-connect {
                background: linear-gradient(to right, #ec4899, #db2777);
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
                list-style: none;
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
            /* Ẩn filter wrapper trên mobile theo mặc định */
            @media (max-width: 1023px) {
                #filter-wrapper {
                    /* Khi JS toggle, sẽ bỏ hidden */
                    display: none; 
                }
            }
        </style>
    </main>
</x-layout-site>