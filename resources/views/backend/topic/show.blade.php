<x-layout-admin>
    <div class="content-wrapper">
        <!-- Header -->
        <div class="border border-blue-100 mb-3 rounded-lg p-2">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-blue-600">CHI TIẾT CHỦ ĐỀ</h2>
                <div class="text-right">
                    <a href="{{ route('topic.index') }}" class="bg-sky-500 px-4 py-2 rounded-xl text-white">
                        <i class="fa fa-arrow-left mr-2" aria-hidden="true"></i> Về danh sách
                    </a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="border border-blue-100 rounded-lg p-4">
            <div class="mb-4">
                <label class="font-semibold">Tên chủ đề:</label>
                <div class="p-3 border border-gray-300 rounded-lg bg-gray-50">
                    {{ $topic->name }}
                </div>
            </div>
            <div class="mb-4">
                <label><strong>Slug:</strong></label>
                <div class="p-3 border border-gray-300 rounded-lg bg-gray-50">{{ $topic->slug }}</div>
            </div>
            <div class="mb-4">
                <label class="font-semibold">Mô tả:</label>
                <div class="p-3 border border-gray-300 rounded-lg bg-gray-50">
                    {{ $topic->description ?? 'Không có mô tả' }}
                </div>
            </div>

            <div class="mb-4">
                <label class="font-semibold">Trạng thái:</label>
                <div class="p-3 border border-gray-300 rounded-lg bg-gray-50">
                    @if ($topic->status == 1)
                        <span class="text-green-600 font-semibold">Hoạt động</span>
                    @else
                        <span class="text-red-600 font-semibold">Không hoạt động</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layout-admin>
