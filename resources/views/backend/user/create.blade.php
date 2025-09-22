<x-layout-admin>
    <div class="bg-white p-4 rounded-lg shadow">
        <h1 class="text-lg font-bold mb-4">Thêm Người Dùng Mới</h1>

        <form action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="text-right">
                <button type="submit" class="bg-green-500 px-2 py-2 cursor-pointer rounded-xl mx-1 text-white">
                    <i class="fa fa-save" aria-hidden="true"></i> Lưu
                </button>
                <a href="{{ route('user.index') }}" class="bg-sky-500 px-2 py-2 rounded-xl mx-1 text-white">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Về danh sách
                </a>
            </div>
            
            <div class="mb-4">
                <label for="name" class="block text-sm font-semibold">Tên Người Dùng</label>
                <input type="text" id="name" name="name" class="w-full p-2 border border-gray-300 rounded-md" value="{{ old('name') }}" required>
                @error('name') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label for="username" class="block text-sm font-semibold">Username</label>
                <input type="text" id="username" name="username" class="w-full p-2 border border-gray-300 rounded-md" value="{{ old('username') }}" required>
                @error('username') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-semibold">Email</label>
                <input type="email" id="email" name="email" class="w-full p-2 border border-gray-300 rounded-md" value="{{ old('email') }}" required>
                @error('email') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label for="phone" class="block text-sm font-semibold">Số Điện Thoại</label>
                <input type="text" id="phone" name="phone" class="w-full p-2 border border-gray-300 rounded-md" value="{{ old('phone') }}" required>
                @error('phone') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label for="address" class="block text-sm font-semibold">Địa Chỉ</label>
                <input type="text" id="address" name="address" class="w-full p-2 border border-gray-300 rounded-md" value="{{ old('address') }}">
            </div>

            <!-- Trường mật khẩu -->
            <div class="mb-4">
                <label for="password" class="block text-sm font-semibold">Mật khẩu</label>
                <input type="password" id="password" name="password" class="w-full p-2 border border-gray-300 rounded-md" required>
                @error('password') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
            </div>
            
            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-semibold">Xác nhận mật khẩu</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="w-full p-2 border border-gray-300 rounded-md" required>
            </div>
            

            <div class="mb-4">
                <label for="avatar" class="block text-sm font-semibold">Avatar</label>
                <input type="file" id="avatar" name="avatar" class="w-full p-2 border border-gray-300 rounded-md">
                @error('avatar') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label for="roles" class="block text-sm font-semibold">Vai trò</label>
                <select id="roles" name="roles" class="w-full p-2 border border-gray-300 rounded-md">
                    <option value="user" {{ old('roles') == 'user' ? 'selected' : '' }}>Người dùng</option>
                    <option value="admin" {{ old('roles') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('roles') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
            </div>
        </form>
    </div>
</x-layout-admin>
