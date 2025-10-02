<x-layout-site>
    <x-slot:title>Đặt lại mật khẩu</x-slot:title>
    <main class="container m-10 max-w-md mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-semibold mb-4">Đặt lại mật khẩu</h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-2 rounded mb-2">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 text-red-800 p-2 rounded mb-2">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label>Email</label>
                <input type="email" name="email" value="{{ session('reset_email') ?? old('email') }}" class="w-full border p-2 rounded" required readonly>
            </div>
            <div class="mb-4">
                <label>Mã OTP</label>
                <input type="text" name="code" class="w-full border p-2 rounded" required>
            </div>
            <div class="mb-4">
                <label>Mật khẩu mới</label>
                <input type="password" name="password" class="w-full border p-2 rounded" required>
            </div>
            <div class="mb-4">
                <label>Nhập lại mật khẩu</label>
                <input type="password" name="password_confirmation" class="w-full border p-2 rounded" required>
            </div>
            <button type="submit" class="w-full bg-pink-600 text-white py-2 rounded hover:bg-pink-700 transition">Đặt lại mật khẩu</button>
        </form>
    </main>
</x-layout-site>
