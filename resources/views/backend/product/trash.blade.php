<x-layout-admin>
    <div class="flex justify-between items-center bg-white p-4 rounded-lg shadow">
        <h1 class="text-lg font-bold">Quản lý sản phẩm</h1>
        <div>
            <a href="{{ route('product.index') }}" class="bg-sky-500 px-2 py-2 rounded-xl mx-1 text-white">
                <i class="fa fa-arrow-left" aria-hidden="true"></i> Về danh sách
            </a>
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
                        <td class="border border-gray-300 p-2">
                            <a href="{{ route('product.restore', ['product' => $item->id]) }}">
                                <i class="fa-solid fa-rotate-left text-blue-500 text-2xl pl-3"></i>
                            </a>
                            <form action="{{ route('product.destroy', ['product' => $item->id]) }}" class="inline pl-3" method="post">
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
        <div class="mt-8">{{ $list->Links() }}</div>
    </div>
</x-layout-admin>
