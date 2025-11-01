<x-layout-admin>

    <div class="flex justify-between items-center bg-white p-3 rounded-xl shadow-lg border-l-4 border-indigo-500 mb-6">
        <h1 class="text-xl font-bold text-gray-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
            Quản lý Đơn hàng
        </h1>
        <div>
            <a href="{{ route('order.trash') }}"
               class="inline-flex items-center bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-150 shadow-md font-semibold">
               <i class="fa fa-trash-alt mr-1"></i> Thùng rác
            </a>
        </div>
    </div>

    <div class="mt-6 bg-white p-6 rounded-xl shadow-lg overflow-x-auto">
        
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-12">ID</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-1/5">Thông tin khách hàng</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-1/4">Địa chỉ</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider w-1/5">Thanh toán</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider w-1/6">Trạng thái</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider w-1/6">Chức năng</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($orders as $order)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        
                        {{-- ID --}}
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-bold text-gray-900">{{ $order->id }}</td>
                        
                        {{-- Thông tin khách hàng (Gộp) --}}
                        <td class="px-4 py-3 text-sm text-gray-700">
                            <div class="font-semibold text-gray-900">{{ $order->name }}</div>
                            <div class="text-xs text-gray-500">{{ $order->email }}</div>
                            <div class="text-xs text-indigo-600">{{ $order->phone }}</div>
                        </td>
                        
                        {{-- Địa chỉ (Giới hạn hiển thị) --}}
                        <td class="px-4 py-3 text-sm text-gray-500 max-w-xs overflow-hidden text-ellipsis whitespace-nowrap" title="{{ $order->address }}">
                            {{ Str::limit($order->address, 50) }}
                        </td>

                        {{-- Thanh toán --}}
                        <td class="px-4 py-3 text-center space-y-1">
                            @if($order->payment_method == 'cod')
    <span class="inline-flex items-center bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">
        <i class="fa fa-money-bill-alt mr-1"></i> COD
    </span>
@elseif($order->payment_method == 'bank')
    <span class="inline-flex items-center bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold">
        <i class="fa fa-check mr-1"></i> Đã thanh toán
    </span>
@else
    <span class="inline-flex items-center bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-semibold">
        Không rõ
    </span>
@endif

                        </td>

                       

                        {{-- Trạng thái Giao hàng (Status) --}}
                        <td class="px-4 py-3 text-center">
                            @php
                                // Định nghĩa trạng thái và màu sắc
                                $statusMap = [
                                    1 => ['text' => 'Chờ xác nhận', 'class' => 'bg-yellow-100 text-yellow-800'],
                                    2 => ['text' => 'Đang chuẩn bị', 'class' => 'bg-orange-100 text-orange-800'],
                                    3 => ['text' => 'Đang giao hàng', 'class' => 'bg-green-100 text-green-800'],
                                    4 => ['text' => 'Giao thành công', 'class' => 'bg-teal-100 text-teal-800'],
                                    5 => ['text' => 'Đã hủy', 'class' => 'bg-red-100 text-red-800'],
                                    6 => ['text' => 'Hoàn trả', 'class' => 'bg-purple-100 text-purple-800'],
                                ];
                                $status = $statusMap[$order->status] ?? ['text' => 'Không rõ', 'class' => 'bg-gray-200 text-gray-600'];
                            @endphp
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $status['class'] }}">
                                {{ $status['text'] }}
                            </span>
                        </td>


                        {{-- Chức năng --}}
                        <td class="px-4 py-3 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex justify-center space-x-3">
                                {{-- Cập nhật trạng thái --}}
                                <a href="{{ route('order.editStatus', $order->id) }}" title="Cập nhật trạng thái"
                                   class="text-blue-600 hover:text-blue-800 transition duration-150 hover:scale-110">
                                    <i class="fa fa-sync fa-lg"></i>
                                </a>
                                {{-- Xem chi tiết --}}
                                <a href="{{ route('order.show', $order->id) }}" title="Xem chi tiết"
                                   class="text-gray-600 hover:text-gray-900 transition duration-150 hover:scale-110">
                                    <i class="fa fa-eye fa-lg"></i>
                                </a>
                                {{-- Xóa --}}
                                <a href="{{ route('order.delete', $order->id) }}" title="Chuyển vào thùng rác"
                                   class="text-red-600 hover:text-red-800 transition duration-150 hover:scale-110">
                                    <i class="fa fa-trash fa-lg"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-6 p-4">
            {{ $orders->Links() }}
        </div>
    </div>
</x-layout-admin>