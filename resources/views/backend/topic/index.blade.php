<x-layout-admin>
    <div class="flex justify-between items-center bg-white p-4 rounded-lg shadow">
        <h1 class="text-lg font-bold">Quản lý chủ đề</h1>
        <div>
            <a href="{{ route('topic.create') }}" class="bg-green-500 text-white px-4 py-2 mr-2 rounded-lg hover:bg-green-600 inline-block">
                + Thêm
            </a>
            <a href="{{ route('topic.trash') }}" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                Thùng rác
            </a>
        </div>
    </div>

    <div class="mt-6 bg-white p-4 rounded-lg shadow">
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2 text-left">Tên chủ đề</th>
                    <th class="border p-2 text-left">Slug</th>
                    <th class="border p-2 text-left">Mô tả</th>
                    <th class="border p-2 text-left">Chức năng</th>
                    <th class="border p-2 text-left">ID</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($list as $item)
                <tr>
                    <td class="border p-2">{{ $item->name }}</td>
                    <td class="border p-2">{{ $item->slug }}</td>
                    <td class="border p-2">{{ $item->description }}</td>
                    <td class="border border-gray-300 p-2 text-center">
                        <form action="{{ route('topic.status', ['topic' => $item->id]) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('get')
                            <button type="submit" style="background: none; border: none; padding: 0;">
                                @if ($item->status == 1)
                                    <i class="fa fa-toggle-on text-green-500 mr-3" aria-hidden="true"
                                        title="Activated"></i>
                                @else
                                    <i class="fa fa-toggle-off text-red-500 mr-3" aria-hidden="true"
                                        title="Deactivated"></i>
                                @endif
                            </button>
                        </form>



                        <a href="{{ route('topic.edit', ['topic' => $item->id]) }}" class="mr-3">
                            <i class="fa fa-edit text-blue-500" aria-hidden="true" title="Edit"></i>
                        </a>
                        <a href="{{ route('topic.show', ['topic' => $item->id]) }}" class="mr-3">
                            <i class="fa fa-eye text-gray-600" title="Xem chi tiết"></i>
                        </a>
                        <a href="{{ route('topic.delete', ['topic' => $item->id]) }}">
                            <i class="fa fa-trash text-red-500" aria-hidden="true" title="Delete"></i>
                        </a>
                    </td>
                    <td class="border p-2">{{ $item->id }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-8">{{ $list->links() }}</div>
    </div>
</x-layout-admin>
