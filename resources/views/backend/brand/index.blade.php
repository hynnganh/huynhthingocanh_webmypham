<x-layout-admin>

    <div class="flex justify-between items-center bg-white p-3 rounded-xl shadow-lg border-l-4 border-indigo-500">
        <h1 class="text-xl font-bold text-gray-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2h2.5M12 21v-4M9.5 7h5" />
            </svg>
            Quản lý Thương hiệu
        </h1>
        <div class="flex space-x-3">
            <a href="{{ route('brand.create') }}"
                class="inline-flex items-center bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-150 shadow-md">
                <i class="fa fa-plus mr-1"></i> Thêm mới
            </a>

            <a href="{{ route('brand.trash') }}"
                class="inline-flex items-center bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-150 shadow-md">
                <i class="fa fa-trash-alt mr-1"></i> Thùng rác
            </a>
        </div>
    </div>

    <div class="mt-6 bg-white p-6 rounded-xl shadow-lg overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider border-b">Hình ảnh</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider border-b">Tên thương hiệu</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider border-b">Slug</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider border-b">Trạng thái</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider border-b">Chức năng</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider border-b">ID</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($list as $item)
                <tr class="hover:bg-gray-50 transition duration-150">
                    
                    {{-- Cột Hình ảnh --}}
                    <td class="px-4 py-2 whitespace-nowrap">
                        <img src="{{ asset('assets/images/brand/'.$item->image) }}"
                        class="w-16 h-16 object-contain rounded border" alt="{{ $item->name }}"> {{-- Giới hạn kích thước --}}
                    </td>
                    
                    {{-- Cột Tên --}}
                    <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{$item->name}}</td>
                    
                    {{-- Cột Slug --}}
                    <td class="px-4 py-2 text-sm text-gray-500">{{$item->slug}}</td>

                    {{-- Cột Trạng thái (Tách riêng để dễ tùy biến) --}}
                    <td class="px-4 py-2 whitespace-nowrap text-center">
                        <a href="{{ route('brand.status', ['brand' => $item->id]) }}" 
                           class="inline-block transition duration-150 hover:scale-110">
                            @if($item->status == 1)
                                <span class="text-green-600 font-semibold" title="Đang hoạt động">
                                    <i class="fa fa-circle fa-sm"></i>
                                </span>
                            @else
                                <span class="text-red-600 font-semibold" title="Đã ẩn">
                                    <i class="fa fa-circle fa-sm"></i>
                                </span>
                            @endif
                        </a>
                    </td>
                    
                    {{-- Cột Chức năng --}}
                    <td class="px-4 py-2 whitespace-nowrap text-center text-sm font-medium">
                        <div class="flex justify-center space-x-3">
                            {{-- Xem chi tiết --}}
                            <a href="{{ route('brand.show', ['brand' => $item->id]) }}" 
                               class="text-gray-600 hover:text-gray-900 transition duration-150" title="Xem chi tiết">
                                <i class="fa fa-eye fa-lg"></i>
                            </a>
                            
                            {{-- Chỉnh sửa --}}
                            <a href="{{ route('brand.edit', ['brand' => $item->id]) }}" 
                               class="text-blue-600 hover:text-blue-800 transition duration-150" title="Chỉnh sửa">
                                <i class="fa fa-edit fa-lg"></i>
                            </a>
                            
                            {{-- Xóa (Chuyển vào thùng rác) --}}
                            <a href="{{ route('brand.delete', ['brand' => $item->id]) }}" 
                               class="text-red-600 hover:text-red-800 transition duration-150" title="Chuyển vào thùng rác">
                                <i class="fa fa-trash fa-lg"></i>
                            </a>
                        </div>
                    </td>
                    
                    {{-- Cột ID --}}
                    <td class="px-4 py-2 whitespace-nowrap text-center text-sm text-gray-400">{{$item->id}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-6 p-4">
            {{ $list->Links() }}
        </div>
    </div>

</x-layout-admin>