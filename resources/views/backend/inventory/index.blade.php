<x-layout-admin>

    <div class="flex justify-between items-center bg-white p-4 rounded-lg shadow">
        <h1 class="text-lg font-bold">Quản lý tồn kho</h1>
    </div>

    <div class="mt-6 bg-white p-4 rounded-lg shadow">
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2 text-left">Tên sản phẩm</th>
                    <th class="border p-2 text-left">Tồn kho</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td class="border border-gray-300 p-2">{{ $product->name }}</td>
                    <td class="border border-gray-300 p-2">
                        <form action="{{ route('inventory.update', $product) }}" method="POST" class="flex items-center">
                            @csrf
                            <input type="number" name="qty" value="{{ $product->qty }}" min="0" class="border p-2 rounded w-24">
                            <button type="submit" class="ml-2 bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition">Cập nhật</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</x-layout-admin>
