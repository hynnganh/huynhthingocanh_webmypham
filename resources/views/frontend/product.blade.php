<x-layout-site>
    <x-slot:title>Sản phẩm</x-slot:title>

    <main class="bg-gray-50 min-h-screen py-8">
        <div class="container mx-auto px-4">

            {{-- 🔍 Bộ lọc --}}
            <div class="bg-white p-5 rounded-2xl shadow-sm mb-8 border border-gray-100">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <h1 class="text-2xl font-bold text-pink-600">Sản phẩm</h1>

                    <form method="GET" action="{{ route('site.product') }}" id="filterForm"
                        class="flex flex-wrap gap-3 items-center">

                        {{-- ✅ Lọc theo giá (nhập trực tiếp) --}}
                        <div class="flex items-center gap-2 bg-pink-50 border border-pink-200 rounded-lg px-3 py-2">
                            <label class="text-pink-600 font-medium">Giá:</label>
                            <input type="number" name="min" id="min_price" value="{{ request('min') }}"
                                placeholder="Từ"
                                class="w-24 border border-pink-200 rounded px-2 py-1 text-sm 
                                       focus:ring-pink-400 focus:border-pink-400">
                            <span class="text-gray-400">-</span>
                            <input type="number" name="max" id="max_price" value="{{ request('max') }}"
                                placeholder="Đến"
                                class="w-24 border border-pink-200 rounded px-2 py-1 text-sm 
                                       focus:ring-pink-400 focus:border-pink-400">
                        </div>

                        {{-- ✅ Danh mục --}}
                        {{-- THAY ĐỔI: Xóa class 'group', thêm ID và data-dropdown --}}
                        <div class="relative" id="categoryDropdownWrapper">
                            <button type="button" data-dropdown-toggle="category-list"
                                class="flex items-center gap-2 bg-pink-50 border border-pink-200 text-pink-600 font-medium px-4 py-2 rounded-lg hover:bg-pink-100 transition">
                                <span>Danh mục</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            {{-- THAY ĐỔI: Thêm ID, Giữ hidden để ẩn mặc định --}}
                            <div id="category-list"
                                class="absolute hidden bg-white border border-gray-200 rounded-lg shadow-lg mt-2 p-3 max-h-52 overflow-y-auto z-20 w-56">
                                @foreach ($category_list as $category)
                                    <label class="flex items-center gap-2 text-gray-700 hover:text-pink-600 cursor-pointer">
                                        <input type="checkbox" name="category_slug[]" value="{{ $category->slug }}"
                                            {{ in_array($category->slug, (array) request('category_slug', [])) ? 'checked' : '' }}
                                            class="text-pink-500 focus:ring-pink-400 rounded">
                                        <span>{{ $category->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- ✅ Thương hiệu --}}
                        {{-- THAY ĐỔI: Xóa class 'group', thêm ID và data-dropdown --}}
                        <div class="relative" id="brandDropdownWrapper">
                            <button type="button" data-dropdown-toggle="brand-list"
                                class="flex items-center gap-2 bg-pink-50 border border-pink-200 text-pink-600 font-medium px-4 py-2 rounded-lg hover:bg-pink-100 transition">
                                <span>Thương hiệu</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            {{-- THAY ĐỔI: Thêm ID, Giữ hidden để ẩn mặc định --}}
                            <div id="brand-list"
                                class="absolute hidden bg-white border border-gray-200 rounded-lg shadow-lg mt-2 p-3 max-h-52 overflow-y-auto z-20 w-56">
                                @foreach ($brand_list as $brand)
                                    <label class="flex items-center gap-2 text-gray-700 hover:text-pink-600 cursor-pointer">
                                        <input type="checkbox" name="brand_slug[]" value="{{ $brand->slug }}"
                                            {{ in_array($brand->slug, (array) request('brand_slug', [])) ? 'checked' : '' }}
                                            class="text-pink-500 focus:ring-pink-400 rounded">
                                        <span>{{ $brand->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- ✅ Nút áp dụng --}}
                        <button type="submit"
                            class="bg-gradient-to-r from-pink-500 to-pink-600 text-white font-semibold py-2 px-5 rounded-lg shadow hover:from-pink-600 hover:to-pink-700 transition">
                            Áp dụng
                        </button>

                        {{-- ❌ Nút xóa bộ lọc --}}
                        <a href="{{ route('site.product') }}"
                            class="bg-gray-100 border border-gray-300 text-gray-600 font-medium py-2 px-5 rounded-lg shadow hover:bg-gray-200 transition">
                            Xóa bộ lọc
                        </a>
                    </form>
                </div>
            </div>

            {{-- 💄 Danh sách sản phẩm --}}
            <section>
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg text-gray-700 font-medium hidden lg:block">
                        Hiển thị {{ $product_list->count() }} / {{ $product_list->total() }} sản phẩm
                    </h2>

                    {{-- Bộ sắp xếp (Giữ nguyên) --}}
                    <form method="GET" action="{{ route('site.product') }}" id="sortForm"
                        class="flex items-center gap-2 ml-auto">
                        <label for="sort" class="text-sm text-gray-600">Sắp xếp:</label>
                        <select name="sort" id="sort" onchange="this.form.submit()"
                            class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-pink-400">
                            <option value="">Mới nhất</option>
                            <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Giá: Thấp → Cao</option>
                            <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Giá: Cao → Thấp</option>
                        </select>

                        {{-- Giữ lại filter --}}
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

                {{-- Hiển thị sản phẩm --}}
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

        {{-- ⚙️ Script click dropdown + validate giá --}}
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                // Logic cho Dropdown (sử dụng click)
                const dropdownButtons = document.querySelectorAll('[data-dropdown-toggle]');

                dropdownButtons.forEach(button => {
                    button.addEventListener('click', (e) => {
                        e.stopPropagation(); // Ngăn sự kiện click lan ra ngoài
                        
                        const targetId = button.getAttribute('data-dropdown-toggle');
                        const dropdown = document.getElementById(targetId);

                        // Đóng tất cả các dropdown khác
                        document.querySelectorAll('.absolute').forEach(d => {
                            if (d.id !== targetId) {
                                d.classList.add('hidden');
                            }
                        });

                        // Mở/Đóng dropdown hiện tại
                        dropdown.classList.toggle('hidden');
                    });
                });
                
                // Đóng dropdown khi click bên ngoài
                document.addEventListener('click', () => {
                    document.querySelectorAll('.absolute').forEach(d => {
                        d.classList.add('hidden');
                    });
                });

                // Giữ dropdown mở khi click bên trong nó
                document.querySelectorAll('.absolute').forEach(dropdown => {
                    dropdown.addEventListener('click', (e) => {
                        e.stopPropagation();
                    });
                });


                // Hoán đổi giá nếu nhập ngược (Giữ nguyên logic này)
                const minInput = document.getElementById('min_price');
                const maxInput = document.getElementById('max_price');
                const form = document.getElementById('filterForm');

                form.addEventListener('submit', (e) => {
                    let min = parseInt(minInput.value || 0);
                    let max = parseInt(maxInput.value || 0);
                    if (min > 0 && max > 0 && min > max) {
                        [minInput.value, maxInput.value] = [max, min];
                    }
                });
            });
        </script>
    </main>
</x-layout-site>