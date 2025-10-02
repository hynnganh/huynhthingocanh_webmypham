<x-layout-site>
    <x-slot:title>
        Đăng Nhập
    </x-slot:title>

    <main>
        <div class="container m-10 mx-auto max-w-md px-4 py-8 bg-white shadow-lg rounded-lg border border-gray-200"> 
            <!-- Tiêu đề -->
            <h2 class="text-3xl font-semibold text-center text-pink-600 mb-8">Đăng Nhập</h2>
        
            <!-- Form đăng nhập -->
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <!-- Input Email -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" class="mt-2 block w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500" required>
                </div>
        
                <!-- Input Mật khẩu -->
                <div class="mb-2">
                    <label for="password" class="block text-sm font-medium text-gray-700">Mật khẩu</label>
                    <input type="password" name="password" id="password" class="mt-2 block w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500" required>
                </div>

                <!-- Link quên mật khẩu -->
                <div class="text-right mb-6">
                    <a href="{{ route('password.request') }}" class="text-pink-600 hover:text-pink-700 text-sm">Quên mật khẩu?</a>
                </div>
        
                <!-- Button đăng nhập -->
                <div class="mb-6">
                    <button type="submit" class="w-full bg-pink-600 text-white py-3 px-4 rounded-md hover:bg-pink-700 transition duration-300">Đăng Nhập</button>
                </div>
        
                <!-- Link đăng ký -->
                <div class="text-center mt-4">
                    <a href="{{ route('register') }}" class="text-pink-600 hover:text-pink-700">Chưa có tài khoản? Đăng ký ngay!</a>
                </div>
            </form>
        </div>
    </main>
</x-layout-site>
