@php use Illuminate\Support\Facades\Auth; @endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- CSS Toastr -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <!-- JS Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


    <title>Document</title>
</head>

<body class="bg-gray-100 h-screen flex flex-col">
    <section id="container" class="flex-grow">
        <!-- Header Start -->
        <header class="header fixed top-0 left-0 w-full bg-white shadow-md z-50">
            <div class="flex items-center justify-between px-6 py-4">
                <!-- Logo Start -->
                <div class="brand flex items-center space-x-4">
                    <div class="fa fa-bars"></div>
                    <a href="" class="text-xl font-semibold text-gray-700">Quản Lý</a>
                </div>
                <!-- Logo End -->

                <div class="top-nav flex items-center space-x-4">
                    <!-- Search & User Info Start -->
                    <ul class="flex items-center space-x-4">
                        <li>
                            <input type="text"
                                class="form-control py-2 px-4 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-pink-500"
                                placeholder="Search">
                        </li>
                        <!-- User Login Dropdown Start -->
                        <li class="relative group">
                            <a class="flex items-center space-x-2 cursor-pointer">
                                <img alt="" src="" class="w-8 h-8 rounded-full">
                                @auth
                                    <span class="text-gray-700">{{ Auth::user()->name }}</span>
                                @endauth
                                @guest
                                    <span class="text-gray-700">Khách</span>
                                @endguest <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </a>
                            <!-- Dropdown menu -->
                            <ul class="absolute hidden group-hover:block bg-white shadow-lg rounded-lg py-2 w-48">
                                <li><a href="#"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Xem trang
                                        web</a></li>
                                <li><a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Hồ
                                        sơ</a></li>
                                <li><a href="#"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Cài đặt</a></li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Đăng xuất
                                    </button>
                                </form>
                            </ul>
                        </li>

                        <!-- User Login Dropdown End -->
                    </ul>
                    <!-- Search & User Info End -->
                </div>
            </div>
        </header>
        <!-- Header End -->

        <!-- Sidebar Start -->
        <aside class="bg-gray-800 w-64 fixed top-0 left-0 h-full shadow-lg z-40">
            <div id="sidebar" class="nav-collapse">
                <div class="leftside-navigation p-4 space-y-4">
                    <ul class="sidebar-menu space-y-2 mt-16">
                        <li>
                            <a href="/admin"
                                class="active text-white flex items-center space-x-2 p-2 rounded-md hover:bg-gray-700">
                                <i class="fa fa-dashboard"></i>
                                <span>Tổng quan</span>
                            </a>
                        </li>

                        <li class="sub-menu ">
                            <a href="#"
                                class="text-white flex items-center space-x-2 p-2 rounded-md hover:bg-gray-700">
                                <i class="fa fa-list-alt"></i>
                                <span>Quản lý sản phẩm</span>
                            </a>
                            <ul class="sub space-y-2 pl-4">
                                <li><a href="{{ route('product.index') }}"
                                        class="text-white text-sm hover:bg-gray-600 p-2 rounded-md">Sản phẩm</a></li>
                                <li><a href="{{ route('category.index') }}"
                                        class="text-white text-sm hover:bg-gray-600 p-2 rounded-md">Danh mục</a></li>
                                <li><a href="{{ route('brand.index') }}"
                                        class="text-white text-sm hover:bg-gray-600 p-2 rounded-md">Thương hiệu</a></li>
                            </ul>
                        </li>

                        <li class="sub-menu">
                            <a href="#"
                                class="text-white flex items-center space-x-2 p-2 rounded-md hover:bg-gray-700">
                                <i class="fa fa-book"></i>
                                <span>Quản lý bài viết</span>
                            </a>
                            <ul class="sub space-y-2 pl-4">
                                <li><a href="{{ route('post.index') }}"
                                        class="text-white text-sm hover:bg-gray-600 p-2 rounded-md">Bài viết</a></li>
                                <li><a href="{{ route('topic.index') }}"
                                        class="text-white text-sm hover:bg-gray-600 p-2 rounded-md">Chủ đề</a></li>
                            </ul>
                        </li>

                        <li class="sub-menu">
                            <a href="#"
                                class="text-white flex items-center space-x-2 p-2 rounded-md hover:bg-gray-700">
                                <i class="fas fa-cogs"></i>
                                <span>Giao diện</span>
                            </a>
                            <ul class="sub space-y-2 pl-4">
                                <li><a href="{{ route('menu.index') }}"
                                        class="text-white text-sm hover:bg-gray-600 p-2 rounded-md">Menu</a></li>
                                <li><a href="{{ route('banner.index') }}"
                                        class="text-white text-sm hover:bg-gray-600 p-2 rounded-md">Banner</a></li>
                            </ul>
                        </li>
                        <li class="sub-menu">
                            <a href="{{ route('contact.index') }}"
                                class="text-white flex items-center space-x-2 p-2 rounded-md hover:bg-gray-700">
                                <i class="fa fa-phone"></i>
                                <span>Liên hệ</span>
                            </a>
                        </li>
                        <li class="sub-menu">
                            <a href="{{ route('order.index') }}"
                                class="text-white flex items-center space-x-2 p-2 rounded-md hover:bg-gray-700">
                                <i class="fas fa-truck"></i>
                                <span>Quản lý đơn hàng</span>
                            </a>
                        </li>
                        <li class="sub-menu">
                            <a href="{{ route('inventory.index') }}"
                                class="text-white flex items-center space-x-2 p-2 rounded-md hover:bg-gray-700">
                                <i class="fa fa-box"></i>
                                <span>Tồn kho</span>
                            </a>
                        </li>
                        <li class="sub-menu">
                            <a href="{{ route('user.index') }}"
                                class="text-white flex items-center space-x-2 p-2 rounded-md hover:bg-gray-700">
                                <i class="fa fa-user"></i>
                                <span>Thành viên</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </aside>
        <!--sidebar end-->
    </section>

    <!--main content start-->
    <section id="main-content" class="ml-64 p-6 pt-20 flex-grow">
        {{ $slot }}
    </section>
    <!--main content end-->

    <!-- Footer -->
    <footer class="mt-auto bg-gray-800 py-4 text-center">
        <p class="text-sm text-gray-400">© 2025 Visitors. All rights reserved | Design by Ngoc Anh</p>
    </footer>
    <script>
        $(document).ready(function() {
            // Kiểm tra nếu toastr đã được tải trước khi sử dụng
            if (typeof toastr !== 'undefined') {
                @if (session('success'))
                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "timeOut": "3000"
                    };
                    toastr.success("{{ session('success') }}");
                @endif

                @if (session('error'))
                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "timeOut": "5000"
                    };
                    toastr.error("{{ session('error') }}");
                @endif

                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        toastr.options = {
                            "closeButton": true,
                            "progressBar": true,
                            "positionClass": "toast-top-right",
                            "timeOut": "5000"
                        };
                        toastr.error("{{ $error }}");
                    @endforeach
                @endif
            }
        });
    </script>
    @stack('scripts')

</body>

</html>
