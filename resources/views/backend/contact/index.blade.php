<x-layout-admin>

    <div class="flex justify-between items-center bg-white p-4 rounded-lg shadow">
        <h1 class="text-lg font-bold">Quản lý liên hệ</h1>
        <div>
            <a href="{{ route('contact.trash') }}"
                class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">Thùng rác</button></a>
        </div>
    </div>

    <!-- Contact Table -->
    <div class="mt-6 bg-white p-4 rounded-lg shadow">
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2 text-left">Tên người gửi</th>
                    <th class="border p-2 text-left">Email</th>
                    <th class="border p-2 text-left">Phone</th>
                    <th class="border p-2 text-left">Tiêu đề</th>
                    <th class="border p-2 text-left">Nội dung</th>
                    <th class="border p-2 text-left">Chức năng</th>
                    <th class="border p-2 text-left">ID</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($list as $item)
                    <tr>
                        <td class="border border-gray-300 p-2">{{ $item->name }}</td>
                        <td class="border border-gray-300 p-2">{{ $item->email }}</td>
                        <td class="border border-gray-300 p-2">{{ $item->phone }}</td>
                        <td class="border border-gray-300 p-2">{{ $item->title }}</td>
                        <td class="border border-gray-300 p-2">{{ $item->content }}</td>
                        <td class="border border-gray-300 p-2 text-center">
                            <form action="{{ route('contact.status', ['contact' => $item->id]) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('get')
                                <button type="submit" style="background: none; border: none; padding: 0;">
                                    @if ($item->status == 1)
                                        <i class="fa fa-toggle-on text-green-500 pr-2" aria-hidden="true"
                                            title="Activated"></i>
                                    @else
                                        <i class="fa fa-toggle-off text-red-500 pr-2" aria-hidden="true"
                                            title="Deactivated"></i>
                                    @endif
                                </button>
                            </form>



                            <a href="{{ route('contact.reply', ['contact' => $item->id]) }}" class="mr-3">
                                <i class="fa fa-edit text-blue-500" aria-hidden="true" title="Edit"></i>
                            </a>
                            <a href="{{ route('contact.show', ['contact' => $item->id]) }}" class="mr-3">
                                <i class="fa fa-eye text-gray-600" title="Xem chi tiết"></i>
                            </a>
                            <a href="{{ route('contact.delete', ['contact' => $item->id]) }}">
                                <i class="fa fa-trash text-red-500" aria-hidden="true" title="Delete"></i>
                            </a>
                        </td>
                        <td class="border border-gray-300 p-2">{{ $item->id }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-8">
            {{ $list->links() }}
        </div>
    </div>

</x-layout-admin>
