<x-layout-admin>
    <form action="{{ route('post.update', ['post' => $post->id]) }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow">
        @csrf
        @method('PUT')

        <h2 class="text-xl font-bold mb-4 text-blue-600">Cập nhật Bài Viết</h2>

        {{-- Tiêu đề --}}
        <div class="mb-4">
            <label for="title" class="block font-semibold mb-1">Tiêu đề</label>
            <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}"
                   class="w-full border border-gray-300 rounded-lg p-2" placeholder="Nhập tiêu đề bài viết">
            @error('title')
                <div class="text-red-500">{{ $message }}</div>
            @enderror
        </div>

        {{-- Mô tả ngắn --}}
        <div class="mb-4">
            <label for="description" class="block font-semibold mb-1">Mô tả ngắn</label>
            <textarea name="description" id="description"
                      class="w-full border border-gray-300 rounded-lg p-2" rows="3">{{ old('description', $post->description) }}</textarea>
        </div>

        {{-- Chi tiết bài viết --}}
        <div class="mb-4">
            <label for="detail" class="block font-semibold mb-1">Chi tiết bài viết</label>
            <textarea name="detail" id="detail"
                      class="w-full border border-gray-300 rounded-lg p-2" rows="5">{{ old('detail', $post->detail) }}</textarea>
        </div>

        {{-- Chủ đề --}}
        <div class="mb-4">
            <label for="topic_id" class="block font-semibold mb-1">Chủ đề</label>
            <select name="topic_id" id="topic_id" class="w-full border border-gray-300 rounded-lg p-2">
                <option value="">-- Chọn chủ đề --</option>
                @foreach ($list_topic as $topic)
                    <option value="{{ $topic->id }}" {{ old('topic_id', $post->topic_id) == $topic->id ? 'selected' : '' }}>
                        {{ $topic->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Ảnh đại diện --}}
        <div class="mb-4">
            <label for="thumbnail" class="block font-semibold mb-1">Ảnh đại diện</label>
            @if($post->thumbnail)
                <div class="mb-2">
                    <img src="{{ asset('assets/images/post/' . $post->thumbnail) }}" alt="{{ $post->title }}" class="w-32 rounded">
                </div>
            @endif
            <input type="file" name="thumbnail" id="thumbnail" class="w-full border border-gray-300 rounded-lg p-2">
            <small class="text-gray-500">Chỉ chấp nhận ảnh jpg, jpeg, png. Tối đa 2MB.</small>
        </div>

        {{-- Trạng thái --}}
        <div class="mb-4">
            <label for="status" class="block font-semibold mb-1">Trạng thái</label>
            <select name="status" id="status" class="w-full border border-gray-300 rounded-lg p-2">
                <option value="1" {{ old('status', $post->status) == 1 ? 'selected' : '' }}>Xuất bản</option>
                <option value="0" {{ old('status', $post->status) == 0 ? 'selected' : '' }}>Không xuất bản</option>
            </select>
        </div>

        {{-- Nút --}}
        <div class="flex justify-between mt-6">
            <a href="{{ route('post.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">Quay lại</a>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Cập nhật</button>
        </div>
    </form>
</x-layout-admin>
