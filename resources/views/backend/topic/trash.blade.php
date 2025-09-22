<x-layout-admin>
    <div class="flex justify-between items-center bg-white p-4 rounded-lg shadow">
        <h1 class="text-lg font-bold">Thùng rác chủ đề</h1>
        <div>
            <a href="{{ route('topic.index') }}" class="bg-sky-500 px-4 py-2 rounded-lg text-white hover:bg-sky-600">
                <i class="fa fa-arrow-left"></i> Về danh sách
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
                        <td class="border border-gray-300 p-2">{{ $item->name }}</td>
                        <td class="border border-gray-300 p-2">{{ $item->slug }}</td>
                        <td class="border border-gray-300 p-2">{{ $item->description }}</td>
                        <td class="border border-gray-300 p-2">
                            <a href="{{ route('topic.restore', ['topic' => $item->id]) }}">
                                <i class="fa-solid fa-rotate-left text-blue-500 text-2xl pl-3"></i>
                            </a>
                            <form action="{{ route('topic.destroy', ['topic' => $item->id]) }}" class="inline pl-3" method="post">
                                @csrf
                                @method('DELETE')
                                <button>
                                    <i class="fa fa-trash text-red-500 text-2xl" aria-hidden="true"></i>
                                </button>
                            </form>
                        </td>
                        <td class="border border-gray-300 p-2">{{ $item->id }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-6">
            {{ $list->links() }}
        </div>
    </div>
</x-layout-admin>
