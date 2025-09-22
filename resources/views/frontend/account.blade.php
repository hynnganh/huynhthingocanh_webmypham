<x-layout-site>
    <x-slot:title>
        Thông tin tài khoản
    </x-slot:title>

    <main class="p-8 max-w-2xl mx-auto bg-white shadow-2xl rounded-xl border border-pink-100 mt-8 mb-8">
        <h2 class="text-3xl font-bold text-pink-600 mb-8 border-b border-pink-200 pb-4">
            Thông tin tài khoản
        </h2>

        <div class="flex items-center space-x-6 mb-8">
            <img src="{{ asset('assets/images/user/' . $user->avatar) }}" 
                 alt="Avatar" 
                 class="w-28 h-28 rounded-full object-cover border-4 border-pink-300 shadow-sm">

            <div>
                <p class="text-2xl font-semibold text-gray-800">{{ $user->name }}</p>
                <p class="text-md text-gray-500">Tên đăng nhập: {{ $user->username }}</p>
            </div>
        </div>

        <div class="space-y-4 text-lg text-gray-700 leading-relaxed">
            <div>
                <p class="font-semibold text-pink-600">Email</p>
                <p class="bg-pink-50 p-3 rounded-md border border-pink-100">{{ $user->email }}</p>
            </div>

            <div>
                <p class="font-semibold text-pink-600">Số điện thoại</p>
                <p class="bg-pink-50 p-3 rounded-md border border-pink-100">{{ $user->phone }}</p>
            </div>

            <div>
                <p class="font-semibold text-pink-600">Địa chỉ</p>
                <p class="bg-pink-50 p-3 rounded-md border border-pink-100">{{ $user->address }}</p>
            </div>
        </div>
    </main>
</x-layout-site>
