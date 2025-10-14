@php use Illuminate\Support\Facades\Auth; @endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Sử dụng phông chữ Inter mặc định của Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- CSS Toastr -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <!-- JS Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


    <title>Quản Lý Dashboard</title>
    <!-- Tùy chỉnh CSS cho hiệu ứng và độ rộng -->
    <style>
        /* Tùy chỉnh thanh cuộn trên webkit */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #a0aec0;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #718096;
        }

        /* Transition cho Sidebar */
        #sidebar {
            transition: transform 0.3s ease-in-out, width 0.3s ease-in-out;
            transform: translateX(-100%);
            /* Mặc định ẩn trên di động */
        }

        /* Hiển thị sidebar trên màn hình lớn */
        @media (min-width: 1024px) {
            #sidebar {
                transform: translateX(0);
            }
        }

        /* Khi sidebar mở trên di động */
        .sidebar-open #sidebar {
            transform: translateX(0);
        }

        /* Đẩy nội dung chính sang phải khi sidebar mở */
        #main-content {
            transition: margin-left 0.3s ease-in-out;
        }

        @media (min-width: 1024px) {
            #main-content {
                margin-left: 256px; /* w-64 */
            }

            .sidebar-collapsed #main-content {
                margin-left: 0;
            }
        }

        /* Layer Overlay cho mobile khi sidebar mở */
        .sidebar-overlay {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease-in-out;
        }

        .sidebar-open .sidebar-overlay {
            opacity: 0.5;
            pointer-events: auto;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen flex flex-col antialiased">
    <!-- Overlay cho mobile/tablet khi sidebar mở -->
    <div class="sidebar-overlay fixed inset-0 bg-black z-30 lg:hidden" onclick="toggleSidebar()"></div>

    <section id="container" class="flex-grow">
        <!-- Header Start -->
        <header class="header fixed top-0 left-0 w-full bg-white shadow-lg z-50">
            <div class="flex items-center justify-between px-4 py-3 md:px-6">
                <!-- Logo & Toggle Button -->
                <div class="brand flex items-center space-x-4">
                    <button id="sidebarToggle" onclick="toggleSidebar()"
                        class="text-gray-600 hover:text-indigo-600 transition lg:hidden p-2 rounded-lg bg-gray-100">
                        <i class="fa fa-bars text-xl"></i>
                    </button>
                    <a href="/admin" class="text-2xl font-bold text-indigo-700 hover:text-indigo-900 transition">Dashboard</a>
                </div>

                <div class="top-nav flex items-center space-x-4">
                    <!-- Search Input (Hidden on mobile) -->
                    <div class="hidden sm:block">
                        <input type="text"
                            class="py-2 px-4 w-64 rounded-full border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150"
                            placeholder="Tìm kiếm...">
                    </div>

                    <!-- User Login Dropdown Start -->
                    <div class="relative group">
                        <a class="flex items-center space-x-2 cursor-pointer p-2 rounded-full hover:bg-gray-100 transition duration-150">
                            <!-- Avatar placeholder -->
                            <img alt="User Avatar" src="https://placehold.co/32x32/6366f1/ffffff?text={{ Auth::user()->name[0] ?? '?' }}"
                                class="w-8 h-8 rounded-full border border-indigo-300 object-cover">
                            @auth
                                <span class="text-gray-700 font-medium hidden sm:block">{{ Auth::user()->name }}</span>
                            @endauth
                            @guest
                                <span class="text-gray-700 font-medium hidden sm:block">Khách</span>
                            @endguest
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </a>
                        <!-- Dropdown menu -->
                        <ul class="absolute right-0 hidden group-hover:block bg-white shadow-xl border border-gray-100 rounded-xl py-2 w-48 mt-2 z-50 transition duration-200 origin-top-right scale-95 group-hover:scale-100">
                            <li><a href="#"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition duration-150 rounded-lg mx-2">
                                    <i class="fas fa-globe mr-2"></i> Xem trang web</a></li>
                            <li><a href="#"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition duration-150 rounded-lg mx-2">
                                    <i class="fas fa-user-circle mr-2"></i> Hồ sơ</a></li>
                            <li><a href="#"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition duration-150 rounded-lg mx-2">
                                    <i class="fas fa-cog mr-2"></i> Cài đặt</a></li>
                            <div class="border-t my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition duration-150 rounded-lg mx-2">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Đăng xuất
                                </button>
                            </form>
                        </ul>
                    </div>
                    <!-- User Login Dropdown End -->
                </div>
            </div>
        </header>
        <!-- Header End -->

        <!-- Sidebar Start -->
        <aside id="sidebar"
            class="bg-gray-800 w-64 fixed top-0 left-0 h-full shadow-2xl z-40 lg:w-64">
            <div class="flex items-center justify-center h-[60px] border-b border-gray-700 px-4">
                <a href="/admin" class="text-2xl font-extrabold text-white">ADMIN PANEL</a>
            </div>
            
            <div class="nav-collapse overflow-y-auto" style="height: calc(100% - 60px);">
                <div class="leftside-navigation p-4 space-y-2">
                    <ul class="sidebar-menu space-y-1">
                        <!-- Dashboard -->
                        <li>
                            <a href="/admin"
                                class="text-gray-300 flex items-center space-x-3 p-3 rounded-xl bg-gray-700 text-white font-semibold hover:bg-indigo-600 transition duration-200">
                                <i class="fa fa-tachometer-alt w-5"></i>
                                <span>Tổng quan</span>
                            </a>
                        </li>

                        <!-- Quản lý Sản phẩm -->
                        <li class="sub-menu">
                            <button onclick="toggleSubMenu(this)"
                                class="w-full text-left text-gray-300 flex items-center justify-between p-3 rounded-xl hover:bg-gray-700 hover:text-white transition duration-200">
                                <span class="flex items-center space-x-3">
                                    <i class="fa fa-list-alt w-5"></i>
                                    <span>Quản lý sản phẩm</span>
                                </span>
                                <i class="fas fa-chevron-right text-xs transform transition-transform duration-200"></i>
                            </button>
                            <ul class="sub space-y-1 pl-4 mt-1 hidden">
                                <li><a href="{{ route('product.index') }}"
                                        class="text-gray-400 text-sm flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-700 hover:text-white transition duration-200">
                                        <div class="w-2 h-2 rounded-full bg-gray-500 mr-2"></div>Sản phẩm</a></li>
                                <li><a href="{{ route('category.index') }}"
                                        class="text-gray-400 text-sm flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-700 hover:text-white transition duration-200">
                                        <div class="w-2 h-2 rounded-full bg-gray-500 mr-2"></div>Danh mục</a></li>
                                <li><a href="{{ route('brand.index') }}"
                                        class="text-gray-400 text-sm flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-700 hover:text-white transition duration-200">
                                        <div class="w-2 h-2 rounded-full bg-gray-500 mr-2"></div>Thương hiệu</a></li>
                            </ul>
                        </li>

                        <!-- Quản lý Bài viết -->
                        <li class="sub-menu">
                            <button onclick="toggleSubMenu(this)"
                                class="w-full text-left text-gray-300 flex items-center justify-between p-3 rounded-xl hover:bg-gray-700 hover:text-white transition duration-200">
                                <span class="flex items-center space-x-3">
                                    <i class="fa fa-book w-5"></i>
                                    <span>Quản lý bài viết</span>
                                </span>
                                <i class="fas fa-chevron-right text-xs transform transition-transform duration-200"></i>
                            </button>
                            <ul class="sub space-y-1 pl-4 mt-1 hidden">
                                <li><a href="{{ route('post.index') }}"
                                        class="text-gray-400 text-sm flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-700 hover:text-white transition duration-200">
                                        <div class="w-2 h-2 rounded-full bg-gray-500 mr-2"></div>Bài viết</a></li>
                                <li><a href="{{ route('topic.index') }}"
                                        class="text-gray-400 text-sm flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-700 hover:text-white transition duration-200">
                                        <div class="w-2 h-2 rounded-full bg-gray-500 mr-2"></div>Chủ đề</a></li>
                            </ul>
                        </li>

                        <!-- Quản lý Giao diện -->
                        <li class="sub-menu">
                            <button onclick="toggleSubMenu(this)"
                                class="w-full text-left text-gray-300 flex items-center justify-between p-3 rounded-xl hover:bg-gray-700 hover:text-white transition duration-200">
                                <span class="flex items-center space-x-3">
                                    <i class="fas fa-cogs w-5"></i>
                                    <span>Giao diện</span>
                                </span>
                                <i class="fas fa-chevron-right text-xs transform transition-transform duration-200"></i>
                            </button>
                            <ul class="sub space-y-1 pl-4 mt-1 hidden">
                                <li><a href="{{ route('menu.index') }}"
                                        class="text-gray-400 text-sm flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-700 hover:text-white transition duration-200">
                                        <div class="w-2 h-2 rounded-full bg-gray-500 mr-2"></div>Menu</a></li>
                                <li><a href="{{ route('banner.index') }}"
                                        class="text-gray-400 text-sm flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-700 hover:text-white transition duration-200">
                                        <div class="w-2 h-2 rounded-full bg-gray-500 mr-2"></div>Banner</a></li>
                            </ul>
                        </li>
                        
                        <!-- Liên hệ -->
                        <li>
                            <a href="{{ route('contact.index') }}"
                                class="text-gray-300 flex items-center space-x-3 p-3 rounded-xl hover:bg-gray-700 hover:text-white transition duration-200">
                                <i class="fa fa-phone w-5"></i>
                                <span>Liên hệ</span>
                            </a>
                        </li>
                        
                        <!-- Quản lý đơn hàng -->
                        <li>
                            <a href="{{ route('order.index') }}"
                                class="text-gray-300 flex items-center space-x-3 p-3 rounded-xl hover:bg-gray-700 hover:text-white transition duration-200">
                                <i class="fas fa-truck w-5"></i>
                                <span>Quản lý đơn hàng</span>
                            </a>
                        </li>
                        
                        <!-- Tồn kho -->
                        <li>
                            <a href="{{ route('inventory.index') }}"
                                class="text-gray-300 flex items-center space-x-3 p-3 rounded-xl hover:bg-gray-700 hover:text-white transition duration-200">
                                <i class="fa fa-box w-5"></i>
                                <span>Tồn kho</span>
                            </a>
                        </li>
                        
                        <!-- Thành viên -->
                        <li>
                            <a href="{{ route('user.index') }}"
                                class="text-gray-300 flex items-center space-x-3 p-3 rounded-xl hover:bg-gray-700 hover:text-white transition duration-200">
                                <i class="fa fa-user w-5"></i>
                                <span>Thành viên</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </aside>
        <!-- Sidebar End -->

        <!-- Main Content Start -->
        <section id="main-content" class="min-h-screen pt-16 lg:ml-64 bg-gray-50">
            <div class="p-4 md:p-6">
                {{ $slot }}
            </div>
        </section>
        <!-- Main Content End -->
    </section>

    <!-- Footer -->
    <footer class="mt-auto bg-white py-4 text-center border-t border-gray-200 lg:ml-64">
        <p class="text-sm text-gray-500">© 2025 Visitors. All rights reserved | Design by Ngoc Anh</p>
    </footer>
    
    <script>
        // Hàm để ẩn/hiện Sidebar trên mobile
        function toggleSidebar() {
            const body = document.body;
            body.classList.toggle('sidebar-open');
        }

        // Hàm để ẩn/hiện Submenu
        function toggleSubMenu(button) {
            const submenu = button.nextElementSibling;
            const icon = button.querySelector('i:last-child');
            
            // Toggle visibility of the submenu
            submenu.classList.toggle('hidden');
            
            // Toggle the arrow icon rotation
            icon.classList.toggle('rotate-90');
        }

        $(document).ready(function() {
            // Toastr Notifications
            if (typeof toastr !== 'undefined') {
                toastr.options = {
                    "closeButton": true,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                    "timeOut": "3000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                };

                @if (session('success'))
                    toastr.success("{{ session('success') }}");
                @endif

                @if (session('error'))
                    toastr.error("{{ session('error') }}");
                @endif

                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        toastr.error("{{ $error }}");
                    @endforeach
                @endif
            }
            
            // Đảm bảo sidebar đóng khi chuyển đổi giữa các trang trên mobile
            // Tự động đóng sidebar khi click vào một link (chỉ áp dụng trên màn hình nhỏ)
            $('.sidebar-menu a').on('click', function() {
                if (window.innerWidth < 1024) {
                    document.body.classList.remove('sidebar-open');
                }
            });

            // Active Menu Highlight (Simple implementation)
            const currentPath = window.location.pathname;
            $('.sidebar-menu a').each(function() {
                if ($(this).attr('href') === currentPath) {
                    $('.sidebar-menu a').removeClass('bg-gray-700 text-white font-semibold');
                    $(this).addClass('bg-indigo-600 text-white font-semibold').removeClass('bg-gray-700');
                    
                    // Mở submenu cha nếu có
                    $(this).closest('.sub-menu').find('.sub').removeClass('hidden');
                    $(this).closest('.sub-menu').find('.fa-chevron-right').addClass('rotate-90');
                }
            });

        });
    </script>
    @stack('scripts')

</body>

</html>
