<x-layout-admin>
    <form action="{{ route('product.update',['product'=> $product->id]) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="content-wrapper">
            <div class="border border-blue-100 mb-3 rounded-lg p-2">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-blue-600">CẬP NHẬP SẢN PHẨM</h2>
                    <div class="text-right">
                        <button type="submit" class="bg-green-500 px-2 py-2 cursor-pointer rounded-xl mx-1 text-white">
                            <i class="fa fa-save" aria-hidden="true"></i> Lưu
                        </button>
                        <a href="{{ route('product.index') }}" class="bg-sky-500 px-2 py-2 rounded-xl mx-1 text-white">
                            <i class="fa fa-arrow-left" aria-hidden="true"></i> Về danh sách
                        </a>
                    </div>
                </div>
            </div>

            <div class="border border-blue-100 rounded-lg p-3">
                <div class="flex gap-6">
                    <div class="basis-2/3">
                        <div class="mb-3">
                            <label for="name"><strong>Tên sản phẩm</strong></label>
                            <input type="text" class="w-full border border-gray-300 rounded-lg p-2" placeholder="Tên sản phẩm" name="name" id="name" value="{{ old('name', $product->name) }}">
                            @error('name')
                                <div class="text-red-500">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="detail"><strong>Chi tiết sản phẩm</strong></label>
                            <textarea name="detail" id="detail" rows="4" class="w-full border border-gray-300 rounded-lg p-2">{{ old('detail',$product->detail) }}</textarea>
                            @error('detail')
                                <div class="text-red-500">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description"><strong>Mô tả</strong></label>
                            <textarea name="description" id="description" class="w-full border border-gray-300 rounded-lg p-2">{{ old('description',$product->description) }}</textarea>
                        </div>
                        
                        <div class="flex justify-between gap-5">
                            <div class="mb-3 w-1/3">
                                <label for="price_root"><strong>Giá gốc</strong></label>
                                <input type="number" class="w-full border border-gray-300 rounded-lg p-2" placeholder="Giá gốc" name="price_root" id="price_root" value="{{ old('price_root',$product->price_root) }}">
                                @error('price_root')
                                    <div class="text-red-500">{{ $message }}</div>
                                @enderror
                            </div>
    
                            <div class="mb-3 w-1/3">
                                <label for="price_sale"><strong>Giá khuyến mãi</strong></label>
                                <input type="number" class="w-full border border-gray-300 rounded-lg p-2" placeholder="Giá khuyến mãi" name="price_sale" id="price_sale" value="{{ old('price_sale',$product->price_sale) }}">
                                @error('price_sale')
                                    <div class="text-red-500">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="qty"><strong>Số lượng</strong></label>
                                <input type="number" class="w-full border border-gray-300 rounded-lg p-2" value="1" name="qty" min="1" id="qty">
                            </div>
                        </div>
                    </div>

                    <div class="basis-1/3">
                        <div class="mb-3">
                            <label for="category_id"><strong>Danh mục</strong></label>
                            <select name="category_id" id="category_id" class="w-full border border-gray-300 rounded-lg p-2">
                                <option value="">Chọn danh mục</option>
                                @foreach ($list_category as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id',$product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <div class="text-red-500">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="brand_id"><strong>Thương hiệu</strong></label>
                            <select name="brand_id" id="brand_id" class="w-full border border-gray-300 rounded-lg p-2">
                                <option value="">Chọn thương hiệu</option>
                                @foreach ($list_brand as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id',$product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                            @error('brand_id')
                                <div class="text-red-500">{{ $message }}</div> 
                            @enderror
                        </div>
                        

<div class="mb-3">
    <label for="thumbnail"><strong>Hình ảnh</strong></label>
    <input type="file" name="thumbnail" id="thumbnail" class="w-full border border-gray-300 rounded-lg p-2" accept="image/*">
    <small class="text-gray-500 block mb-2">Chỉ chấp nhận ảnh .jpg, .jpeg, .png (kích thước tối đa 2MB)</small>

    <div class="mt-2">
        <p class="text-sm text-gray-600 mb-1">Ảnh hiện tại / ảnh mới chọn:</p>
        <img 
            id="preview-image" 
            src="{{ asset('assets/images/product/' . $product->thumbnail) }}" 
            alt="Ảnh hiện tại" 
            class="w-40 h-40 object-cover rounded-lg border border-gray-300"
        >
    </div>
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
    @push('scripts')
    <!-- CKEditor 5 -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#detail'), {
                toolbar: [
                    'undo', 'redo', '|',
                    'heading', '|',
                    'bold', 'italic', 'underline', 'link', '|',
                    'bulletedList', 'numberedList', 'blockQuote', '|',
                    'insertTable', 'mediaEmbed'
                ]
            })
            .catch(error => {
                console.error(error);
            });
    </script>
@endpush
@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#detail'), {
            toolbar: [
                'undo', 'redo', '|',
                'heading', '|',
                'bold', 'italic', 'underline', 'link', '|',
                'bulletedList', 'numberedList', 'blockQuote', '|',
                'insertTable', 'mediaEmbed'
            ]
        })
        .catch(error => {
            console.error(error);
        });

    // 🖼️ Preview ảnh mới
    document.getElementById('thumbnail').addEventListener('change', function (event) {
        const file = event.target.files[0];
        const preview = document.getElementById('preview-image');
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result; // hiển thị ảnh mới chọn
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush

</x-layout-admin>
