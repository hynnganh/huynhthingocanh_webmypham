<x-layout-admin>

    <div class="flex justify-between items-center bg-white p-3 rounded-xl shadow-lg border-l-4 border-indigo-500 mb-6">
        <h1 class="text-xl font-bold text-gray-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            Quản lý Liên hệ
        </h1>
        <div>
            {{-- Nút Thùng rác (Giữ nguyên màu đỏ 500 nhưng thêm hiệu ứng đẹp hơn) --}}
            <a href="{{ route('contact.trash') }}"
                class="inline-flex items-center bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition duration-150 shadow-md font-semibold">
                <i class="fa fa-trash-alt mr-1"></i> Thùng rác
            </a>
        </div>
    </div>

    <div class="mt-6 bg-white p-6 rounded-xl shadow-lg overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Người gửi</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Email/Phone</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-1/4">Tiêu đề</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-1/4">Nội dung</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Trạng thái</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Chức năng</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">ID</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($list as $item)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        
                        {{-- Tên người gửi --}}
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->name }}</td>
                        
                        {{-- Email/Phone --}}
                        <td class="px-4 py-3 text-sm text-gray-500">
                            <div class="text-xs text-gray-900 font-semibold">{{ $item->email }}</div>
                            <div class="text-xs text-gray-500">{{ $item->phone }}</div>
                        </td>
                        
                        {{-- Tiêu đề --}}
                        <td class="px-4 py-3 text-sm text-gray-700 max-w-xs overflow-hidden text-ellipsis whitespace-nowrap" title="{{ $item->title }}">
                            {{ $item->title }}
                        </td>
                        
                        {{-- Nội dung (Giới hạn hiển thị) --}}
                        <td class="px-4 py-3 text-sm text-gray-500 max-w-xs overflow-hidden text-ellipsis whitespace-nowrap" title="{{ $item->content }}">
                            {{ Str::limit($item->content, 50) }}
                        </td>
                        
                        {{-- Trạng thái (Đã xem/Chưa xem) --}}
                        <td class="px-4 py-3 whitespace-nowrap text-center">
                            {{-- Click để thay đổi trạng thái --}}
                            <a href="{{ route('contact.status', ['contact' => $item->id]) }}" 
                               class="inline-block transition duration-150 hover:scale-110" 
                               title="{{ $item->status == 1 ? 'Đã xử lý' : 'Chưa xử lý' }}">
                                @if ($item->status == 1)
                                    <span class="text-green-600 text-sm font-semibold">
                                        <i class="fa fa-check-circle fa-lg"></i>
                                    </span>
                                @else
                                    <span class="text-red-500 text-sm font-semibold">
                                        <i class="fa fa-clock fa-lg"></i>
                                    </span>
                                @endif
                            </a>
                        </td>
                        
                        {{-- Chức năng --}}
                        <td class="px-4 py-3 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex justify-center space-x-3">
                                
                                {{-- Trả lời (Reply) --}}
                                <a href="{{ route('contact.reply', ['contact' => $item->id]) }}" 
                                   class="text-blue-600 hover:text-blue-800 transition duration-150" title="Trả lời/Xử lý">
                                    <i class="fa fa-reply fa-lg"></i>
                                </a>
                                
                                {{-- Xem chi tiết --}}
                                <a href="{{ route('contact.show', ['contact' => $item->id]) }}" 
                                   class="text-gray-600 hover:text-gray-900 transition duration-150" title="Xem chi tiết">
                                    <i class="fa fa-eye fa-lg"></i>
                                </a>
                                
                                {{-- Xóa --}}
                                <a href="{{ route('contact.delete', ['contact' => $item->id]) }}" 
                                   class="text-red-600 hover:text-red-800 transition duration-150" title="Xóa">
                                    <i class="fa fa-trash fa-lg"></i>
                                </a>
                            </div>
                        </td>
                        
                        {{-- ID --}}
                        <td class="px-4 py-3 whitespace-nowrap text-center text-sm text-gray-400">{{ $item->id }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-6 p-4 border-t border-gray-100">
            {{ $list->links() }}
        </div>
    </div>

</x-layout-admin>