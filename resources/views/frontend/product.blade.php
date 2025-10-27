<x-layout-site>
    <x-slot:title>
        Sản phẩm
    </x-slot:title>

    <main class="bg-gray-50 min-h-screen py-8">
        <div class="container mx-auto px-4">

            {{-- 🔍 Bộ lọc --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm mb-8 border border-gray-100">
                <div class="flex justify-between items-center mb-4 lg:hidden">
                    <h1 class="text-2xl font-bold text-pink-600">Sản phẩm</h1>
                    <button id="openFilterBtn"
                        onclick="document.getElementById('filter-wrapper').classList.toggle('hidden')"
                        class="bg-pink-500 text-white font-medium py-2 px-4 rounded-lg shadow hover:bg-pink-600 transition flex items-center gap-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4h18M4 8h16M5 12h14M6 16h12M7 20h10"></path>
                        </svg>
                        Bộ lọc
                    </button>
                </div>

                {{-- Bộ lọc tổng hợp --}}
                <div id="filter-wrapper" class="hidden lg:block">
                    <form method="GET" action="{{ route('site.product') }}" id="filterForm"
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                        {{-- Giữ lại sort --}}
                        @if(request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif

                        {{-- Lọc theo giá --}}
                        <div class="border rounded-xl bg-white overflow-hidden">
                            <details open>
                                <summary
                                    class="flex justify-between items-center p-3 cursor-pointer bg-gray-50 font-semibold text-pink-600 hover:bg-gray-100">
                                    Giá
                                    <svg class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </summary>
                                <div class="p-4">
                                    <div id="price-slider" class="mt-2"></div>
                                    <input type="hidden" id="min_price" name="min" value="{{ request('min', 0) }}">
                                    <input type="hidden" id="max_price" name="max" value="{{ request('max', 10000000) }}">
                                    <div class="flex justify-between text-sm font-medium text-gray-600 mt-4">
                                        <span id="min-label"></span>
                                        <span id="max-label"></span>
                                    </div>
                                </div>
                            </details>
                        </div>

                        {{-- Danh mục --}}
                        <div class="border rounded-xl bg-white overflow-hidden">
                            <details>
                                <summary
                                    class="flex justify-between items-center p-3 cursor-pointer bg-gray-50 font-semibold text-pink-600 hover:bg-gray-100">
                                    Danh mục
                                    <svg class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </summary>
                                <div class="p-4 max-h-48 overflow-y-auto space-y-2">
                                    @foreach ($category_list as $category)
                                        <label class="flex items-center gap-2 text-gray-700">
                                            <input type="checkbox" name="category_slug[]" value="{{ $category->slug }}"
                                                {{ in_array($category->slug, (array) request('category_slug', [])) ? 'checked' : '' }}
                                                class="text-pink-500 focus:ring-pink-400 rounded">
                                            <span>{{ $category->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </details>
                        </div>

                        {{-- Thương hiệu --}}
                        <div class="border rounded-xl bg-white overflow-hidden">
                            <details>
                                <summary
                                    class="flex justify-between items-center p-3 cursor-pointer bg-gray-50 font-semibold text-pink-600 hover:bg-gray-100">
                                    Thương hiệu
                                    <svg class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </summary>
                                <div class="p-4 max-h-48 overflow-y-auto space-y-2">
                                    @foreach ($brand_list as $brand)
                                        <label class="flex items-center gap-2 text-gray-700">
                                            <input type="checkbox" name="brand_slug[]" value="{{ $brand->slug }}"
                                                {{ in_array($brand->slug, (array) request('brand_slug', [])) ? 'checked' : '' }}
                                                class="text-pink-500 focus:ring-pink-400 rounded">
                                            <span>{{ $brand->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </details>
                        </div>

                        {{-- Nút áp dụng --}}
                        <div class="flex items-end">
                            <button type="submit"
                                class="w-full bg-gradient-to-r from-pink-500 to-pink-600 text-white font-semibold py-3 rounded-lg shadow hover:from-pink-600 hover:to-pink-700 transition">
                                Áp dụng
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- 💄 Danh sách sản phẩm --}}
            <section>
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg text-gray-700 font-medium hidden lg:block">
                        Hiển thị {{ $product_list->count() }} / {{ $product_list->total() }} sản phẩm
                    </h2>

                    {{-- Bộ sắp xếp --}}
                    <form method="GET" action="{{ route('site.product') }}" id="sortForm"
                        class="flex items-center gap-2 ml-auto">
                        <label for="sort" class="text-sm text-gray-600">Sắp xếp:</label>
                        <select name="sort" id="sort" onchange="this.form.submit()"
                            class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-pink-400">
                            <option value="">Mới nhất</option>
                            <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Giá: Thấp → Cao</option>
                            <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Giá: Cao → Thấp</option>
                        </select>

                        {{-- Giữ lại filter khi đổi sắp xếp --}}
                        @foreach(request()->except(['sort','page']) as $key => $value)
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
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                        @foreach ($product_list as $product)
                            <x-product-card :productrow="$product" />
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $product_list->onEachSide(1)->links() }}
                    </div>
                @else
                    <div class="bg-white p-8 rounded-xl shadow text-center text-gray-500">
                        😔 Không tìm thấy sản phẩm nào phù hợp.
                    </div>
                @endif
            </section>
        </div>

        {{-- 📦 noUiSlider --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const slider = document.getElementById('price-slider');
                const minInput = document.getElementById('min_price');
                const maxInput = document.getElementById('max_price');
                const minLabel = document.getElementById('min-label');
                const maxLabel = document.getElementById('max-label');

                const startMin = Number(minInput.value) || 0;
                const startMax = Number(maxInput.value) || 10000000;

                noUiSlider.create(slider, {
                    start: [startMin, startMax],
                    connect: true,
                    step: 10000,
                    range: { 'min': 0, 'max': 10000000 },
                    format: {
                        to: v => Math.round(v),
                        from: v => Number(v)
                    }
                });

                const fmt = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND', minimumFractionDigits: 0 });

                const updateLabels = (values) => {
                    minInput.value = values[0];
                    maxInput.value = values[1];
                    minLabel.textContent = fmt.format(values[0]);
                    maxLabel.textContent = fmt.format(values[1]);
                };

                updateLabels(slider.noUiSlider.get());
                slider.noUiSlider.on('update', (values) => updateLabels(values));
            });
        </script>

        <style>
            #price-slider .noUi-connect {
                background: linear-gradient(to right, #ec4899, #db2777);
            }

            #price-slider .noUi-handle {
                border-radius: 50%;
                background: #fff;
                border: 2px solid #ec4899;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
                width: 18px;
                height: 18px;
            }

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

            @media (max-width: 1023px) {
                #filter-wrapper {
                    display: none;
                }
            }
        </style>
    </main>
</x-layout-site>
