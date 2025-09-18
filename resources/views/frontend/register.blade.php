<x-layout-site>
    <x-slot:title>
        Đăng Ký
    </x-slot:title>

    <main>
        <div class="container mx-auto max-w-3xl px-4 py-8">
            <h2 class="text-4xl font-semibold text-center text-pink-600 mb-8">Đăng Ký</h2>
            <!-- Form đăng ký -->
            <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-lg space-y-6">
                @csrf

                <!-- Họ và tên -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Họ và tên:</label>
                    <input type="text" name="name" id="name"
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500"
                           value="{{ old('name') }}" required>
                    @error('name')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Số điện thoại -->
                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700">Số điện thoại:</label>
                    <input type="text" name="phone" id="phone"
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500"
                           value="{{ old('phone') }}" required>
                    @error('phone')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
                    <input type="email" name="email" id="email"
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500"
                           value="{{ old('email') }}" required>
                    @error('email')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Địa chỉ -->
                <div class="mb-4">
                    <label for="address" class="block text-sm font-medium text-gray-700">Địa chỉ:</label>
                    <input type="text" name="address" id="address"
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500"
                           value="{{ old('address') }}" required>
                    @error('address')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Tên người dùng -->
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700">Tên người dùng:</label>
                    <input type="text" name="username" id="username"
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500"
                           value="{{ old('username') }}" required>
                    @error('username')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Ảnh đại diện -->
                <div class="mb-4">
                    <label for="avatar" class="block text-sm font-medium text-gray-700">Ảnh đại diện:</label>
                    <input type="file" name="avatar" id="avatar"
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500">
                    @error('avatar')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Mật khẩu -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Mật khẩu:</label>
                    <input type="password" name="password" id="password"
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500"
                           required>
                    @error('password')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Xác nhận mật khẩu -->
                <div class="mb-4">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Xác nhận mật khẩu:</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500"
                           required>
                </div>

                <!-- Nút đăng ký -->
                <div>
                    <button type="submit"
                            class="w-full bg-pink-600 text-white py-3 px-4 rounded-md hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 transition-all duration-200">
                        Đăng Ký
                    </button>
                </div>

                <!-- Liên kết đăng nhập -->
                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-pink-600 hover:text-pink-700 transition duration-200">
                        Đã có tài khoản? Đăng nhập ngay!
                    </a>
                </div>
            </form>
        </div>
    </main>
</x-layout-site>
