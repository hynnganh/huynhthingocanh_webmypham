<x-layout-admin>
    <div class="content-wrapper">
        <div class="border border-blue-100 mb-3 rounded-lg p-2">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-blue-600">CHI TIẾT SẢN PHẨM</h2>
                <div class="text-right">
                    <a href="{{ route('product.index') }}" class="bg-sky-500 px-4 py-2 rounded-xl mx-1 text-white">
                        <i class="fa fa-arrow-left mr-2"></i> Về danh sách
                    </a>
                </div>
            </div>
        </div>

        <div class="border border-blue-100 rounded-lg p-4">
            <div class="flex gap-6">
                <!-- Left section -->
                <div class="basis-2/3">
                    <div class="mb-4">
                        <label class="font-semibold">Tên sản phẩm:</label>
                        <div class="p-3 border border-gray-300 rounded bg-gray-50">{{ $product->name }}</div>
                    </div>
                    <div class="mb-4">
                        <label><strong>Slug:</strong></label>
                        <div class="p-3 border border-gray-300 rounded-lg bg-gray-50">{{ $product->slug }}</div>
                    </div>
                    <div class="mb-4">
                        <label class="font-semibold">Chi tiết:</label>
                        <div class="p-3 border border-gray-300 rounded bg-gray-50">{{ $product->detail }}</div>
                    </div>

                    <div class="mb-4">
                        <label class="font-semibold">Mô tả:</label>
                        <div class="p-3 border border-gray-300 rounded bg-gray-50">{{ $product->description }}</div>
                    </div>

                    <div class="flex gap-4">
                        <div class="mb-4 w-1/3">
                            <label class="font-semibold">Giá gốc:</label>
                            <div class="p-3 border border-gray-300 rounded bg-gray-50">
                                {{ number_format($product->price_root, 0, ',', '.') }} VNĐ
                            </div>
                        </div>

                        <div class="mb-4 w-1/3">
                            <label class="font-semibold">Giá khuyến mãi:</label>
                            <div class="p-3 border border-gray-300 rounded bg-gray-50">
                                {{ number_format($product->price_sale, 0, ',', '.') }} VNĐ
                            </div>
                        </div>

                        <div class="mb-4 w-1/3">
                            <label class="font-semibold">Số lượng:</label>
                            <div class="p-3 border border-gray-300 rounded bg-gray-50">{{ $product->qty }}</div>
                        </div>
                    </div>
                </div>

                <!-- Right section -->
                <div class="basis-1/3">
                    <div class="mb-4">
                        <label class="font-semibold">Danh mục:</label>
                        <div class="p-3 border border-gray-300 rounded bg-gray-50">
                            {{ $product->category->name ?? '-- Không có --' }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="font-semibold">Thương hiệu:</label>
                        <div class="p-3 border border-gray-300 rounded bg-gray-50">
                            {{ $product->brand->name ?? '-- Không có --' }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="font-semibold">Ảnh đại diện:</label>
                        <div class="p-3 border border-gray-300 rounded bg-gray-50">
                            @if ($product->thumbnail)
                                <img src="{{ asset('assets/images/product/' . $product->thumbnail) }}" alt="{{ $product->name }}" class="w-full rounded shadow">
                            @else
                                <p class="text-gray-500">Không có ảnh</p>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="font-semibold">Trạng thái:</label>
                        <div class="p-3 border border-gray-300 rounded bg-gray-50">
                            @if ($product->status == 1)
                                <span class="text-green-600 font-semibold">Xuất bản</span>
                            @else
                                <span class="text-red-600 font-semibold">Không xuất bản</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout-admin>
