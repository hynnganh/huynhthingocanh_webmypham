<x-layout-admin>

    <div class="flex justify-between items-center bg-white p-3 rounded-xl shadow-lg border-l-4 border-teal-600 mb-6">
        <h1 class="text-xl font-bold text-gray-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-teal-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            Quản lý Người dùng
        </h1>
        <div class="space-x-3">
            {{-- Nút Thêm --}}
            <a href="{{ route('user.create') }}"
                class="inline-flex items-center bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-150 shadow-md font-semibold">
                <i class="fa fa-plus mr-1"></i> Thêm
            </a>

            {{-- Nút Thùng rác --}}
            <a href="{{ route('user.trash') }}"
                class="inline-flex items-center bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-150 shadow-md font-semibold">
                <i class="fa fa-trash-alt mr-1"></i> Thùng rác
            </a>
        </div>
    </div>

    <div class="mt-6 bg-white p-6 rounded-xl shadow-lg overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-16">Avatar</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-1/4">Thông tin cơ bản</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-20">Vai trò</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-1/4">Địa chỉ & SĐT</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider w-1/6">Trạng thái</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider w-1/6">Chức năng</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider w-12">ID</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($list as $item)
                <tr class="hover:bg-gray-50 transition duration-150">
                    
                    {{-- Avatar --}}
                    <td class="px-4 py-2 whitespace-nowrap">
                        <img src="{{ asset('assets/images/user/'.$item->avatar) }}" 
                             class="w-10 h-10 object-cover rounded-full ring-2 ring-gray-200" 
                             alt="{{ $item->name }}">
                    </td>
                    
                    {{-- Thông tin cơ bản (Name, Username, Email) --}}
                    <td class="px-4 py-2 text-sm text-gray-700">
                        <div class="font-semibold text-gray-900">{{$item->name}}</div> 
                        <div class="text-xs text-indigo-600 font-medium">@ {{$item->username}}</div>
                        <div class="text-xs text-gray-500">{{$item->email}}</div>
                    </td> 
                    
                    {{-- Vai trò --}}
                    <td class="px-4 py-2 whitespace-nowrap text-center">
                        @if($item->roles == 1)
                            <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold">Admin</span>
                        @else
                            <span class="inline-block bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-semibold">Khách hàng</span>
                        @endif
                    </td> 
                    
                    {{-- Địa chỉ & Phone --}}
                    <td class="px-4 py-2 text-sm text-gray-700 max-w-xs overflow-hidden text-ellipsis whitespace-nowrap" title="{{ $item->address }}">
                        <div class="font-medium text-gray-800">{{$item->phone}}</div>
                        <div class="text-xs text-gray-500">{{ Str::limit($item->address, 30) }}</div> 
                    </td> 
                    
                    {{-- Trạng thái (Toggle) --}}
                    <td class="px-4 py-2 text-center whitespace-nowrap">
                        {{-- Xóa style inline và dùng Tailwind --}}
                        <form action="{{ route('user.status', ['user' => $item->id]) }}" method="POST"
                            class="inline-block">
                            @csrf
                            @method('get') {{-- Giữ nguyên theo code gốc, nhưng nên dùng POST cho route này --}}
                            <button type="submit" 
                                    class="p-1 rounded-full transition duration-150 hover:bg-gray-200 transform hover:scale-110" 
                                    style="background: none; border: none;">
                                @if ($item->status == 1)
                                    <i class="fa fa-toggle-on fa-lg text-green-600" title="Đang hoạt động"></i>
                                @else
                                    <i class="fa fa-toggle-off fa-lg text-red-500" title="Đã khóa"></i>
                                @endif
                            </button>
                        </form>
                    </td>

                    {{-- Chức năng --}}
                    <td class="px-4 py-2 text-center whitespace-nowrap text-sm font-medium">
                        <div class="flex justify-center space-x-3">
                            {{-- Sửa --}}
                            <a href="{{ route('user.edit', ['user' => $item->id]) }}" 
                               class="text-blue-600 hover:text-blue-800 transition duration-150 hover:scale-110" title="Chỉnh sửa">
                                <i class="fa fa-edit fa-lg"></i>
                            </a>
                            {{-- Xem chi tiết --}}
                            <a href="{{ route('user.show', ['user' => $item->id]) }}" 
                               class="text-gray-600 hover:text-gray-900 transition duration-150 hover:scale-110" title="Xem chi tiết">
                                <i class="fa fa-eye fa-lg"></i>
                            </a>
                            {{-- Xóa --}}
                            <a href="{{ route('user.delete', ['user' => $item->id]) }}"
                               class="text-red-600 hover:text-red-800 transition duration-150 hover:scale-110" title="Xóa">
                                <i class="fa fa-trash fa-lg"></i>
                            </a>
                        </div>
                    </td>
                    
                    {{-- ID --}}
                    <td class="px-4 py-2 text-center text-sm text-gray-400">{{$item->id}}</td> 
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="mt-6 p-4">
            {{ $list->Links() }}
        </div>
    </div>

</x-layout-admin>