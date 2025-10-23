@php use Illuminate\Support\Facades\Auth; @endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'Shop bé Ánh' }}</title>
    <!-- Tailwind CSS và Font Inter -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    
    <!-- Toastr CSS & JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    
    <!-- noUiSlider CSS & JS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.js"></script>

    <!-- Custom Style for Active Menu and Delayed Dropdown -->
    <style>
        .active-menu-item {
            background-color: #ffffff !important; /* White background */
            color: #F7A7C1 !important; /* Pink text */
            font-weight: 700 !important;
            border-radius: 9999px; /* Full rounded corners */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.06); /* Shadow for lift */
            transition: all 0.2s;
        }
        /* Đảm bảo link trong menu luôn display: block để dễ click và apply style */
        .mainmenu a {
            display: block;
            padding: 8px 16px; /* p-2 px-4 */
            border-radius: 9999px;
            transition: background-color 0.2s, color 0.2s, box-shadow 0.2s;
        }

        /* --- Custom Styles for Delayed Dropdown --- */
        /* Transition cho hiệu ứng mượt mà hơn */
        #account-menu {
            transition: opacity 0.2s ease-in-out, transform 0.2s ease-in-out;
            opacity: 0;
            transform: translateY(-5px);
            pointer-events: none; /* Ngăn chặn tương tác khi ẩn */
        }
        #account-menu.is-visible {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto; /* Cho phép tương tác khi hiển thị */
        }
    </style>

    {{ $header ?? '' }}
</head>

