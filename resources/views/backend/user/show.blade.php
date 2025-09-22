<x-layout-admin>
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-bold text-blue-600">CHI TIẾT NGƯỜI DÙNG</h1>
            <a href="{{ route('user.index') }}" class="bg-sky-500 px-4 py-2 text-white rounded-xl hover:bg-sky-600">
                <i class="fa fa-arrow-left mr-1"></i> Về danh sách
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <div class="mb-4">
                    <label class="block font-semibold text-sm">Tên người dùng</label>
                    <div class="p-3 border border-gray-300 rounded-md bg-gray-50">{{ $user->name }}</div>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold text-sm">Username</label>
                    <div class="p-3 border border-gray-300 rounded-md bg-gray-50">{{ $user->username }}</div>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold text-sm">Email</label>
                    <div class="p-3 border border-gray-300 rounded-md bg-gray-50">{{ $user->email }}</div>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold text-sm">Số điện thoại</label>
                    <div class="p-3 border border-gray-300 rounded-md bg-gray-50">{{ $user->phone }}</div>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold text-sm">Địa chỉ</label>
                    <div class="p-3 border border-gray-300 rounded-md bg-gray-50">{{ $user->address }}</div>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold text-sm">Vai trò</label>
                    <div class="p-3 border border-gray-300 rounded-md bg-gray-50">
                        @if($user->roles == 'admin')
                            <span class="text-red-600 font-semibold">Admin</span>
                        @else
                            <span class="text-green-600 font-semibold">Người dùng</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex flex-col items-center">
                <label class="block font-semibold text-sm mb-2">Ảnh đại diện</label>
                @if($user->avatar)
                    <img src="{{ asset('assets/images/user/' . $user->avatar) }}" alt="{{ $user->name }}"
                         class="w-40 h-40 object-cover rounded-full border border-gray-300">
                @else
                    <p class="text-gray-500">Không có ảnh</p>
                @endif
            </div>
        </div>
    </div>
</x-layout-admin>
