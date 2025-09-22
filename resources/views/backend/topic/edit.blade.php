<x-layout-admin>
    <form action="{{ route('topic.update', ['topic' => $topic->id]) }}" method="POST" class="bg-white p-6 rounded-lg shadow">
        @csrf
        @method('PUT')

        <h2 class="text-xl font-bold mb-4 text-blue-600">Cập nhật Chủ Đề</h2>

        {{-- Tên chủ đề --}}
        <div class="mb-4">
            <label for="name" class="block font-semibold mb-1">Tên chủ đề</label>
            <input type="text" name="name" id="name" value="{{ old('name', $topic->name) }}"
                   class="w-full border border-gray-300 rounded-lg p-2" placeholder="Nhập tên chủ đề">
            @error('name')
                <div class="text-red-500">{{ $message }}</div>
            @enderror
        </div>

        {{-- Mô tả --}}
        <div class="mb-4">
            <label for="description" class="block font-semibold mb-1">Mô tả</label>
            <textarea name="description" id="description" rows="4"
                      class="w-full border border-gray-300 rounded-lg p-2">{{ old('description', $topic->description) }}</textarea>
        </div>

        {{-- Trạng thái --}}
        <div class="mb-4">
            <label for="status" class="block font-semibold mb-1">Trạng thái</label>
            <select name="status" id="status" class="w-full border border-gray-300 rounded-lg p-2">
                <option value="1" {{ old('status', $topic->status) == 1 ? 'selected' : '' }}>Xuất bản</option>
                <option value="0" {{ old('status', $topic->status) == 0 ? 'selected' : '' }}>Không xuất bản</option>
            </select>
        </div>

        {{-- Nút điều hướng --}}
        <div class="flex justify-between mt-6">
            <a href="{{ route('topic.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                <i class="fa fa-arrow-left"></i> Quay lại
            </a>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                <i class="fa fa-save"></i> Cập nhật
            </button>
        </div>
    </form>
</x-layout-admin>
