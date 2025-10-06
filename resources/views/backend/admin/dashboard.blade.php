<x-layout-admin>
    <div class="flex justify-between items-center bg-white p-4 rounded-lg shadow">
        <h1 class="text-lg font-bold">Trang Quản Trị</h1>
    </div>

    <!-- Thống kê nhanh -->
    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
        <div class="bg-blue-500 p-4 rounded-lg text-white">
            <h2 class="text-xl font-bold">Tổng số đơn hàng</h2>
            <p class="text-3xl">{{ $totalOrders }}</p>
        </div>

        <div class="bg-green-500 p-4 rounded-lg text-white">
            <h2 class="text-xl font-bold">Tổng số sản phẩm</h2>
            <p class="text-3xl">{{ $totalProducts }}</p>
        </div>

        <div class="bg-yellow-500 p-4 rounded-lg text-white">
            <h2 class="text-xl font-bold">Tổng số người dùng</h2>
            <p class="text-3xl">{{ $totalUsers }}</p>
        </div>

        <div class="bg-purple-500 p-4 rounded-lg text-white">
            <h2 class="text-xl font-bold">Tổng số danh mục</h2>
            <p class="text-3xl">{{ $totalCategories }}</p>
        </div>

        <div class="bg-red-500 p-4 rounded-lg text-white">
            <h2 class="text-xl font-bold">Tổng số thương hiệu</h2>
            <p class="text-3xl">{{ $totalBrands }}</p>
        </div>
    </div>

    <!-- Biểu đồ -->
    <div class="mt-8 bg-white p-6 rounded-lg shadow">
        <h2 class="text-lg font-bold mb-4">Thống kê trực quan</h2>
        <canvas id="dashboardChart" height="120"></canvas>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('dashboardChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Đơn hàng', 'Sản phẩm', 'Người dùng', 'Danh mục', 'Thương hiệu'],
                    datasets: [{
                        label: 'Số lượng',
                        data: [
                            {{ $totalOrders ?? 0 }},
                            {{ $totalProducts ?? 0 }},
                            {{ $totalUsers ?? 0 }},
                            {{ $totalCategories ?? 0 }},
                            {{ $totalBrands ?? 0 }}
                        ],
                        backgroundColor: [
                            '#3b82f6',
                            '#22c55e',
                            '#eab308',
                            '#a855f7',
                            '#ef4444'
                        ],
                        borderRadius: 10
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        </script>
    @endpush
</x-layout-admin>
