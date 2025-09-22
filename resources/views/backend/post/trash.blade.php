<x-layout-admin>
    <div class="flex justify-between items-center bg-white p-4 rounded-lg shadow">
        <h1 class="text-lg font-bold">Thùng rác bài viết</h1>
        <div>
            <a href="{{ route('post.index') }}" class="bg-sky-500 px-4 py-2 rounded-xl text-white">
                <i class="fa fa-arrow-left"></i> Về danh sách
            </a>
        </div>
    </div>

    <div class="mt-6 bg-white p-4 rounded-lg shadow">
        <table class="w-full border-collapse border border-gray-300">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border p-2 text-left">Hình ảnh</th>
                    <th class="border p-2 text-left">Tiêu đề</th>
                    <th class="border p-2 text-left">Chủ đề</th>
                    <th class="border p-2 text-left">Trạng thái</th>
                    <th class="border p-2 text-left">Chức năng</th>
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
                    <td class="border border-gray-300 p-2">{{$item->detail}}</td>
                    <td class="border border-gray-300 p-2">{{$item->topic_name}}</td>
                    <td class="border border-gray-300 p-2">
                        <a href="{{ route('post.restore', ['post' => $item->id]) }}">
                            <i class="fa-solid fa-rotate-left text-blue-500 text-2xl pl-3"></i>
                        </a>
                        <form action="{{ route('post.destroy', ['post' => $item->id]) }}" class="inline pl-3" method="post">
                            @csrf
                            @method('DELETE')
                            <button>
                                <i class="fa fa-trash text-red-500 text-2xl" aria-hidden="true"></i>
                            </button>
                        </form>
                    </td>
                    <td class="border border-gray-300 p-2">{{$item->id}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-6">{{ $list->links() }}</div>
    </div>
</x-layout-admin>
