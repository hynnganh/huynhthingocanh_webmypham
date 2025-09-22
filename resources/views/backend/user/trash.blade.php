<x-layout-admin>
    <div class="flex justify-between items-center bg-white p-4 rounded-lg shadow">
        <h1 class="text-lg font-bold">Thùng rác người dùng</h1>
        <div>
            <a href="{{ route('user.index') }}" class="bg-sky-500 px-2 py-2 rounded-xl mx-1 text-white">
                <i class="fa fa-arrow-left" aria-hidden="true"></i> Về danh sách
            </a>
        </div>
    </div>

    <div class="mt-6 bg-white p-4 rounded-lg shadow">
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2 text-left">Hình</th>
                    <th class="border p-2 text-left">Tên người dùng</th>
                    <th class="border p-2 text-left">Email</th>
                    <th class="border p-2 text-left">Số điện thoại</th>
                    <th class="border p-2 text-left">Hành động</th>
                    <th class="border p-2 text-left">ID</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($list as $user)
                    <tr>
                        <td class="border p-2">
                            <img src="{{ asset('assets/images/user/' . $user->avatar) }}" class="w-32 h-auto" alt="{{ $user->avatar }}">
                        </td>
                        <td class="border p-2">{{ $user->name }}</td>
                        <td class="border p-2">{{ $user->email }}</td>
                        <td class="border p-2">{{ $user->phone }}</td>
                        <td class="border border-gray-300 p-2">
                            <a href="{{ route('user.restore', ['user' => $user->id]) }}">
                                <i class="fa-solid fa-rotate-left text-blue-500 text-2xl pl-3"></i>
                            </a>
                            <form action="{{ route('user.destroy', ['user' => $user->id]) }}" class="inline pl-3" method="post">
                                @csrf
                                @method('DELETE')
                                <button>
                                    <i class="fa fa-trash text-red-500 text-2xl" aria-hidden="true"></i>
                                </button>
                            </form>
                        </td>
                        <td class="border p-2">{{ $user->id }}</td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layout-admin>
