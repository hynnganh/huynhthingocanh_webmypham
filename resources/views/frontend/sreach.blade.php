<x-layout-site>
    <x-slot:title>
        Sản phẩm
    </x-slot:title>
    <main>


        @if ($products->isEmpty())
            <p class="text-center text-gray-500 p-10">Không có sản phẩm nào phù hợp với từ khóa tìm kiếm.</p>
        @else
            <div class="container mx-auto my-8 px-4">

                <div class="bg-[#ffffff] p-6 border border-gray-300 rounded-lg">
                    <h1 class="text-3xl mb-6 text-center font-bold font-mono text-[#FF66B2] animate-pulse">Sản phẩm tìm được
                    </h1>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach ($products as $product)
                            <x-product-card :productrow="$product" />
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </main>

</x-layout-site>