<body class="font-sans antialiased bg-gray-50">
    <header class="bg-white sticky top-0 z-50 shadow-md">
    <div class="w-full px-4 md:px-8 lg:px-16 mx-auto">
        <div class="flex items-center justify-between py-3"> <!-- Giảm py-3 -> py-2 -->
            
            <!-- Logo -->
            <div class="w-auto">
                <a href="/" class="flex items-center space-x-2">
                    <img src="https://webmypham.onrender.com/assets/img/logo.jpg" 
                        alt="Shop Logo"
                        class="w-12 h-12 object-contain rounded-full shadow-md" /> <!-- Giảm w-16 h-16 -->
                    <span class="text-2xl font-extrabold text-[#F7A7C1] hidden sm:block">Bé Ánh</span> <!-- Giảm text-3xl -> text-2xl -->
                </a>
            </div>

            <!-- Ô tìm kiếm -->
            <div class="flex-grow max-w-lg mx-3 hidden md:block"> <!-- max-w-xl -> max-w-lg -->
                <form action="{{ route('product.search') }}" method="GET" class="flex">
                    <input type="search" name="query" placeholder="Tìm kiếm sản phẩm..."
                        class="px-4 py-2 border border-gray-300 rounded-l-full w-full focus:ring-2 focus:ring-[#F7A7C1] focus:border-[#F7A7C1] transition duration-200" /> <!-- Giảm py -->
                    <button type="submit"
                        class="px-4 py-2 bg-[#F7A7C1] text-white rounded-r-full hover:bg-[#F191A8] transition duration-200">
                        <i class="fa fa-search"></i>
                    </button>
                </form>
            </div>

            <!-- Menu tài khoản + giỏ hàng -->
            <div class="w-auto">
                <div class="shop-menu">
                    <ul class="flex justify-end space-x-2 sm:space-x-4"> <!-- Giảm khoảng cách -->
                        <!-- Tài khoản - Cần thêm ID và loại bỏ class 'group' -->
                        <li id="account-dropdown-li" class="relative">
                            <a href="#" id="account-button" class="flex items-center space-x-1.5 bg-pink-100 text-[#F7A7C1] rounded-full p-2 hover:bg-[#F7A7C1] hover:text-white transition duration-300 shadow-sm">
                                <i class="fa fa-user text-base"></i>
                                <span class="hidden lg:block text-sm font-semibold">
                                    @if (Auth::check())
                                        {{ Auth::user()->name }}
                                    @else
                                        Tài khoản
                                    @endif
                                </span>
                            </a>

                            <!-- Dropdown - Cần thêm ID và loại bỏ class 'group-hover:block' -->
                            <!-- Class 'hidden' được giữ lại để ẩn ban đầu, JS sẽ thêm/bỏ 'is-visible' -->
                            <ul id="account-menu" class="absolute left-0 hidden bg-white text-gray-800 shadow-xl border border-gray-100 rounded-lg py-2 min-w-[190px] mt-2 z-50">
                                @if (Auth::check())
                                    <li>
                                        <a href="{{ route('account') }}" class="block px-3 py-2 text-sm hover:bg-pink-50 hover:text-[#F7A7C1] transition rounded-lg mx-1">
                                            <i class="fa fa-cogs mr-2"></i> Tài khoản
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('wishlist.index') }}" class="block px-3 py-2 text-sm hover:bg-pink-50 hover:text-[#F7A7C1] transition rounded-lg mx-1">
                                            <i class="fa fa-heart mr-2"></i> Danh sách yêu thích
                                        </a>
                                    </li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="block px-3 py-2 text-sm hover:bg-red-50 hover:text-red-600 transition rounded-lg mx-1">
                                                <i class="fa fa-sign-out-alt mr-2"></i> Đăng xuất
                                            </button>
                                        </form>
                                    </li>
                                @else
                                    <li>
                                        <a href="{{ route('login') }}" class="block px-3 py-2 text-sm hover:bg-pink-50 hover:text-[#F7A7C1] transition rounded-lg mx-1">
                                            <i class="fa fa-sign-in-alt mr-2"></i> Đăng nhập
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('register') }}" class="block px-3 py-2 text-sm hover:bg-pink-50 hover:text-[#F7A7C1] transition rounded-lg mx-1">
                                            <i class="fa fa-user-plus mr-2"></i> Đăng ký
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>

                        <!-- Giỏ hàng -->
                        <li>
                            <a href="{{ route('cart.index') }}" 
                            id="cart-icon"
                            class="flex items-center space-x-1.5 bg-pink-100 text-[#F7A7C1] rounded-full p-2 hover:bg-[#F7A7C1] hover:text-white transition duration-300 shadow-sm relative">
                                
                                <i class="fa fa-shopping-cart text-base"></i>
                                <span class="hidden lg:block text-sm font-semibold">Giỏ hàng</span>
                                
                                <span id="cart-count"
                                    class="absolute top-0 right-0 transform translate-x-1/2 -translate-y-1/2 bg-red-500 text-white text-xs font-bold rounded-full h-4 w-4 flex items-center justify-center p-[2px]">
                                    {{ count(session()->get('cart', [])) }} 
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Thanh menu -->
<nav class="bg-[#F7A7C1] shadow-inner sticky top-[65px] z-40"> <!-- top giảm để sát hơn -->
    <div class="container mx-auto px-4">
        <div class="mainmenu w-full text-center font-bold">
            <ul class="flex flex-wrap justify-center space-x-1 py-1.5"> <!-- Giảm py -->
                <x-main-menu />
            </ul>
        </div>
    </div>
</nav>


    <main>
        {{ $slot }}
    </main>


    <footer class="bg-gray-700 text-white pt-10 pb-4 mt-12">
        <div class="w-full px-4 md:px-8 lg:px-16 mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-8 border-b border-gray-700 pb-8">

                <!-- Column 1: Danh mục -->
                <div class="footerInfo">
                    <h4 class="text-xl font-semibold mb-5 border-b border-[#F7A7C1] pb-2 text-[#F7A7C1]">Danh mục</h4>
                    <ul class="space-y-2 text-gray-300">
                        <li><a href="#" class="hover:text-[#F7A7C1] transition"><x-menu-footer /></a></li>
                        <!-- Thêm các mục khác nếu cần -->
                    </ul>
                </div>
                
                <!-- Column 2: Liên hệ và Theo dõi -->
                <div class="footerInfo md:col-span-2">
                    <h4 class="text-xl font-semibold mb-5 border-b border-[#F7A7C1] pb-2 text-[#F7A7C1]">Theo dõi và Liên hệ</h4>
                    <ul class="space-y-4">
                        <li>
                            <a href="https://www.facebook.com/anhloveyou08" target="_blank"
                                class="flex items-center text-lg font-medium text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-full shadow-lg transition-all duration-300 transform hover:scale-[1.02]">
                                <i class="fab fa-facebook-f mr-3"></i> Facebook: Ngọc Ánh
                            </a>
                        </li>
                        <li>
                            <a href="https://www.instagram.com/hynnganh" target="_blank"
                                class="flex items-center text-lg font-medium text-white bg-pink-600 hover:bg-pink-700 px-4 py-2 rounded-full shadow-lg transition-all duration-300 transform hover:scale-[1.02]">
                                <i class="fab fa-instagram mr-3"></i> Instagram: Ngọc Ánh
                            </a>
                        </li>
                        <li>
                            <a href="https://www.tiktok.com/@htnanh23" target="_blank"
                                class="flex items-center text-lg font-medium text-white bg-black hover:bg-gray-700 px-4 py-2 rounded-full shadow-lg transition-all duration-300 transform hover:scale-[1.02]">
                                <i class="fab fa-tiktok mr-3"></i> TikTok: bánh píaa
                            </a>
                        </li>
                    </ul>
                </div>


                <!-- Column 3: Thông tin thanh toán (QR Codes) -->
                <div class="footerInfo lg:col-span-1">
                    <h4 class="text-xl font-semibold mb-5 border-b border-[#F7A7C1] pb-2 text-[#F7A7C1]">Thanh toán</h4>
                    <div class="flex justify-start space-x-6">
                        <div class="flex flex-col items-center p-3 bg-white rounded-xl shadow-inner">
                            <img src="https://webmypham.onrender.com/assets/img/momo.jpg"
                                alt="MoMo QR Code" class="w-28 h-28 object-cover rounded-lg shadow-md mb-2 border border-gray-200" />
                            <span class="text-sm font-semibold text-gray-700">MoMo</span>
                        </div>
                        <div class="flex flex-col items-center p-3 bg-white rounded-xl shadow-inner">
                            <img src="https://webmypham.onrender.com/assets/img/bidv.jpg"
                                alt="BIDV QR Code" class="w-28 h-28 object-cover rounded-lg shadow-md mb-2 border border-gray-200" />
                            <span class="text-sm font-semibold text-gray-700">BIDV</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Copyright area -->
            <div class="text-center mt-6 pt-4 border-t border-gray-700">
                <p class="text-sm text-gray-400 font-medium">Thiết kế bởi <span
                            class="font-semibold text-[#F7A7C1]">Huỳnh Thị Ngọc Ánh</span></p>
                <p class="text-xs text-gray-500 mt-2">© 2025 All rights reserved</p>
            </div>

        </div>
    </footer>

    {{ $footer ?? '' }}
    
    <script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/toastr.min.js') }}"></script>

<script>
/* ========== TOASTR CONFIG ========== */
toastr.options = {
    closeButton: true,
    progressBar: true,
    positionClass: "toast-bottom-right",
    timeOut: "3000",
    extendedTimeOut: "1000",
    showEasing: "swing",
    hideEasing: "linear",
    showMethod: "fadeIn",
    hideMethod: "fadeOut"
};

// Show Laravel session messages
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

/* ========== JQUERY READY ========== */
$(function() {
    /* === 1. Highlight Menu === */
    const currentPath = window.location.pathname;

    function clearActiveStates() {
        $('.mainmenu a').removeClass('active-menu-item')
                        .addClass('text-white hover:bg-[#F191A8]');
    }

    $('.mainmenu a').each(function() {
        const linkPath = $(this).attr('href');
        if (linkPath && currentPath.startsWith(linkPath) && linkPath !== '/') {
            clearActiveStates();
            $(this).addClass('active-menu-item').removeClass('text-white hover:bg-[#F191A8]');
            return false;
        }
        if (linkPath === '/' && currentPath === '/') {
            clearActiveStates();
            $(this).addClass('active-menu-item').removeClass('text-white hover:bg-[#F191A8]');
            return false;
        }
    });

    $('.mainmenu a').on('click', function() {
        clearActiveStates();
        $(this).addClass('active-menu-item').removeClass('text-white hover:bg-[#F191A8]');
    });


    /* === 2. Dropdown tài khoản (ẩn sau 1 giây) === */
    const accountLi = $('#account-dropdown-li');
    const accountMenu = $('#account-menu');
    let hideTimeout;
    const HIDE_DELAY = 1000;

    if (accountLi.length && accountMenu.length) {
        accountLi.on('mouseenter', function() {
            clearTimeout(hideTimeout);
            accountMenu.removeClass('hidden');
            requestAnimationFrame(() => accountMenu.addClass('is-visible'));
        });
        accountLi.on('mouseleave', function() {
            hideTimeout = setTimeout(() => {
                accountMenu.removeClass('is-visible');
                setTimeout(() => accountMenu.addClass('hidden'), 200);
            }, HIDE_DELAY);
        });
    }


    /* === 3. Hiệu ứng "Thêm vào giỏ hàng" === */
    const CART_ICON_SELECTOR = '#cart-icon';
    const CART_COUNT_SELECTOR = '#cart-count';
    const CART_ANIMATION_CLASS = 'animate-bounce';

    $('.js-add-to-cart-btn').on('click', function(e) {
        e.preventDefault();
        if ($(this).prop('disabled')) return;

        const $button = $(this);
        const $form = $button.closest('form');
        const productId = $button.data('product-id');
        const $image = $('#product-image-' + productId);
        const $cartIcon = $(CART_ICON_SELECTOR);

        if (!$image.length || !$cartIcon.length) {
            submitCartFormAjax($form, $cartIcon, CART_COUNT_SELECTOR);
            return;
        }

        const startPosition = $image.offset();
        const scrollY = $(window).scrollTop();
        const scrollX = $(window).scrollLeft();

        const $flyingImage = $image.clone()
            .css({
                position: 'fixed',
                zIndex: 9999,
                top: startPosition.top - scrollY,
                left: startPosition.left - scrollX,
                width: $image.outerWidth(),
                height: $image.outerHeight(),
                opacity: 1,
                borderRadius: '50%',
                boxShadow: '0 0 15px rgba(255,51,153,0.8)',
                pointerEvents: 'none'
            })
            .appendTo('body');

        const cartOffset = $cartIcon.offset();
        const endX = cartOffset.left - scrollX + ($cartIcon.outerWidth() / 2) - ($image.outerWidth() / 4);
        const endY = cartOffset.top - scrollY + ($cartIcon.outerHeight() / 2) - ($image.outerHeight() / 4);

        $flyingImage.animate({
            top: endY,
            left: endX,
            width: 40,
            height: 40,
            opacity: 0.5
        }, 600, 'swing', function() {
            $flyingImage.fadeOut(300, function() {
                $(this).remove();
                submitCartFormAjax($form, $cartIcon, CART_COUNT_SELECTOR);
            });
        });
    });


    /* === 4. Hàm AJAX thêm giỏ hàng === */
    function submitCartFormAjax($form, $cartIcon, cartCountSelector) {
        const $submitButton = $form.find('button[type="submit"]');
        const originalContent = $submitButton.html();
        $submitButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: $form.serialize(),
            dataType: 'json',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function(response) {
                if (response.success) {
                    $(cartCountSelector).text(response.cart_count);
                    $cartIcon.addClass(CART_ANIMATION_CLASS);
                    setTimeout(() => $cartIcon.removeClass(CART_ANIMATION_CLASS), 800);
                    toastr.success(response.message);
                } else if (response.requires_login && response.redirect_url) {
                    window.location.href = response.redirect_url;
                } else {
                    toastr.error(response.message || 'Lỗi không xác định!');
                }
            },
            error: function(xhr) {
                toastr.error('Lỗi hệ thống hoặc chưa đăng nhập!');
            },
            complete: function() {
                $submitButton.prop('disabled', false).html(originalContent);
            }
        });
    }


    /* === 5. Wishlist Toggle === */
    window.toggleWishlist = function(productId) {
        fetch("{{ route('wishlist.toggle') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            body: JSON.stringify({ product_id: productId })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                toastr.success(data.message);
            } else {
                toastr.info(data.message || 'Đã xoá khỏi danh sách yêu thích!');
            }
        })
        .catch(() => toastr.error('Lỗi kết nối máy chủ!'));
    };
});
</script>


</body>

</html>
