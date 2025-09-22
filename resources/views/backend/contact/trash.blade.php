<x-layout-admin>

    <div class="flex justify-between items-center bg-white p-4 rounded-lg shadow">
        <h1 class="text-lg font-bold">Thùng Rác</h1>
        <div>
            <a href="{{ route('contact.index') }}"
                class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Quay lại danh sách</a>
        </div>
    </div>

    <!-- Trash Table -->
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
                </tr>
            </thead>
            <tbody>
                @foreach ($contacts as $contact)
                    <tr>
                        <td class="border border-gray-300 p-2">{{ $contact->name }}</td>
                        <td class="border border-gray-300 p-2">{{ $contact->email }}</td>
                        <td class="border border-gray-300 p-2">{{ $contact->phone }}</td>
                        <td class="border border-gray-300 p-2">{{ $contact->title }}</td>
                        <td class="border border-gray-300 p-2">{{ $contact->content }}</td>
                         <td class="border border-gray-300 p-2">
                            <a href="{{ route('contact.restore', ['contact' => $contact->id]) }}">
                                <i class="fa-solid fa-rotate-left text-blue-500 text-2xl pl-3"></i>
                            </a>
                            <form action="{{ route('contact.destroy', ['contact' => $contact->id]) }}" class="inline pl-3" method="post">
                                @csrf
                                @method('DELETE')
                                <button>
                                    <i class="fa fa-trash text-red-500 text-2xl" aria-hidden="true"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</x-layout-admin>
