<x-layout-admin>
    <div class="flex justify-between items-center bg-white p-4 rounded-lg shadow">
        <h1 class="text-lg font-bold">Trang Quản Trị</h1>
    </div>

    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <!-- Tổng số đơn hàng -->
        <div class="bg-blue-500 p-4 rounded-lg text-white">
            <h2 class="text-xl font-bold">Tổng số đơn hàng</h2>
            <p class="text-3xl">{{ $totalOrders }}</p>
        </div>

        <!-- Tổng số sản phẩm -->
        <div class="bg-green-500 p-4 rounded-lg text-white">
            <h2 class="text-xl font-bold">Tổng số sản phẩm</h2>
            <p class="text-3xl">{{ $totalProducts }}</p>
        </div>

        <!-- Tổng số người dùng -->
        <div class="bg-yellow-500 p-4 rounded-lg text-white">
            <h2 class="text-xl font-bold">Tổng số người dùng</h2>
            <p class="text-3xl">{{ $totalUsers }}</p>
        </div>

        <!-- Tổng số danh mục -->
        <div class="bg-purple-500 p-4 rounded-lg text-white">
            <h2 class="text-xl font-bold">Tổng số danh mục</h2>
            <p class="text-3xl">{{ $totalCategories }}</p>
        </div>

        <!-- Tổng số thương hiệu -->
        <div class="bg-red-500 p-4 rounded-lg text-white">
            <h2 class="text-xl font-bold">Tổng số thương hiệu</h2>
            <p class="text-3xl">{{ $totalBrands }}</p>
        </div>
    </div>
</x-layout-admin>
