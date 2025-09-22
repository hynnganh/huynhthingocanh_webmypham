<x-layout-admin>
    <div class="flex justify-between items-center bg-white p-4 rounded-lg shadow">
        <h1 class="text-lg font-bold">Quản lý đơn hàng</h1>
        <div>
            <a href="{{ route('order.trash') }}"
               class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">Thùng rác</a>
        </div>
    </div>

    <div class="mt-6 bg-white p-4 rounded-lg shadow">
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2 text-left">ID</th>
                    <th class="border p-2 text-left">Tên khách hàng</th>
                    <th class="border p-2 text-left">Email</th>
                    <th class="border p-2 text-left">Số điện thoại</th>
                    <th class="border p-2 text-left">Địa chỉ</th>
                    <th class="border p-2 text-left">Chức năng</th>
                    <th class="border p-2 text-left">Trạng thái giao hàng</th> <!-- Thêm cột trạng thái giao hàng -->

                </tr>
            </thead>
            <tbody>
                @foreach ($list as $item)
                    <tr>
                        <td class="border border-gray-300 p-2">{{ $item->id }}</td>
                        <td class="border border-gray-300 p-2">{{ $item->name }}</td>
                        <td class="border border-gray-300 p-2">{{ $item->email }}</td>
                        <td class="border border-gray-300 p-2">{{ $item->phone }}</td>
                        <td class="border border-gray-300 p-2">{{ $item->address }}</td>
                        
                        <td class="border border-gray-300 p-2 text-center">
                            <!-- Thay đổi trạng thái đơn hàng -->
                            <a href="{{ route('order.editStatus', ['order' => $item->id]) }}" class="mr-3">
                                <i class="fa fa-edit text-blue-500" title="Cập nhật trạng thái"></i>
                            </a>
                            <a href="{{ route('order.show', ['order' => $item->id]) }}" class="mr-3">
                                <i class="fa fa-eye text-gray-600" title="Xem chi tiết"></i>
                            </a>
                            <!-- Xóa đơn hàng -->
                            <a href="{{ route('order.delete', ['order' => $item->id]) }}">
                                <i class="fa fa-trash text-red-500" aria-hidden="true" title="Delete"></i>
                            </a>
                        </td>

                        <td class="border border-gray-300 p-2 text-center"> <!-- Thêm trạng thái giao hàng -->
                            @switch($item->status)
                                @case(1)
                                    <span class="text-yellow-600">Chờ xác nhận</span>
                                    @break
                                @case(2)
                                    <span class="text-blue-600">Đã xác nhận</span>
                                    @break
                                @case(3)
                                    <span class="text-orange-600">Đang chuẩn bị hàng</span>
                                    @break
                                @case(4)
                                    <span class="text-green-600">Đang giao hàng</span>
                                    @break
                                @case(5)
                                    <span class="text-teal-600">Giao thành công</span>
                                    @break
                                @case(6)
                                    <span class="text-red-600">Đã hủy</span>
                                    @break
                                @case(7)
                                    <span class="text-purple-600">Hoàn trả</span>
                                    @break
                                @case(8)
                                    <span class="text-indigo-600">Đổi hàng</span>
                                    @break
                                @case(9)
                                    <span class="text-gray-600">Từ chối</span>
                                    @break
                                @case(10)
                                    <span class="text-pink-600">Khác</span>
                                    @break
                                @default
                                    <span class="text-gray-500">Chưa xác định</span>
                            @endswitch
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Hiển thị phân trang -->
        <div class="mt-8">{{ $list->links() }}</div>
    </div>

</x-layout-admin>
