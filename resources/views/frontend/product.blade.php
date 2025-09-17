<x-layout-site>
    <x-slot:title>
        Sản phẩm
    </x-slot:title>
    <main>
        <div class="container mx-auto my-8 px-4">
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Sidebar: Cột trái -->
                <aside
                    class="w-full lg:w-1/4 bg-white p-4 rounded-lg shadow-md border max-h-[400px] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300">
                    <x-category-list />
                    <x-brand-list />
                </aside>

                <section class="w-full lg:w-3/4">
                    <h1 class="text-3xl mb-6 text-center font-mono text-pink-600">Sản phẩm</h1>

                    @if ($product_list->count())
                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                            @foreach ($product_list as $product)
                                <x-product-card :productrow="$product" />
                            @endforeach
                        </div>

                        {{-- Phân trang --}}
                        <div class="mt-6">
                            {{ $product_list->withQueryString()->links() }}
                        </div>
                    @else
                        <p class="text-center text-gray-500">Không có sản phẩm nào phù hợp.</p>
                    @endif
                </section>

            </div>
    </main>

</x-layout-site>
