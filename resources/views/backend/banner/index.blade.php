<x-layout-admin>

    <div class="flex justify-between items-center bg-white p-4 rounded-lg shadow">
        <h1 class="text-lg font-bold">Quản lý Banner</h1>
        <div>
            <a href="{{ route('banner.create') }}"
                class="bg-green-500 text-white px-4 py-2 mr-2 rounded-lg hover:bg-green-600 inline-block">
                + Thêm
            </a>

            <a href="{{ route('banner.trash') }}"
                class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">Thùng rác</button></a>
        </div>
    </div>

    <div class="mt-6 bg-white p-4 rounded-lg shadow">
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2 text-left">Hình</th>
                    <th class="border p-2 text-left">Tên danh mục</th>
                    <th class="border p-2 text-left">Vị trí</th>
                    <th class="border p-2 text-left">Chức năng</th>
                    <th class="border p-2 text-left">ID</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($list as $item)
                <tr>
                    <td class="border border-gray-300 p-2">
                        <img src="{{ asset('assets/images/banner/'.$item->image) }}"
                             class="w-32 h-auto" alt="{{ $item->image }}">
                    </td>
                    <td class="border border-gray-300 p-2">{{ $item->name }}</td>
                    <td class="border border-gray-300 p-2">{{ $item->position }}</td> {{-- Hiển thị vị trí --}}
                    <td class="border border-gray-300 p-2 text-center">
                        <a href="{{ route('banner.status', ['banner' => $item->id]) }}" class="mr-3">
                            @if($item->status == 1)
                                <i class="fa fa-toggle-on text-green-500" title="Activated"></i> 
                            @else
                                <i class="fa fa-toggle-off text-red-500" title="Deactivated"></i>
                            @endif
                        </a>
                    
                        <a href="{{ route('banner.edit', ['banner' => $item->id]) }}" class="mr-3">
                            <i class="fa fa-edit text-blue-500" title="Edit"></i> 
                        </a>
                    
                        <a href="{{ route('banner.show', ['banner' => $item->id]) }}" class="mr-3">
                            <i class="fa fa-eye text-gray-600" title="Xem chi tiết"></i>
                        </a>
                    
                        <a href="{{ route('banner.delete', ['banner' => $item->id]) }}">
                            <i class="fa fa-trash text-red-500" title="Delete"></i>
                        </a>
                    </td>
                    
                    <td class="border border-gray-300 p-2">{{ $item->id }}</td>
                </tr>
                @endforeach
            </tbody>
            
        </table>
        
        <div class="mt-8">{{$list->Links()}}</div>
        </table>
    </div>

</x-layout-admin>
