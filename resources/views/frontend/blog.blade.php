<x-layout-site>
    <x-slot:title>
        Blog
    </x-slot:title>
               <main class="container mx-auto px-4 py-10 grid md:grid-cols-3 gap-8">
        <!-- Danh sách bài viết -->
        <section class="md:col-span-2 space-y-8">
            <article class="bg-white rounded-lg shadow p-6">
                <h3 class="text-2xl font-semibold text-pink-600 mb-2">Top 5 Kem Dưỡng Da Được Yêu Thích Nhất</h3>
                <p class="text-gray-700">Tổng hợp những loại kem dưỡng được đánh giá cao trong cộng đồng làm đẹp, giúp dưỡng ẩm và phục hồi làn da khô hiệu quả.</p>
                <a href="#" class="text-sm text-pink-500 hover:underline mt-2 inline-block">Đọc tiếp →</a>
            </article>

            <article class="bg-white rounded-lg shadow p-6">
                <h3 class="text-2xl font-semibold text-pink-600 mb-2">Cách Trang Điểm Tự Nhiên Mỗi Ngày</h3>
                <p class="text-gray-700">Makeup nhẹ nhàng giúp bạn tự tin mà không làm mất đi vẻ đẹp tự nhiên. Cùng học cách tạo lớp nền mịn và chọn màu son phù hợp.</p>
                <a href="#" class="text-sm text-pink-500 hover:underline mt-2 inline-block">Xem chi tiết →</a>
            </article>

            <article class="bg-white rounded-lg shadow p-6">
                <h3 class="text-2xl font-semibold text-pink-600 mb-2">Bí Quyết Chăm Sóc Da Dầu Không Bóng Nhờn</h3>
                <p class="text-gray-700">Da dầu cần quy trình chăm sóc phù hợp để giảm tiết bã nhờn và hạn chế mụn. Cùng tìm hiểu các bước đơn giản nhưng hiệu quả.</p>
                <a href="#" class="text-sm text-pink-500 hover:underline mt-2 inline-block">Xem thêm →</a>
            </article>
        </section>

        <!-- Sidebar -->
        <aside class="space-y-8">
            <!-- Danh mục -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h4 class="text-xl font-semibold text-pink-600 mb-4">Danh mục</h4>
                <ul class="space-y-2 text-gray-700 text-sm">
                    <li><a href="#" class="hover:text-pink-500">Chăm sóc da</a></li>
                    <li><a href="#" class="hover:text-pink-500">Makeup</a></li>
                    <li><a href="#" class="hover:text-pink-500">Chăm sóc tóc</a></li>
                    <li><a href="#" class="hover:text-pink-500">Review sản phẩm</a></li>
                    <li><a href="#" class="hover:text-pink-500">Tips làm đẹp</a></li>
                </ul>
            </div>

            <!-- Bài viết nổi bật -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h4 class="text-xl font-semibold text-pink-600 mb-4">Bài viết nổi bật</h4>
                <ul class="space-y-3 text-gray-700 text-sm">
                    <li><a href="#" class="hover:text-pink-500">5 bước skincare buổi tối đúng chuẩn</a></li>
                    <li><a href="#" class="hover:text-pink-500">Mẹo chọn kem chống nắng phù hợp</a></li>
                    <li><a href="#" class="hover:text-pink-500">Xu hướng makeup Hàn Quốc 2025</a></li>
                </ul>
            </div>

            <!-- Form đăng ký nhận tin -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h4 class="text-xl font-semibold text-pink-600 mb-4">Đăng ký nhận bản tin</h4>
                <form class="space-y-3">
                    <input type="email" placeholder="Nhập email của bạn" class="w-full p-2 border border-pink-200 rounded-md focus:ring-2 focus:ring-pink-300">
                    <button type="submit" class="w-full bg-pink-500 text-white py-2 rounded-md hover:bg-pink-600">Đăng ký</button>
                </form>
            </div>
        </aside>
    </main>
</x-layout-site>