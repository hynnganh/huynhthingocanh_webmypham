<x-layout-admin>
    <div class="content-wrapper">
        <!-- Header -->
        <div class="border border-blue-100 mb-3 rounded-lg p-2">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-blue-600">CHI TIẾT BÀI VIẾT</h2>
                <div class="text-right">
                    <a href="{{ route('post.index') }}" class="bg-sky-500 px-4 py-2 rounded-xl mx-1 text-white">
                        <i class="fa fa-arrow-left mr-2" aria-hidden="true"></i> Về danh sách
                    </a>
                </div>
            </div>
        </div>

        <!-- Post Details -->
        <div class="border border-blue-100 rounded-lg p-4">
            <div class="flex gap-6">
                <!-- Left Side - Post Details -->
                <div class="basis-2/3">
                    <!-- Title -->
                    <div class="mb-4">
                        <label class="font-semibold text-gray-700"><strong>Tiêu đề:</strong></label>
                        <div class="p-3 border-2 border-gray-300 rounded-lg bg-gray-50">{{ $post->title }}</div>
                    </div>
                    <div class="mb-4">
                        <label><strong>Slug:</strong></label>
                        <div class="p-3 border border-gray-300 rounded-lg bg-gray-50">{{ $post->slug }}</div>
                    </div>
                    <!-- Short Description -->
                    <div class="mb-4">
                        <label class="font-semibold text-gray-700"><strong>Mô tả ngắn:</strong></label>
                        <div class="p-3 border-2 border-gray-300 rounded-lg bg-gray-50">{{ $post->description }}</div>
                    </div>

                    <!-- Detailed Description -->
                    <div class="mb-4">
                        <label class="font-semibold text-gray-700"><strong>Chi tiết bài viết:</strong></label>
                        <div class="p-3 border-2 border-gray-300 rounded-lg bg-gray-50">{{ $post->detail }}</div>
                    </div>

                    <!-- Topic -->
                    <div class="mb-4">
                        <label class="font-semibold text-gray-700"><strong>Chủ đề:</strong></label>
                        <div class="p-3 border-2 border-gray-300 rounded-lg bg-gray-50">{{ $post->topic->name ?? '-- Không có --' }}</div>
                    </div>
                </div>

                <!-- Right Side - Post Status & Thumbnail -->
                <div class="basis-1/3">
                    <!-- Status -->
                    <div class="mb-4">
                        <label class="font-semibold text-gray-700"><strong>Trạng thái:</strong></label>
                        <div class="p-3 border-2 border-gray-300 rounded-lg bg-gray-50">
                            @if($post->status == 1)
                                <span class="text-green-600 font-semibold">Xuất bản</span>
                            @else
                                <span class="text-red-600 font-semibold">Không xuất bản</span>
                            @endif
                        </div>
                    </div>

                    <!-- Thumbnail -->
                    <div class="mb-4">
                        <label class="font-semibold text-gray-700"><strong>Ảnh đại diện:</strong></label>
                        <div class="p-3 border-2 border-gray-300 rounded-lg bg-gray-50">
                            @if($post->thumbnail)
                                <img src="{{ asset('storage/thumbnails/' . $post->thumbnail) }}" alt="{{ $post->title }}" class="w-full rounded-lg">
                            @else
                                <p class="text-gray-500">Không có ảnh đại diện</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout-admin>
