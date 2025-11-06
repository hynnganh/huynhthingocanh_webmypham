<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập quản trị viên</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="font-Poppins bg-gray-100 min-h-screen flex justify-center items-center">

    <div class="p-4 sm:p-8 w-full max-w-lg">

        <div class="bg-white p-8 sm:p-10 rounded-xl shadow-2xl border-t-4 border-indigo-600 transform hover:shadow-3xl transition duration-500">

            <h2 class="text-3xl font-extrabold text-gray-900 text-center mb-2">
                Đăng nhập
            </h2>
            <p class="text-center text-gray-500 mb-8">
                Quản trị viên Hệ thống
            </p>

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6 text-sm" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <form action="{{ route('admin.login') }}" method="POST"> @csrf
                <div class="space-y-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <div class="relative">
                             <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14v4M12 6V4"></path></svg>
                            <input type="email" name="email" id="email"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 placeholder-gray-400 transition duration-300"
                                required placeholder="admin@domain.com">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu</label>
                        <div class="relative">
                             <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6-6h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v4a2 2 0 002 2zm0 0h12"></path></svg>
                            <input type="password" name="password" id="password"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 placeholder-gray-400 transition duration-300"
                                required placeholder="Nhập mật khẩu">
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-indigo-600 text-white font-semibold py-3 rounded-lg shadow-md hover:bg-indigo-700 hover:shadow-lg transition duration-300 transform hover:scale-[1.01]">
                        Đăng nhập
                    </button>
                </div>
            </form>

            <div class="mt-8 text-center pt-4 border-t border-gray-200">
                <p class="text-gray-500 text-sm">Quên mật khẩu?
                    <a href="#" class="text-indigo-600 hover:text-indigo-700 font-medium ml-1">
                        Lấy lại tại đây
                    </a>
                </p>
                <p class="text-gray-500 text-sm mt-2">Chưa có tài khoản?
                    <a href=""
                        class="text-indigo-600 hover:text-indigo-700 font-medium ml-1">
                        Đăng ký ngay
                    </a>
                </p>
            </div>
        </div>
    </div>

</body>

</html>