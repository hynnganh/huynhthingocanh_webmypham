<x-layout-site>
    <x-slot:title>
        Sản phẩm
    </x-slot:title>
    <main>
        <div class="container mx-auto my-8 px-2">
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Sidebar -->
                <aside
                    class="w-full lg:w-1/3 bg-white p-4 rounded-lg shadow-md border max-h-[600px] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300">

                    <!-- Bộ lọc giá -->
                    <div class="mb-6">
                        <h2 class="text-lg font-semibold mb-4 text-pink-600">Lọc theo giá</h2>
                        <form method="GET" action="{{ route('site.product') }}" class="space-y-4" id="priceFilterForm">
                            @if(request('category_slug'))
                                <input type="hidden" name="category_slug" value="{{ request('category_slug') }}">
                            @endif
                            @if(request('brand_slug'))
                                <input type="hidden" name="brand_slug" value="{{ request('brand_slug') }}">
                            @endif

                            <!-- Slider -->
                            <div id="price-slider" class="mt-2"></div>

                            <!-- Input ẩn -->
                            <input type="hidden" id="min_price" name="min" value="{{ request('min', 0) }}">
                            <input type="hidden" id="max_price" name="max" value="{{ request('max', 10000000) }}">

                            <!-- Hiển thị giá -->
                            <div class="flex justify-between text-sm font-medium mt-3 text-gray-600">
                                <span id="min-label"></span>
                                <span id="max-label"></span>
                            </div>

                            <button type="submit"class="ml-3 bg-gradient-to-r from-pink-500 to-pink-600 text-white font-semibold py-2 px-4 rounded-lg shadow hover:from-pink-600 hover:to-pink-700 transition">
                                    Lọc
                            </button>
                        </form>
                    </div>

                    <!-- Category -->
                    <x-category-list />

                    <!-- Brand -->
                    <x-brand-list />
                </aside>

                <!-- Danh sách sản phẩm -->
                <section class="w-full lg:w-2/3">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-3xl font-mono text-pink-600">Sản phẩm</h1>

                        <!-- Dropdown sắp xếp -->
                        <form method="GET" action="{{ route('site.product') }}" class="flex items-center space-x-2">
                            <label for="sort" class="text-sm text-gray-600">Sắp xếp:</label>
                            <select name="sort" id="sort" onchange="this.form.submit()"
                                class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-pink-400">
                                <option value="">Mặc định</option>
                                <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Giá tăng dần</option>
                                <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Giá giảm dần</option>
                            </select>

                            {{-- Giữ lại query --}}
                            @if(request('category_slug'))
                                <input type="hidden" name="category_slug" value="{{ request('category_slug') }}">
                            @endif
                            @if(request('brand_slug'))
                                <input type="hidden" name="brand_slug" value="{{ request('brand_slug') }}">
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

                        <!-- Phân trang -->
                        <div class="mt-6">
                            {{ $product_list->withQueryString()->links() }}
                        </div>
                    @else
                        <p class="text-center text-gray-500">Không có sản phẩm nào phù hợp.</p>
                    @endif
                </section>
            </div>

            <!-- noUiSlider CSS & JS -->
            <link href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.css" rel="stylesheet">
            <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.js"></script>

            <!-- Custom JS -->
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    let slider = document.getElementById('price-slider');
                    let minInput = document.getElementById('min_price');
                    let maxInput = document.getElementById('max_price');
                    let minLabel = document.getElementById('min-label');
                    let maxLabel = document.getElementById('max-label');

                    noUiSlider.create(slider, {
                        start: [minInput.value || 0, maxInput.value || 10000000],
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

                    // Update khi kéo
                    slider.noUiSlider.on('update', function(values) {
                        minInput.value = values[0];
                        maxInput.value = values[1];
                        minLabel.innerText = new Intl.NumberFormat('vi-VN').format(values[0]) + 'đ';
                        maxLabel.innerText = new Intl.NumberFormat('vi-VN').format(values[1]) + 'đ';
                    });
                });
            </script>

            <!-- Tuỳ chỉnh style slider -->
            <style>
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
            </style>
        </div>
    </main>
</x-layout-site>

