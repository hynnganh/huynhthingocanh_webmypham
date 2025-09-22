<x-layout-admin>
    <form action="{{ route('category.store') }}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="content-wrapper">
            <div class="border border-blue-100 mb-3 rounded-lg p-2">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-blue-600">THÊM DANH MỤC</h2>
                    <div class="text-right">
                        <button type="submit" class="bg-green-500 px-2 py-2 cursor-pointer rounded-xl mx-1 text-white">
                            <i class="fa fa-save" aria-hidden="true"></i> Lưu
                        </button>
                        <a href="{{ route('category.index') }}" class="bg-sky-500 px-2 py-2 rounded-xl mx-1 text-white">
                            <i class="fa fa-arrow-left" aria-hidden="true"></i> Về danh sách
                        </a>
                    </div>
                </div>
            </div>

            <div class="border border-blue-100 rounded-lg p-3">
                <div class="mb-3">
                    <label for="name"><strong>Tên danh mục</strong></label>
                    <input type="text" name="name" id="name" class="w-full border border-gray-300 rounded-lg p-2" placeholder="Nhập tên danh mục" value="{{ old('name') }}" oninput="generateSlug()">
                    @error('name')
                        <div class="text-red-500">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Mô tả -->
                <div class="mb-3">
                    <label for="description"><strong>Mô tả</strong></label>
                    <textarea name="description" id="description" class="w-full border border-gray-300 rounded-lg p-2">{{ old('description') }}</textarea>
                </div>

                <!-- Trạng thái -->
                <div class="mb-3">
                    <label for="status"><strong>Trạng thái</strong></label>
                    <select name="status" id="status" class="w-full border border-gray-300 rounded-lg p-2">
                        <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Xuất bản</option>
                        <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Không xuất bản</option>
                    </select>
                    @error('status')
                        <div class="text-red-500">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Hình ảnh -->
                <div class="mb-3">
                    <label for="image"><strong>Hình ảnh</strong></label>
                    <input type="file" name="image" id="image" class="w-full border border-gray-300 rounded-lg p-2">
                    <small class="text-gray-500">Chỉ chấp nhận ảnh .jpg, .jpeg, .png (kích thước tối đa 2MB)</small>
                    @error('image')
                        <div class="text-red-500">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </form>
</x-layout-admin>
