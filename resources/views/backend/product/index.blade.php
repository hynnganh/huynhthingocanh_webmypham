<x-layout-admin>

    <div class="flex justify-between items-center bg-white p-3 rounded-xl shadow-lg border-l-4 border-purple-600 mb-6">
        <h1 class="text-xl font-bold text-gray-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            Quản lý Sản phẩm
        </h1>
        <div class="space-x-3">
            {{-- Nút Thêm --}}
            <a href="{{ route('product.create') }}"
                class="inline-flex items-center bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-150 shadow-md font-semibold">
                <i class="fa fa-plus mr-1"></i> Thêm
            </a>

            {{-- Nút Thùng rác --}}
            <a href="{{ route('product.trash') }}"
                class="inline-flex items-center bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-150 shadow-md font-semibold">
                <i class="fa fa-trash-alt mr-1"></i> Thùng rác
            </a>
        </div>
    </div>

    <div class="mt-6 bg-white p-6 rounded-xl shadow-lg overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-20">Hình</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-1/3">Tên sản phẩm</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Danh mục</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Thương hiệu</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Trạng thái</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Chức năng</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider w-12">ID</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($list as $item)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        
                        {{-- Hình ảnh --}}
                        <td class="px-4 py-2 whitespace-nowrap">
                            <img src="{{ asset('assets/images/product/' . $item->thumbnail) }}" 
                                 class="w-16 h-16 object-cover rounded-md shadow-sm border"
                                 alt="{{ $item->name }}" 
                                 title="{{ $item->name }}">
                        </td>
                        
                        {{-- Tên sản phẩm --}}
                        <td class="px-4 py-2 text-sm font-medium text-gray-900">{{ $item->name }}</td>
                        
                        {{-- Danh mục --}}
                        <td class="px-4 py-2 text-sm text-gray-700">{{ $item->categoryname }}</td>
                        
                        {{-- Thương hiệu --}}
                        <td class="px-4 py-2 text-sm text-gray-700">{{ $item->brandname }}</td>

                        {{-- Trạng thái (Nút Toggle) --}}
                        <td class="px-4 py-2 text-center whitespace-nowrap">
                            {{-- Sử dụng form với phương thức GET (theo code gốc) hoặc sử dụng link kèm JS/Ajax cho đẹp nhất --}}
                            {{-- Tôi giữ nguyên form nhưng xóa style inline và dùng Tailwind --}}
                            <form action="{{ route('product.status', ['product' => $item->id]) }}" method="POST"
                                class="inline-block" onsubmit="return true;"> 
                                @csrf
                                @method('get') {{-- Giữ nguyên theo code gốc, nhưng nên dùng POST cho route này --}}
                                <button type="submit" 
                                        class="p-1 rounded-full transition duration-150 hover:bg-gray-200 transform hover:scale-110" 
                                        style="background: none; border: none;">
                                    @if ($item->status == 1)
                                        <i class="fa fa-toggle-on fa-lg text-green-600" title="Đang kinh doanh"></i>
                                    @else
                                        <i class="fa fa-toggle-off fa-lg text-red-500" title="Ngừng kinh doanh"></i>
                                    @endif
                                </button>
                            </form>
                        </td>

                        {{-- Chức năng --}}
                        <td class="px-4 py-2 text-center whitespace-nowrap text-sm font-medium">
                            <div class="flex justify-center space-x-3">
                                {{-- Sửa --}}
                                <a href="{{ route('product.edit', ['product' => $item->id]) }}" 
                                   class="text-blue-600 hover:text-blue-800 transition duration-150 hover:scale-110" title="Chỉnh sửa">
                                    <i class="fa fa-edit fa-lg"></i>
                                </a>
                                {{-- Xem chi tiết --}}
                                <a href="{{ route('product.show', ['product' => $item->id]) }}" 
                                   class="text-gray-600 hover:text-gray-900 transition duration-150 hover:scale-110" title="Xem chi tiết">
                                    <i class="fa fa-eye fa-lg"></i>
                                </a>
                                {{-- Xóa --}}
                                <a href="{{ route('product.delete', ['product' => $item->id]) }}"
                                   class="text-red-600 hover:text-red-800 transition duration-150 hover:scale-110" title="Xóa">
                                    <i class="fa fa-trash fa-lg"></i>
                                </a>
                            </div>
                        </td>
                        
                        {{-- ID --}}
                        <td class="px-4 py-2 text-center text-sm text-gray-400">{{ $item->id }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="mt-6 p-4">
            {{ $list->Links() }}
        </div>
    </div>
</x-layout-admin>