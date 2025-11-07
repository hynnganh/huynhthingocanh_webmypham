@php
    $chunkedCategories = $category_list->chunk(6); // Chia danh mục thành từng nhóm 6
    $chunkedBrands = $brand_list->chunk(6); // Chia thương hiệu thành từng nhóm 6
@endphp

<x-layout-site>
    <x-slot:title>
        Trang chủ
    </x-slot:title>

<main>
    <section id="index-categories" style="background-color: #ffffff">
        <!-- Banner -->
        <x-banner-list />

        <!-- DANH MỤC -->
        <div class="container mx-auto px-4 py-10 flex justify-center items-center">
            <div class="w-full max-w-5xl overflow-hidden relative">
                <div class="index-heading-wrap text-center mb-10">
                    <h2 class="text-3xl mb-6 text-center font-bold font-mono text-[#F7A7C1] animate-pulse">
                        Danh mục
                    </h2>
                </div>

                <div class="relative">
                    <div id="carousel" class="flex transition-transform duration-500 ease-in-out">
                        @foreach ($chunkedCategories as $group)
                            <div class="category-slide grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-6 xl:grid-cols-6 gap-4 w-full flex-shrink-0">
                                @foreach ($group as $category)
                                    <div class="index-category group flex flex-col items-center border border-gray-200 rounded-lg shadow-md bg-white p-4 hover:shadow-xl transition-all duration-300 hover:scale-105">
                                        <a href="{{ route('site.category.show', ['slug' => $category->slug]) }}" class="block text-center">
                                            <span class="index-category-img mb-4 relative flex justify-center items-center">
                                                <img class="img-fluid transition-transform group-hover:scale-110 w-24 h-24 object-cover"
                                                     alt="{{ $category->name }}"
                                                     src="{{ asset('assets/images/categories/'.$category->image) }}">
                                            </span>
                                            <span class="index-category-title text-base font-semibold text-gray-800 group-hover:text-[#F7A7C1]">
                                                {{ $category->name }}
                                            </span>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Nút điều hướng danh mục -->
                <div class="flex justify-center mt-4">
                    <button onclick="prevCategorySlide()"
                        class="category-btn mx-2 bg-[#F7A7C1] p-2 rounded hover:bg-[#8C1C13] focus:outline-none focus:ring-2 focus:ring-blue-500">❮</button>
                    <button onclick="nextCategorySlide()"
                        class="category-btn mx-2 bg-[#F7A7C1] p-2 rounded hover:bg-[#8C1C13] focus:outline-none focus:ring-2 focus:ring-blue-500">❯</button>
                </div>
            </div>
        </div>

        <!-- Voucher -->
        <x-voucher />

        <!-- Sản phẩm mới -->
        <x-product-new />

        <!-- Bài viết -->
        <x-post-list />

        <!-- Sản phẩm giảm giá -->
        <x-product-sale />

        <!-- THƯƠNG HIỆU -->
        <div class="container mx-auto px-4 py-10 flex justify-center items-center">
            <div class="w-full max-w-5xl overflow-hidden relative">
                <div class="index-heading-wrap text-center mb-10">
                    <h2 class="text-3xl mb-6 text-center font-bold font-mono text-[#F7A7C1] animate-pulse">
                        Thương hiệu
                    </h2>
                </div>

                <div class="relative">
                    <div id="brand-carousel" class="flex transition-transform duration-500 ease-in-out">
                        @foreach ($chunkedBrands as $group)
                            <div class="brand-slide grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-6 xl:grid-cols-6 gap-4 w-full flex-shrink-0">
                                @foreach ($group as $brand)
                                    <div class="index-brand group flex flex-col items-center border border-gray-200 rounded-lg shadow-md bg-white p-4 hover:shadow-xl transition-all duration-300 hover:scale-105">
                                        <a href="{{ route('site.brand.show', ['slug' => $brand->slug]) }}" class="block text-center">
                                            <span class="index-brand-img mb-4 relative flex justify-center items-center">
                                                <img class="img-fluid transition-transform group-hover:scale-110 w-24 h-24 object-contain"
                                                     alt="{{ $brand->name }}"
                                                     src="{{ asset('assets/images/brand/'.$brand->image) }}">
                                            </span>
                                            <span class="index-brand-title text-base font-semibold text-gray-800 group-hover:text-[#F7A7C1]">
                                                {{ $brand->name }}
                                            </span>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Nút điều hướng thương hiệu -->
                <div class="flex justify-center mt-4">
                    <button onclick="prevBrandSlide()" 
                        class="brand-btn mx-2 bg-[#F7A7C1] p-2 rounded hover:bg-[#8C1C13] focus:outline-none focus:ring-2 focus:ring-blue-500">❮</button>
                    <button onclick="nextBrandSlide()" 
                        class="brand-btn mx-2 bg-[#F7A7C1] p-2 rounded hover:bg-[#8C1C13] focus:outline-none focus:ring-2 focus:ring-blue-500">❯</button>
                </div>
            </div>
        </div>

       <div class="container mx-auto px-4 py-10">
    <h2 class="text-3xl font-bold text-center text-[#F7A7C1] mb-6">
        Góc review TikTok
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 justify-items-center">
        <!-- Video 1 -->
        <blockquote class="tiktok-embed" 
            cite="https://www.tiktok.com/@sophia.beauty99/video/7468328481105906952" 
            data-video-id="7468328481105906952" 
            style="max-width: 325px;">
            <section></section>
        </blockquote>

        <!-- Video 2 -->
        <blockquote class="tiktok-embed" 
            cite="https://www.tiktok.com/@hnhu2804/video/7374492734234594568" 
            data-video-id="7374492734234594568" 
            style="max-width: 325px;">
            <section></section>
        </blockquote>

        <!-- Video 3 -->
        <blockquote class="tiktok-embed" 
            cite="https://www.tiktok.com/@quynh_thuhai/video/7302786535718489345" 
            data-video-id="7302786535718489345" 
            style="max-width: 325px;">
            <section></section>
        </blockquote>
    </div>

    <script async src="https://www.tiktok.com/embed.js"></script>
