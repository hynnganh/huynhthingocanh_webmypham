@php use Illuminate\Support\Facades\Auth; @endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'Shop bé Ánh' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- jQuery (Toastr phụ thuộc) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    {{ $header ?? '' }}
</head>


<body>
    <header>
        <div class="w-full px-4">
            <div class="flex items-center justify-between">
                <!-- Logo Section -->
                <div class="w-1/4">
                    <div class="logo flex justify-start">
                        <a href="">
                            <img src="http://localhost/assets/img/logo.jpg" alt="Logo"
                                class="w-24 h-24 object-contain" />
                        </a>
                    </div>
                </div>

                <!-- Search Section -->
                <div class="flex justify-center items-center">
                    <form action="{{ route('product.search') }}" method="GET" class="flex space-x-2 w-full max-w-xl">
                        <input type="search" name="query" placeholder="Tìm kiếm sản phẩm"
                            class="px-4 py-2 border border-[#F7A7C1] rounded-lg w-full focus:ring-2 focus:ring-[#F7A7C1]" />
                        <button type="submit" class="px-4 py-2 bg-[#F7A7C1] text-white rounded-lg hover:bg-[#F191A8]">
                            <i class="fa fa-search"></i>
                        </button>
                    </form>
                </div>

                <!-- Shop Menu Section -->
                <div class="w-1/4 items-center">
        <div class="shop-menu">
            <ul class="flex justify-end space-x-6">
                <!-- Tài khoản -->
                <li class="relative group">
                    <a href="#" class="flex items-center space-x-2 border border-[#F7A7C1] rounded-lg p-2 hover:bg-[#F7A7C1] hover:text-white transition duration-300">
                        <i class="fa fa-user"></i>
                        <span>
                            @if (Auth::check())
                                {{ Auth::user()->name }}
                            @else
                                Tài Khoản
                            @endif
                        </span>
                    </a>

                    <!-- Dropdown Tài khoản -->
                    <ul class="absolute left-0 hidden group-hover:block bg-white text-gray-800 shadow-lg rounded-lg pt-2 min-w-[180px] z-50">
                        @if (Auth::check())
                            <!-- Nếu đã đăng nhập -->
                            <li>
                                <a href="{{ route('account') }}" class="block px-4 py-2 hover:bg-gray-100 rounded-t-lg">
                                    Tài khoản
                                </a>
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100 rounded-b-lg">
                                        Đăng xuất
                                    </button>
                                </form>
                            </li>

                            <!-- Nếu là admin -->
                            {{-- @if(Auth::user()->roles == 'admin')
                                <li>
                                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 hover:bg-gray-100 rounded-b-lg">
                                        Quản trị viên
                                    </a>
                                </li>
                            @endif --}}

                        @else
                            <!-- Nếu chưa đăng nhập -->
                            <li>
                                <a href="{{ route('login') }}" class="block px-4 py-2 hover:bg-gray-100 rounded-t-lg">
                                    Đăng nhập
                                </a>
                            </li>
                        @endif

                    </ul>
                </li>

                <!-- Giỏ hàng -->
                <li>
                    <a href="{{ route('cart.index') }}" class="flex items-center space-x-2 border border-[#F7A7C1] rounded-lg p-2 hover:bg-[#F7A7C1] hover:text-white transition duration-300">
                        <i class="fa fa-shopping-cart"></i>
                        <span>Giỏ hàng</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>


                </div>
            </div>
        </header>


        <nav class="bg-[#F7A7C1]">
        <div class="container mx-auto px-1">
            <div class="mainmenu w-full text-center font-bold">
                <ul class="grid grid-cols-6 gap-2 py-2">
            <x-main-menu />
        </ul>
    </div>
        </div>
    </nav>

        <section>
            <x-banner-list />
        </section>

        {{ $slot }}



    <footer class="bg-[#F7A7C1] text-white py-8">
        <div class="w-full px-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

                <div class="footerInfo">
                    <h4 class="text-xl font-semibold mb-4">Danh mục</h4>
                    <!-- Menu xổ dọc -->
                    <ul class="footer-dropdown-menu space-y-2">
                        <li>
                            <a href="#" class="text-white hover:text-white block"><x-menu-footer /></a>
                        </li>
                    </ul>
                </div>
                

                <div
                    class="footerInfo bg-gradient-to-r from-pink-100 via-purple-100 to-pink-50 py-8 px-4 rounded-lg shadow-md">
                    <h4 class="text-2xl font-bold text-pink-600 mb-6">Theo dõi tôi tại:</h4>
                    <ul class="space-y-4">
                        <li>
                            <a href="https://www.facebook.com/anhloveyou08" target="_blank"
                                class="flex items-center text-lg font-medium text-white bg-blue-500 hover:bg-blue-600 px-5 py-2 rounded-full shadow-md transition-all duration-300 transform hover:scale-105">
                                <i class="fab fa-facebook-f mr-3"></i> Facebook: Ngọc Ánh
                            </a>
                        </li>
                        <li>
                            <a href="https://www.instagram.com/hynnganh" target="_blank"
                                class="flex items-center text-lg font-medium text-white bg-gradient-to-r from-pink-500 to-pink-600 hover:from-pink-400 hover:to-pink-500 px-5 py-2 rounded-full shadow-md transition-all duration-300 transform hover:scale-105">
                                <i class="fab fa-instagram mr-3"></i> Instagram: Ngọc Ánh
                            </a>
                        </li>
                        <li>
                            <a href="https://www.tiktok.com/@htnanh23" target="_blank"
                                class="flex items-center text-lg font-medium text-white bg-black hover:bg-gray-800 px-5 py-2 rounded-full shadow-md transition-all duration-300 transform hover:scale-105">
                                <i class="fab fa-tiktok mr-3"></i> TikTok: nàng khờ bíc iuu
                            </a>
                        </li>
                    </ul>
                </div>




                <div class="footerInfo">
                    <h4 class="text-xl font-semibold mb-4">Thông tin thanh toán</h4>
                    <div class="flex justify-center space-x-6">
                        <div class="flex flex-col items-center">
                            <img src="http://localhost/assets/img/momo.jpg"
                                alt="MoMo QR Code" class="w-40 h-40 object-cover rounded-md shadow-lg mb-3" />
                            <span class="text-lg font-semibold text-gray-800">MoMo</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <img src="http://localhost/assets/img/bidv.jpg"
                                alt="BIDV QR Code" class="w-40 h-40 object-cover rounded-md shadow-lg mb-3" />
                            <span class="text-lg font-semibold text-gray-800">BIDV</span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="text-center mt-8 py-6 bg-white">
                <p class="text-sm text-gray-600 font-medium">Thiết kế bởi <span
                        class="font-semibold text-pink-600">Huỳnh Thị Ngọc Ánh</span></p>
                <p class="text-xs text-gray-500 mt-2">© 2025 All rights reserved</p>
            </div>

        </div>
    </footer>

    {{ $footer ?? '' }}
    <script>
        // Kiểm tra thông báo thành công (success)
        @if (session('success'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-bottom-right",
                "timeOut": "3000"
            };
            toastr.success("{{ session('success') }}");
        @endif
    
        // Kiểm tra thông báo lỗi chung (error)
        @if (session('error'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-bottom-right",
                "timeOut": "5000"
            };
            toastr.error("{{ session('error') }}");
        @endif
    
        // Kiểm tra lỗi đăng nhập (sai email hoặc mật khẩu)
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.options = {
                    "closeButton": true,
                    "progressBar": true,
                    "positionClass": "toast-bottom-right",
                    "timeOut": "5000"
                };
                toastr.error("{{ $error }}");
            @endforeach
        @endif
    </script>
    
    
</body>

</html>
