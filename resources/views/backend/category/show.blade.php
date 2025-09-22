<x-layout-admin>
    <div class="content-wrapper">
        <div class="border border-blue-100 mb-3 rounded-lg p-2">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-blue-600">CHI TIẾT DANH MỤC</h2>
                <div class="text-right">
                    <a href="{{ route('category.index') }}" class="bg-sky-500 px-2 py-2 rounded-xl mx-1 text-white">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i> Về danh sách
                    </a>
                </div>
            </div>
        </div>

        <div class="border border-blue-100 rounded-lg p-3">
            <div class="flex gap-6">
                <div class="basis-2/3">
                    <div class="mb-3">
                        <label><strong>Tên danh mục:</strong></label>
                        <div class="p-2 border border-gray-300 rounded-lg bg-gray-50">{{ $category->name }}</div>
                    </div>

                    <div class="mb-3">
                        <label><strong>Slug:</strong></label>
                        <div class="p-2 border border-gray-300 rounded-lg bg-gray-50">{{ $category->slug }}</div>
                    </div>

                    <div class="mb-3">
                        <label><strong>Mô tả:</strong></label>
                        <div class="p-2 border border-gray-300 rounded-lg bg-gray-50">
                            {{ $category->description }}
                        </div>
                    </div>
                </div>

                <div class="basis-1/3">
                    <div class="mb-3">
                        <label><strong>Trạng thái:</strong></label>
                        <div class="p-2 border border-gray-300 rounded-lg bg-gray-50">
                            @if ($category->status == 1)
                                <span class="text-green-600 font-semibold">Xuất bản</span>
                            @else
                                <span class="text-red-600 font-semibold">Không xuất bản</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label><strong>Hình ảnh:</strong></label><br>
                        @if($category->image)
                            <img src="{{ asset('assets/images/category/' . $category->image) }}" alt="{{ $category->name }}" class="w-48 h-auto mt-2 rounded-lg border border-gray-300">
                        @else
                            <p class="text-gray-500 mt-2">Không có hình ảnh</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout-admin>
