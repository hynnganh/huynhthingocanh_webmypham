<x-layout-admin>

    {{-- Header --}}
    <div class="flex justify-between items-center bg-white p-6 rounded-xl shadow-lg border-b-2 border-indigo-500">
        <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">
            Trang Quản Trị Hệ Thống
        </h1>
    </div>

    {{--- THỐNG KÊ NHANH (GIỮ NGUYÊN) ---}}
    <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">

        {{-- Tổng số đơn hàng --}}
        <div class="bg-white p-6 rounded-xl shadow-xl hover:shadow-2xl transition duration-300 ease-in-out transform hover:-translate-y-2 hover:scale-105 border-l-4 border-blue-600">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Tổng số đơn hàng</p>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
            </div>
            <p class="text-4xl font-extrabold text-gray-900 mt-1">{{ $totalOrders }}</p>
        </div>

        {{-- Tổng số sản phẩm --}}
        <div class="bg-white p-6 rounded-xl shadow-xl hover:shadow-2xl transition duration-300 ease-in-out transform hover:-translate-y-2 hover:scale-105 border-l-4 border-green-600">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Tổng số sản phẩm</p>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-4-4H7a4 4 0 00-4 4v10a4 4 0 004 4h10a4 4 0 004-4v-7m-4 7h-4m4 0v-4" />
                </svg>
            </div>
            <p class="text-4xl font-extrabold text-gray-900 mt-1">{{ $totalProducts }}</p>
        </div>

        {{-- Tổng số người dùng --}}
        <div class="bg-white p-6 rounded-xl shadow-xl hover:shadow-2xl transition duration-300 ease-in-out transform hover:-translate-y-2 hover:scale-105 border-l-4 border-yellow-600">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Tổng số người dùng</p>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20v-2a3 3 0 015.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M12 10a5 5 0 110-10 5 5 0 010 10zm-5 5a5 5 0 110-10 5 5 0 010 10zm10 0a5 5 0 110-10 5 5 0 010 10z" />
                </svg>
            </div>
            <p class="text-4xl font-extrabold text-gray-900 mt-1">{{ $totalUsers }}</p>
        </div>

        {{-- Tổng số danh mục --}}
        <div class="bg-white p-6 rounded-xl shadow-xl hover:shadow-2xl transition duration-300 ease-in-out transform hover:-translate-y-2 hover:scale-105 border-l-4 border-purple-600">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Tổng số danh mục</p>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
            </div>
            <p class="text-4xl font-extrabold text-gray-900 mt-1">{{ $totalCategories }}</p>
        </div>

        {{-- Tổng số thương hiệu --}}
        <div class="bg-white p-6 rounded-xl shadow-xl hover:shadow-2xl transition duration-300 ease-in-out transform hover:-translate-y-2 hover:scale-105 border-l-4 border-red-600">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Tổng số thương hiệu</p>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a1.5 1.5 0 00.865 1.34L12 21l7.135-2.66c.677-.253.865-1.09.865-1.34L18 7l3-1m-3 0V4a2 2 0 00-2-2H9a2 2 0 00-2 2v2m7 5V4" />
                </svg>
            </div>
            <p class="text-4xl font-extrabold text-gray-900 mt-1">{{ $totalBrands }}</p>
        </div>

    </div>

    <hr class="my-8 border-t-2 border-gray-200">

    {{--- BIỂU ĐỒ (GIỮ NGUYÊN) ---}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Biểu đồ cột tổng quan --}}
        <div class="bg-white p-6 rounded-xl shadow-xl">
            <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Biểu đồ tổng quan hệ thống</h2>
            <div class="relative h-[300px]">
                <canvas id="dashboardChart"></canvas>
            </div>
        </div>

        {{-- Biểu đồ tròn trạng thái đơn hàng --}}
        <div class="bg-white p-6 rounded-xl shadow-xl">
            <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Tỷ lệ trạng thái đơn hàng</h2>
            <div class="relative h-[300px]">
                <canvas id="orderStatusChart"></canvas>
            </div>
        </div>
    </div>

    <hr class="my-8 border-t-2 border-gray-200">

    {{--- BẢNG SẢN PHẨM ĐƯỢC MUA NHIỀU NHẤT (MỚI) ---}}
    <div class="bg-white p-6 rounded-xl shadow-xl">
        <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2 flex items-center">
            <i class="fa fa-fire text-orange-500 mr-2"></i>
            Top Sản phẩm Bán chạy nhất
        </h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-12">#</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-16">Hình</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-1/2">Tên sản phẩm</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Danh mục</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Số lượng bán</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    {{-- Kiểm tra xem biến $topSellingProducts có tồn tại và có dữ liệu không --}}
                    @forelse ($topSellingProducts as $index => $product)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            {{-- Thứ tự --}}
                            <td class="px-4 py-3 text-sm font-medium text-gray-900 text-center">{{ $index + 1 }}</td>
                            
                            {{-- Hình ảnh --}}
                            <td class="px-4 py-3 whitespace-nowrap">
                                <img src="{{ asset('assets/images/product/' . $product->thumbnail) }}" 
                                     class="w-10 h-10 object-cover rounded-md border"
                                     alt="{{ $product->name }}">
                            </td>
                            
                            {{-- Tên sản phẩm --}}
                            <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ $product->name }}</td>
                            
                            {{-- Danh mục --}}
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $product->categoryname }}</td>
                            
                            {{-- Số lượng bán --}}
                            <td class="px-4 py-3 text-center text-lg font-bold text-orange-600">
                                {{ number_format($product->total_sold) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">Chưa có dữ liệu sản phẩm bán chạy trong giai đoạn này.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
        <script>
            // Biểu đồ tổng quan
            const overviewCtx = document.getElementById('dashboardChart').getContext('2d');
            new Chart(overviewCtx, {
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
                        backgroundColor: ['#3b82f6','#10b981','#f59e0b','#8b5cf6','#ef4444'],
                        borderRadius: 8,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { display: false } },
                        x: { grid: { drawOnChartArea: false } }
                    }
                }
            });

            // Biểu đồ tròn trạng thái đơn hàng
            const orderCtx = document.getElementById('orderStatusChart').getContext('2d');
            new Chart(orderCtx, {
                type: 'pie',
                data: {
                    labels: ['Đã hoàn thành', 'Đang xử lý', 'Đang giao', 'Đã hủy'],
                    datasets: [{
                        data: [
                            {{ $completedOrders ?? 0 }},
                            {{ $processingOrders ?? 0 }},
                            {{ $shippingOrders ?? 0 }},
                            {{ $cancelledOrders ?? 0 }}
                        ],
                        backgroundColor: ['#10b981','#3b82f6','#f59e0b','#ef4444'],
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.chart._metasets[0].total;
                                    const value = context.parsed;
                                    const percent = ((value / total) * 100).toFixed(1);
                                    return `${context.label}: ${value} (${percent}%)`;
                                }
                            }
                        }
                    }
                }
            });
        </script>
    @endpush
</x-layout-admin>