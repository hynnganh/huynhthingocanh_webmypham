<!-- resources/views/backend/login.blade.php -->

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập quản trị viên</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="font-Poppins bg-gradient-to-r from-blue-400 to-purple-600">

    <!-- Wrapper chính -->
    <div class="flex justify-center items-center min-h-screen">

        <!-- Nội dung form đăng nhập -->
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">

            <h2 class="text-4xl font-bold text-center text-pink-600 mb-6">Đăng nhập quản trị viên</h2>

            <!-- Hiển thị lỗi nếu có -->
            @if (session('error'))
                <p class="text-red-600 mb-4 text-center">{{ session('error') }}</p>
            @endif

            <!-- Form đăng nhập -->
            <form action="{{ route('admin.login') }}" method="POST"> @csrf
                <div class="space-y-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email"
                            class="w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                            required placeholder="Nhập email...">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Mật khẩu</label>
                        <input type="password" name="password" id="password"
                            class="w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                            required placeholder="Nhập mật khẩu...">
                    </div>

                    <button type="submit"
                        class="w-full bg-pink-500 text-white py-3 rounded-md hover:bg-pink-600 transition duration-300">Đăng
                        nhập</button>
                </div>
            </form>

            <!-- Link đến đăng ký nếu chưa có tài khoản -->
            <div class="mt-4 text-center">
                <p class="text-gray-600 text-sm">Chưa có tài khoản? <a href="{{ route('register') }}"
                        class="text-pink-500 hover:text-pink-600">Đăng ký ngay</a></p>
            </div>
        </div>
    </div>

</body>

</html>
