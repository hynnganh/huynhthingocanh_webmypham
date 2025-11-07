<x-layout-site>
    <x-slot:title>
        {{ $brand->name }}
    </x-slot:title>

    <div class="container mx-auto py-10 px-4">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold text-[#F7A7C1]">{{ $brand->name }}</h1>
        </div>

        <div class="products ">
                @if($products->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($products as $product)
                        <x-product-card :productrow="$product" />
                    @endforeach
                </div>

                    <!-- Phân trang -->
                    <div class="pagination mt-6 text-center">
                        {{ $products->links() }}
                    </div>
                @else
                    <p class="text-center text-gray-600">Không có sản phẩm nào trong danh mục này.</p>
                @endif
            </div>

        <div class="mt-8">
            {{ $products->links('pagination::tailwind') }}
        </div>
    </div>
</x-layout-site>
