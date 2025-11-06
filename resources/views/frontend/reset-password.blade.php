<x-layout-site>
    <x-slot:title>Đặt lại mật khẩu</x-slot:title>

    <main class="min-h-screen flex items-center justify-center bg-gradient-to-b from-pink-50 to-white py-16 px-4">
        <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl border border-pink-100 overflow-hidden">
            <div class="p-8 sm:p-10">
                <div class="text-center mb-8">
                    <div class="w-16 h-16 mx-auto mb-4 flex items-center justify-center rounded-full bg-pink-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-pink-500" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.5 10.5V6a4.5 4.5 0 10-9 0v4.5M4.5 10.5h15M8.25 13.5h7.5m-7.5 3h7.5" />
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-800 mb-1">Đặt lại mật khẩu 🔐</h2>
                    <p class="text-gray-500 text-sm">Nhập mã OTP và tạo mật khẩu mới cho tài khoản của bạn</p>
                </div>

                {{-- ✅ Thông báo thành công --}}
                @if(session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 text-sm">
                        <strong>✔ Thành công!</strong> {{ session('success') }}
                    </div>
                @endif

                {{-- ❌ Lỗi --}}
                @if($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6 text-sm">
                        <strong>⚠ Lỗi:</strong>
                        <ul class="list-disc list-inside mt-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div id="response-message" class="mb-6 hidden"></div>

                {{-- 🔒 Form --}}
                <form action="{{ route('password.update') }}" method="POST" id="reset-password-form" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="email"
                            value="{{ session('reset_email') ?? old('email') }}"
                            class="w-full bg-gray-100 border border-gray-200 p-3 rounded-lg text-gray-600 cursor-not-allowed focus:ring-0 shadow-sm"
                            readonly required>
                    </div>

                    <div>
                        <label for="code" class="block text-sm font-semibold text-gray-700 mb-1">Mã OTP</label>
                        <div class="flex gap-2">
                            <input type="text" name="code" id="code"
                                class="flex-1 border border-gray-300 p-3 rounded-lg focus:ring-pink-400 focus:border-pink-400 shadow-sm transition"
                                placeholder="Nhập 6 chữ số OTP" required>
                            <button type="button" id="resend-otp-button"
                                class="bg-pink-500 hover:bg-pink-600 text-white text-sm font-semibold px-4 py-3 rounded-lg shadow-md transition duration-300 disabled:bg-gray-400 disabled:cursor-not-allowed min-w-[120px]">
                                Gửi lại mã
                            </button>
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Mật khẩu mới</label>
                        <input type="password" name="password" id="password"
                            class="w-full border border-gray-300 p-3 rounded-lg focus:ring-pink-400 focus:border-pink-400 shadow-sm"
                            placeholder="Tối thiểu 6 ký tự" required>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1">Xác nhận mật khẩu</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full border border-gray-300 p-3 rounded-lg focus:ring-pink-400 focus:border-pink-400 shadow-sm"
                            placeholder="Nhập lại mật khẩu" required>
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-pink-500 to-pink-400 text-white font-bold py-3 rounded-lg hover:from-pink-600 hover:to-pink-500 shadow-lg transform hover:scale-[1.02] transition">
                        ✅ Đặt lại mật khẩu
                    </button>
                </form>
            </div>

            <div class="bg-pink-50 text-center py-4 border-t border-pink-100">
                <p class="text-gray-600 text-sm">Bạn đã nhớ mật khẩu? 
                    <a href="{{ route('login') }}" class="text-pink-500 hover:underline font-semibold">Đăng nhập ngay</a>
                </p>
            </div>
        </div>
    </main>

    {{-- 🌸 Script --}}
    <script>
        const resendButton = document.getElementById('resend-otp-button');
        const responseMessageDiv = document.getElementById('response-message');
        const RESEND_TIME = 30;
        let countdown = RESEND_TIME;
        let timer = null;

        function showMessage(type, message) {
            const colors = {
                green: ['green-100', 'green-500', 'green-700'],
                red: ['red-100', 'red-500', 'red-700']
            }[type] || ['gray-100', 'gray-500', 'gray-700'];

            responseMessageDiv.innerHTML = `
                <div class="bg-${colors[0]} border-l-4 border-${colors[1]} text-${colors[2]} p-4 rounded-lg text-sm mb-4">
                    <strong>${type === 'green' ? '✔ Thành công!' : '⚠ Lỗi!'}</strong> ${message}
                </div>`;
            responseMessageDiv.classList.remove('hidden');
        }

        function startCountdown() {
            if (timer) clearInterval(timer);
            countdown = RESEND_TIME;
            resendButton.disabled = true;
            resendButton.textContent = `Gửi lại (${countdown}s)`;
            timer = setInterval(() => {
                countdown--;
                resendButton.textContent = `Gửi lại (${countdown}s)`;
                if (countdown <= 0) {
                    clearInterval(timer);
                    resendButton.disabled = false;
                    resendButton.textContent = 'Gửi lại mã';
                }
            }, 1000);
        }

        async function resendOtp() {
            const email = document.getElementById('email').value;
            startCountdown();
            responseMessageDiv.classList.add('hidden');
            try {
                const response = await fetch("{{ route('otp.resend') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({ email })
                });

                const data = await response.json();
                if (response.ok && data.success) {
                    showMessage('green', data.message || 'Mã xác thực mới đã được gửi!');
                } else {
                    clearInterval(timer);
                    resendButton.disabled = false;
                    resendButton.textContent = 'Gửi lại mã';
                    showMessage('red', data.message || 'Không thể gửi lại mã OTP.');
                }
            } catch (error) {
                clearInterval(timer);
                resendButton.disabled = false;
                resendButton.textContent = 'Gửi lại mã';
                showMessage('red', 'Lỗi mạng, vui lòng thử lại.');
            }
        }

        resendButton.addEventListener('click', resendOtp);
        document.addEventListener('DOMContentLoaded', startCountdown);
    </script>
</x-layout-site>
