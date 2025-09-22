<x-layout-admin>
    <div class="content-wrapper">
        <div class="border border-blue-100 mb-3 rounded-lg p-2">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-blue-600">CHI TIẾT THƯƠNG HIỆU</h2>
                <div class="text-right">
                    <a href="{{ route('brand.index') }}" class="bg-sky-500 px-2 py-2 rounded-xl mx-1 text-white">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i> Về danh sách
                    </a>
                </div>
            </div>
        </div>

        <div class="border border-blue-100 rounded-lg p-4">
            <div class="mb-4">
                <label><strong>Tên thương hiệu:</strong></label>
                <div class="p-2 border border-gray-300 rounded-lg bg-gray-50">{{ $brand->name }}</div>
            </div>

            <div class="mb-4">
                <label><strong>Slug:</strong></label>
                <div class="p-2 border border-gray-300 rounded-lg bg-gray-50">{{ $brand->slug }}</div>
            </div>

            <div class="mb-4">
                <label><strong>Mô tả:</strong></label>
                <div class="p-2 border border-gray-300 rounded-lg bg-gray-50">
                    {{ $brand->description }}
                </div>
            </div>

            <div class="mb-4">
                <label><strong>Trạng thái:</strong></label>
                <div class="p-2 border border-gray-300 rounded-lg bg-gray-50">
                    @if ($brand->status == 1)
                        <span class="text-green-600 font-semibold">Xuất bản</span>
                    @else
                        <span class="text-red-600 font-semibold">Không xuất bản</span>
                    @endif
                </div>
            </div>

            <div class="mb-4">
                <label><strong>Hình ảnh:</strong></label><br>
                @if($brand->image)
                    <img src="{{ asset('assets/images/brand/' . $brand->image) }}"
                         alt="{{ $brand->name }}"
                         class="w-48 h-auto mt-2 rounded-lg border border-gray-300">
                @else
                    <p class="text-gray-500 mt-2">Không có hình ảnh</p>
                @endif
            </div>
        </div>
    </div>
</x-layout-admin>
