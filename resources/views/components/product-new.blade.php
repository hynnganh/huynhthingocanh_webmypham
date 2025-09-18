<div class="container mx-auto my-8 px-4">
    <div class="bg-[#ffffff] p-6 border border-gray-300 rounded-lg">
        <h1 class="text-3xl mb-6 text-center font-bold font-mono text-[#FF66B2] animate-pulse">Sản phẩm mới</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            @foreach ($product_list as $product_item)
                <x-product-card :productrow="$product_item" />
            @endforeach
        </div>
    </div>
</div>
