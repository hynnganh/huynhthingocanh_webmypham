<x-layout-admin>

    <div class="flex justify-between items-center bg-white p-4 rounded-lg shadow">
        <h1 class="text-lg font-bold">Quản lý bài viết</h1>
        <div>
            <a href="{{ route('post.create') }}"
                class="bg-green-500 text-white px-4 py-2 mr-2 rounded-lg hover:bg-green-600 inline-block">
                + Thêm
            </a>

            <a href="{{ route('post.trash') }}"
                class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">Thùng rác</button>
            </a>
        </div>
    </div>
    <div class="mt-6 bg-white p-4 rounded-lg shadow">
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2 text-left">Hình ảnh</th>
                    <th class="border p-2 text-left">Tiêu đề</th>
                    <th class="border p-2 text-left">Slug</th>
                    <th class="border p-2 text-left">Detail</th>
                    <th class="border p-2 text-left">Tên topic</th>
                    <th class="border p-2 text-left min-w-[150px]">Chức năng</th>
                    <th class="border p-2 text-left">ID</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($list as $item)
                <tr>
                    <td class="border border-gray-300 p-2">
                        <img src="{{ asset('assets/images/post/'.$item->thumbnail) }}"
                        class="w-32 h-auto" alt="{{ $item->thumbnail }}">
                    </td>
                    <td class="border border-gray-300 p-2">{{$item->title}}</td>
                    <td class="border border-gray-300 p-2">{{$item->slug}}</td>
                    <td class="border border-gray-300 p-2">{{$item->detail}}</td>
                    <td class="border border-gray-300 p-2">{{$item->topic_name}}</td>
                    <td class="border border-gray-300 p-2 min-w-[150px] text-center">
                        <a href="{{ route('post.status', ['post' => $item->id]) }}" class="mr-3">
                            @if($item->status == 1)
                                <i class="fa fa-toggle-on text-green-500" aria-hidden="true" title="Activated"></i>
                            @else
                                <i class="fa fa-toggle-off text-red-500" aria-hidden="true" title="Deactivated"></i>
                            @endif
                        </a>
                        <a href="{{ route('post.edit', ['post' => $item->id]) }}" class="mr-3">
                            <i class="fa fa-edit text-blue-500" aria-hidden="true" title="Edit"></i>
                        </a>
                        <a href="{{ route('post.show', ['post' => $item->id]) }}" class="mr-3">
                            <i class="fa fa-eye text-gray-600" title="Xem chi tiết"></i>
                        </a>
                        <a href="{{ route('post.delete', ['post' => $item->id]) }}">
                            <i class="fa fa-trash text-red-500" aria-hidden="true" title="Delete"></i>
                        </a>
                    </td>
                    <td class="border border-gray-300 p-2">{{$item->id}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="mt-8">{{$list->Links()}}</div>
    </div>

</x-layout-admin>
