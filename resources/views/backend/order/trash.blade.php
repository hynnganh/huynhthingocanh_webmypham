<x-layout-admin>
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-red-600">Thùng rác - Đơn hàng</h2>
            <a href="{{ route('order.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
                <i class="fa fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <table class="w-full border border-gray-300 text-left">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-3 border">Họ tên</th>
                    <th class="p-3 border">Email</th>
                    <th class="p-3 border">Số điện thoại</th>
                    <th class="p-3 border">Trạng thái</th>
                    <th class="p-3 border text-center">Thao tác</th>
                    <th class="p-3 border">Ngày tạo</th>

                </tr>
            </thead>
            <tbody>
                @forelse($orders as $item)
                    <tr>
                        <td class="p-3 border">{{ $item->name }}</td>
                        <td class="p-3 border">{{ $item->email }}</td>
                        <td class="p-3 border">{{ $item->phone }}</td>
                        <td class="p-3 border">
                            @if ($item->status == 1)
                                <span class="text-green-600">Đang xử lý</span>
                            @else
                                <span class="text-red-600">Đã hủy</span>
                            @endif
                        </td>
                        <td class="border border-gray-300 p-2">
                            <a href="{{ route('order.restore', ['order' => $item->id]) }}">
                                <i class="fa-solid fa-rotate-left text-blue-500 text-2xl pl-3"></i>
                            </a>
                            <form action="{{ route('order.destroy', ['order' => $item->id]) }}" class="inline pl-3" method="post">
                                @csrf
                                @method('DELETE')
                                <button>
                                    <i class="fa fa-trash text-red-500 text-2xl" aria-hidden="true"></i>
                                </button>
                            </form>
                        </td>
                        <td class="p-3 border">{{ $item->created_at->format('d/m/Y H:i') }}</td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-gray-500 py-4">Không có đơn hàng nào trong thùng rác.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>
</x-layout-admin>
