 <x-layout-site>
    <x-slot:title>
        Cập nhật thông tin tài khoản
    </x-slot:title>
        <div class="bg-white shadow-2xl rounded-xl border border-pink-100 p-6 mb-8">
            <h3 class="text-2xl font-bold text-pink-600 mb-4 border-b border-pink-200 pb-2">Cập nhật thông tin</h3>

            <form action="{{ route('account.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- Avatar --}}
                <div>
                    <label class="block font-semibold text-pink-600 mb-2">Ảnh đại diện</label>
                    <input type="file" name="avatar" class="block w-full border p-2 rounded-md">
                    @if($user->avatar)
                        <img src="{{ asset('assets/images/user/' . $user->avatar) }}" 
                             alt="Avatar hiện tại" class="w-20 h-20 mt-3 rounded-full border-2 border-pink-300 object-cover">
                    @endif
                </div>

                {{-- Tên --}}
                <div>
                    <label class="block font-semibold text-pink-600 mb-2">Tên hiển thị</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                           class="w-full border p-3 rounded-md focus:ring-2 focus:ring-pink-400">
                </div>

                {{-- Số điện thoại --}}
                <div>
                    <label class="block font-semibold text-pink-600 mb-2">Số điện thoại</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" 
                           class="w-full border p-3 rounded-md focus:ring-2 focus:ring-pink-400">
                </div>

                {{-- Địa chỉ --}}
                <div>
                    <label class="block font-semibold text-pink-600 mb-2">Địa chỉ</label>
                    <textarea name="address" rows="3" 
                              class="w-full border p-3 rounded-md focus:ring-2 focus:ring-pink-400">{{ old('address', $user->address) }}</textarea>
                </div>

                {{-- Nút lưu --}}
                <button type="submit" 
                        class="bg-pink-500 text-white px-6 py-2 rounded-lg hover:bg-pink-600 transition">
                    Lưu thay đổi
                </button>
            </form>
        </div>
</x-layout-site>