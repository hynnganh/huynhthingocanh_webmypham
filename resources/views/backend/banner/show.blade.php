<x-layout-admin>
    <div class="content-wrapper">
        <div class="border border-blue-100 mb-3 rounded-lg p-2">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-blue-600">CHI TIẾT BANNER</h2>
                <div class="text-right">
                    <a href="{{ route('banner.index') }}" class="bg-sky-500 px-2 py-2 rounded-xl text-white">
                        <i class="fa fa-arrow-left"></i> Về danh sách
                    </a>
                </div>
            </div>
        </div>

        <div class="border border-blue-100 rounded-lg p-4">
            <div class="flex gap-6">
                <div class="basis-2/3">
                    <div class="mb-4">
                        <label><strong>Tên Banner:</strong></label>
                        <div class="p-2 border border-gray-300 rounded-lg bg-gray-50">{{ $banner->name }}</div>
                    </div>

                    <div class="mb-4">
                        <label><strong>Mô tả:</strong></label>
                        <div class="p-2 border border-gray-300 rounded-lg bg-gray-50">
                            {{ $banner->description }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <label><strong>Vị trí hiển thị:</strong></label>
                        <div class="p-2 border border-gray-300 rounded-lg bg-gray-50">{{ $banner->position }}</div>
                    </div>

                    <div class="mb-4">
                        <label><strong>Trạng thái:</strong></label>
                        <div class="p-2 border border-gray-300 rounded-lg bg-gray-50">
                            @if ($banner->status == 1)
                                <span class="text-green-600 font-semibold">Xuất bản</span>
                            @else
                                <span class="text-red-600 font-semibold">Không xuất bản</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="basis-1/3">
                    <div class="mb-4">
                        <label><strong>Hình ảnh:</strong></label><br>
                        <img src="{{ asset('assets/images/banner/' . $banner->image) }}"
                             alt="{{ $banner->name }}"
                             class="w-full h-auto rounded-lg border border-gray-300 mt-2">
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout-admin>