</div>

</div>

    </section>

    <!-- ================= JS SLIDE ================= -->
    <script>
        window.addEventListener('DOMContentLoaded', function () {
            // === CATEGORY SLIDER ===
            let categoryIndex = 0;
            const categoryCarousel = document.getElementById('carousel');
            const categorySlides = document.querySelectorAll('.category-slide');
            const totalCategorySlides = categorySlides.length;

            function showCategorySlide() {
                categoryCarousel.style.transform = `translateX(-${categoryIndex * 100}%)`;
            }

            window.nextCategorySlide = function() {
                categoryIndex = (categoryIndex + 1) % totalCategorySlides;
                showCategorySlide();
            }

            window.prevCategorySlide = function() {
                categoryIndex = (categoryIndex - 1 + totalCategorySlides) % totalCategorySlides;
                showCategorySlide();
            }

            let autoCategorySlide = setInterval(nextCategorySlide, 5000);

            // === BRAND SLIDER ===
            let brandIndex = 0;
            const brandCarousel = document.getElementById('brand-carousel');
            const brandSlides = document.querySelectorAll('.brand-slide');
            const totalBrandSlides = brandSlides.length;

            function showBrandSlide() {
                brandCarousel.style.transform = `translateX(-${brandIndex * 100}%)`;
            }

            window.nextBrandSlide = function() {
                brandIndex = (brandIndex + 1) % totalBrandSlides;
                showBrandSlide();
            }

            window.prevBrandSlide = function() {
                brandIndex = (brandIndex - 1 + totalBrandSlides) % totalBrandSlides;
                showBrandSlide();
            }

            let autoBrandSlide = setInterval(nextBrandSlide, 5000);
        });
    </script>
</main>
</x-layout-site>
