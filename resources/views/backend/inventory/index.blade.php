<x-layout-admin>

    <div class="flex justify-between items-center bg-white p-4 mt-2 rounded-xl shadow-lg border-l-4 border-yellow-600">
        <h1 class="text-xl font-bold text-gray-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 11v10" />
            </svg>
            Quản lý Tồn kho
        </h1>
    </div>

    <div class="mt-6 bg-white p-6 rounded-xl shadow-lg overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-3/5">Tên sản phẩm</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider w-1/5">Trạng thái tồn kho</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider w-1/5">Cập nhật số lượng</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($products as $product)
                <tr class="hover:bg-gray-50 transition duration-150">
                    
                    {{-- Cột Tên sản phẩm --}}
                    <td class="px-4 py-3 text-sm font-medium text-gray-900">
                        {{ $product->name }}
                    </td>
                    
                    {{-- Cột Trạng thái tồn kho --}}
                    <td class="px-4 py-3 whitespace-nowrap text-center">
                        @if ($product->qty > 50)
                            <span class="inline-flex px-3 py-1 text-xs font-semibold leading-tight text-green-700 bg-green-100 rounded-full">
                                Sẵn có ({{ $product->qty }})
                            </span>
                        @elseif ($product->qty > 10)
                            <span class="inline-flex px-3 py-1 text-xs font-semibold leading-tight text-yellow-700 bg-yellow-100 rounded-full">
                                Cần bổ sung ({{ $product->qty }})
                            </span>
                        @else
                            <span class="inline-flex px-3 py-1 text-xs font-semibold leading-tight text-red-700 bg-red-100 rounded-full">
                                Sắp hết ({{ $product->qty }})
                            </span>
                        @endif
                    </td>
                    
                    {{-- Cột Cập nhật số lượng --}}
                    <td class="px-4 py-3">
                        <form action="{{ route('inventory.update', $product) }}" method="POST" class="flex items-center space-x-2">
                            @csrf
                            @method('PUT') {{-- Nên dùng PUT/PATCH cho cập nhật --}}
                            
                            {{-- Input số lượng --}}
                            <input type="number" name="qty" value="{{ $product->qty }}" min="0" 
                                class="border border-gray-300 p-2 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-24 shadow-sm"
                                placeholder="SL">
                            
                            {{-- Nút Cập nhật --}}
                            <button type="submit" 
                                class="bg-blue-600 text-white px-3 py-2 rounded-lg text-sm hover:bg-blue-700 transition duration-150 shadow-md">
                                <i class="fa fa-sync-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        {{-- Giả định có phân trang --}}
        {{-- <div class="mt-6 p-4 border-t border-gray-100">
            {{ $products->links() }} 
        </div> --}}
    </div>
</x-layout-admin>