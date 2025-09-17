<x-layout-site>
    <x-slot:title>
        Sản phẩm thuộc danh mục {{ $category->name }}
    </x-slot:title>
    
    <main class="py-10 bg-gray-50">
        <div class="container mx-auto px-4">
            
            <!-- Tiêu đề trang -->
            <h1 class="text-3xl font-bold text-center text-[#F7A7C1] mb-8">Sản phẩm trong danh mục "{{ $category->name }}"</h1>

            <!-- Hiển thị thông báo lỗi nếu có -->
            @if(session('error'))
                <div class="alert alert-danger mb-4 p-4 bg-red-100 text-red-700 rounded-md">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Hiển thị sản phẩm thuộc danh mục -->
            <div class="products">
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
        </div>
    </main>
</x-layout-site>
