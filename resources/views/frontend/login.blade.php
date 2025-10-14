<x-layout-site>
    <x-slot:title>
        Đăng Nhập
    </x-slot:title>

    <main class="flex items-center justify-center min-h-screen bg-gradient-to-br from-pink-100 via-white to-purple-100 p-3">
        <div class="w-full max-w-sm bg-white rounded-2xl shadow-2xl p-8 sm:p-10 border border-gray-100 transform hover:scale-105 transition-transform duration-300 ease-in-out">
            <h2 class="text-2xl font-extrabold text-center text-pink-700 mb-10 tracking-tight">
                Chào mừng trở lại!
            </h2>
            
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-6 relative">
                    <input type="email" name="email" id="email" required
                           class="peer w-full px-3 py-3 border-b-2 border-gray-300 focus:border-pink-500 outline-none bg-transparent text-gray-800 text-lg transition-all duration-300"
                           placeholder=" " {{-- Dùng placeholder rỗng để label nổi lên --}}
                    />
                    <label for="email" 
                           class="absolute left-0 -top-3.5 text-gray-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-3.5 
                                  peer-focus:-top-3.5 peer-focus:text-pink-600 peer-focus:text-sm transition-all duration-300 cursor-text">
                        Địa chỉ Email
                    </label>
                </div>
        
                <div class="mb-6 relative">
                    <input type="password" name="password" id="password" required
                           class="peer w-full px-3 py-3 border-b-2 border-gray-300 focus:border-pink-500 outline-none bg-transparent text-gray-800 text-lg transition-all duration-300"
                           placeholder=" "
                    />
                    <label for="password" 
                           class="absolute left-0 -top-3.5 text-gray-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-3.5 
                                  peer-focus:-top-3.5 peer-focus:text-pink-600 peer-focus:text-sm transition-all duration-300 cursor-text">
                        Mật khẩu
                    </label>
                </div>

                <div class="text-right mb-8">
                    <a href="{{ route('password.request') }}" class="text-pink-600 hover:text-pink-700 text-sm font-medium transition duration-200">Quên mật khẩu?</a>
                </div>
        
                <div class="mb-6">
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-pink-500 to-red-500 text-white py-2 px-4 rounded-full text-lg font-bold 
                                   hover:from-pink-600 hover:to-red-600 focus:outline-none focus:ring-4 focus:ring-pink-200 
                                   transform hover:-translate-y-1 transition duration-300 ease-in-out">
                        Đăng Nhập
                    </button>
                </div>
        
                <div class="text-center mt-8">
                    <p class="text-gray-600">
                        Chưa có tài khoản? 
                        <a href="{{ route('register') }}" class="text-pink-600 hover:text-pink-700 font-semibold transition duration-200">Đăng ký ngay!</a>
                    </p>
                </div>
            </form>
        </div>
    </main>
</x-layout-site>