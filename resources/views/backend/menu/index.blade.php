<x-layout-admin>

    <div class="flex justify-between items-center bg-white p-4 rounded-lg shadow">
        <h1 class="text-lg font-bold">Quản lý Menu</h1>
        <div>
            <a href="{{ route('menu.create') }}" class="bg-green-500 text-white px-4 py-2 mr-2 rounded-lg hover:bg-green-600 inline-block">
                + Thêm
            </a>
            <a href="{{ route('menu.trash') }}" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                Thùng rác
            </a>
        </div>
    </div>

    <div class="mt-5 bg-white p-4 rounded-lg shadow">
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2 text-left">Tên</th>
                    <th class="border p-2 text-left">Link</th>
                    <th class="border p-2 text-left">Menu Cha</th>
                    <th class="border p-2 text-left">Vị trí</th>
                    <th class="border p-2 text-left">Loại</th>
                    <th class="border p-2 text-left">Chức năng</th>
                    <th class="border p-2 text-left">ID</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($list as $item)
                    <tr>
                        <td class="border border-gray-300 p-2">{{ $item->name }}</td>
                        <td class="border border-gray-300 p-2">{{ $item->link }}</td>
    
                        <!-- Hiển thị tên menu cha nếu có -->
                        <td class="border border-gray-300 p-2">
                            @if ($item->parent_id == 0)
                                Không có
                            @else
                                {{ $item->parent->name ?? 'Không có' }}
                            @endif
                        </td>
                        <td class="border border-gray-300 p-2">{{ $item->position }}</td>

                        <td class="border border-gray-300 p-2">{{ $item->type }}</td>

                        <td class="border border-gray-300 p-2 text-center">
                            <a href="{{ route('menu.status', ['menu' => $item->id]) }}" class="mr-3">
                                @if($item->status == 1)
                                    <i class="fa fa-toggle-on text-green-500" aria-hidden="true" title="Hiện"></i>
                                @else
                                    <i class="fa fa-toggle-off text-red-500" aria-hidden="true" title="Ẩn"></i>
                                @endif
                            </a>
                            <a href="{{ route('menu.edit', ['menu' => $item->id]) }}" class="mr-3">
                                <i class="fa fa-edit text-blue-500" aria-hidden="true" title="Sửa"></i>
                            </a>
                            <a href="{{ route('menu.show', ['menu' => $item->id]) }}" class="mr-3">
                                <i class="fa fa-eye text-gray-600" title="Xem chi tiết"></i>
                            </a>
                            <a href="{{ route('menu.delete', ['menu' => $item->id]) }}">
                                <i class="fa fa-trash text-red-500" aria-hidden="true" title="Xóa"></i>
                            </a>
                        </td>
                        <td class="border border-gray-300 p-2">{{ $item->id }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    
        <div class="mt-8">{{ $list->links() }}</div>
    </div>
    

</x-layout-admin>
