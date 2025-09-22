<x-layout-admin>
    <div class="flex justify-between items-center bg-white p-4 rounded-lg shadow">
        <h1 class="text-lg font-bold">Quản lý sản phẩm</h1>
        <div>
            <a href="{{ route('product.create') }}"
                class="bg-green-500 text-white px-4 py-2 mr-2 rounded-lg hover:bg-green-600 inline-block">
                + Thêm
            </a>

            <a href="{{ route('product.trash') }}"
                class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">Thùng rác</button></a>
        </div>
    </div>

    <div class="mt-6 bg-white p-4 rounded-lg shadow">
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2 text-left">Hình</th>
                    <th class="border p-2 text-left">Tên sản phẩm</th>
                    <th class="border p-2 text-left">Danh mục</th>
                    <th class="border p-2 text-left">Thương hiệu</th>
                    <th class="border p-2 text-left">Trạng thái</th>
                    <th class="border p-2 text-left">ID</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($list as $item)
                    <tr>
                        <td class="border border-gray-300 p-2">
                            <img src="{{ asset('assets/images/product/' . $item->thumbnail) }}" class="w-32 h-auto"
                                alt="{{ $item->thumbnail }}">
                        </td>
                        <td class="border border-gray-300 p-2">{{ $item->name }} </td>
                        <td class="border border-gray-300 p-2">{{ $item->categoryname }} </td>
                        <td class="border border-gray-300 p-2">{{ $item->brandname }} </td>

                        <td class="border border-gray-300 p-2 text-center">
                            <form action="{{ route('product.status', ['product' => $item->id]) }}" method="POST"
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



                            <a href="{{ route('product.edit', ['product' => $item->id]) }}" class="mr-3">
                                <i class="fa fa-edit text-blue-500" aria-hidden="true" title="Edit"></i>
                            </a>
                            <a href="{{ route('product.show', ['product' => $item->id]) }}" class="mr-3">
                                <i class="fa fa-eye text-gray-600" title="Xem chi tiết"></i>
                            </a>
                            <a href="{{ route('product.delete', ['product' => $item->id]) }}">
                                <i class="fa fa-trash text-red-500" aria-hidden="true" title="Delete"></i>
                            </a>
                        </td>


                        <td class="border border-gray-300 p-2">{{ $item->id }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-8">{{ $list->Links() }}</div>
    </div>
</x-layout-admin>
