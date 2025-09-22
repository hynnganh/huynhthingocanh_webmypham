<x-layout-admin>
    <form action="{{ route('banner.update',['banner'=> $banner->id]) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="content-wrapper">
            <div class="border border-blue-100 mb-3 rounded-lg p-2">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-blue-600">CẬP NHẬP BANNER</h2>
                    <div class="text-right">
                        <button type="submit" class="bg-green-500 px-2 py-2 cursor-pointer rounded-xl mx-1 text-white">
                            <i class="fa fa-save" aria-hidden="true"></i> Lưu
                        </button>
                        <a href="{{ route('banner.index') }}" class="bg-sky-500 px-2 py-2 rounded-xl mx-1 text-white">
                            <i class="fa fa-arrow-left" aria-hidden="true"></i> Về danh sách
                        </a>
                    </div>
                </div>
            </div>

            <div class="border border-blue-100 rounded-lg p-3">
                <div class="flex gap-6">
                    <div class="basis-2/3">
                        <div class="mb-3">
                            <label for="name"><strong>Tên banner</strong></label>
                            <input type="text" class="w-full border border-gray-300 rounded-lg p-2" placeholder="Tên banner" name="name" id="name" value="{{ old('name', $banner->name) }}">
                            @error('name')
                                <div class="text-red-500">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description"><strong>Mô tả</strong></label>
                            <textarea name="description" id="description" class="w-full border border-gray-300 rounded-lg p-2">{{ old('description',$banner->description) }}</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="position"><strong>Vị trí hiển thị (Position)</strong></label>
                            <select name="position" id="position" class="w-full border border-gray-300 rounded-lg p-2">
                                <option value="slideshow" {{ old('position') == 'slideshow' ? 'selected' : '' }}>Slideshow</option>
                                <option value="ads" {{ old('position') == 'ads' ? 'selected' : '' }}>Ads</option>
                            </select>
                            @error('position')
                                <div class="text-red-500">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        
                    </div>

                    <div class="basis-1/3">
                        <div class="mb-3"> 
                            <label for="image"><strong>Hình ảnh</strong></label>
                            <input type="file" name="image" id="image" class="w-full border border-gray-300 rounded-lg p-2">
                            <small class="text-gray-500">Chỉ chấp nhận ảnh .jpg, .jpeg, .png (kích thước tối đa 2MB)</small>
                        </div>

                        <div class="mb-3">
                            <label for="status"><strong>Trạng thái</strong></label>
                            <select name="status" id="status" class="w-full border border-gray-300 rounded-lg p-2">
                                <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Xuất bản</option>
                                <option value="0" {{ old('status', 1) == 0 ? 'selected' : '' }}>Không xuất bản</option>
                            </select>
                            @error('status')
                                <div class="text-red-500">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        
                    </div>
                </div>
            </div>

        </div>
    </form>
</x-layout-admin>
