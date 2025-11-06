@php
    $chunkedCategories = $category_list->chunk(6); // Chia thành từng nhóm 6 danh mục
@endphp

<x-layout-site>
    <x-slot:title>
        Trang chủ
    </x-slot:title>
<main>
    <section id="index-categories" style="background-color: #ffffff">
        <x-banner-list />
        <div class="container mx-auto px-4 py-10 flex justify-center items-center">
            <div class="w-full max-w-5xl overflow-hidden relative">
                <div class="index-heading-wrap text-center mb-10">
                    <h2 class="text-3xl mb-6 text-center font-bold font-mono text-[#F7A7C1] animate-pulse">Danh mục</h2>
                </div>

                <div class="relative">
                    <div id="carousel" class="flex transition-transform duration-500 ease-in-out">
                        <!-- Carousel Slides -->
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

                <!-- Carousel Navigation -->
                <div class="flex justify-center mt-4">
                    <button onclick="prevSlide()"
                        class="carousel-btn mx-2 bg-[#F7A7C1] p-2 rounded hover:bg-[#8C1C13] focus:outline-none focus:ring-2 focus:ring-blue-500">❮</button>
                    <button onclick="nextSlide()"
                        class="carousel-btn mx-2 bg-[#F7A7C1] p-2 rounded hover:bg-[#8C1C13] focus:outline-none focus:ring-2 focus:ring-blue-500">❯</button>
                </div>
            </div>
        </div>
            <x-voucher />
            <x-product-new />
            <x-post-list />
            <x-product-sale />
            
    </section>

    <script>
        window.addEventListener('DOMContentLoaded', function () {
            let index = 0;
            const categoryCarousel = document.getElementById('carousel');
            const slides = document.querySelectorAll('.category-slide');
            const totalSlides = slides.length;
    
            function showCategorySlide() {
                categoryCarousel.style.transform = `translateX(-${index * 100}%)`;
            }
    
            function nextCategorySlide() {
                index = (index + 1) % totalSlides;
                showCategorySlide();
            }
    
            function prevCategorySlide() {
                index = (index - 1 + totalSlides) % totalSlides;
                showCategorySlide();
            }
    
            // Gán sự kiện cho nút
            document.querySelectorAll('.carousel-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    clearInterval(autoCategorySlide);
                });
            });
    
            document.querySelector('.carousel-btn:nth-child(1)').addEventListener('click', prevCategorySlide);
            document.querySelector('.carousel-btn:nth-child(2)').addEventListener('click', nextCategorySlide);
    
            let autoCategorySlide = setInterval(nextCategorySlide, 5000);
        });
    </script>
</main>
</x-layout-site>
