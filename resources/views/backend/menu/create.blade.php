<x-layout-admin>

    <div class="flex justify-between items-center bg-white p-4 rounded-lg shadow">
        <h1 class="text-lg font-bold">Thêm Menu</h1>
    </div>

    <div class="mt-6 bg-white p-4 rounded-lg shadow">
        <form action="{{ route('menu.store') }}" method="POST">
            @csrf

            <!-- Tên menu -->
            <div class="mb-4">
                <label for="name" class="block font-semibold mb-1">Tên Menu</label>
                <input type="text" name="name" id="name" class="w-full border border-gray-300 rounded-lg p-2"
                    value="{{ old('name') }}" placeholder="Nhập tên menu">
                @error('name')
                    <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>

            <!-- Link menu -->
            <div class="mb-4">
                <label for="link" class="block font-semibold mb-1">Link</label>
                <input type="text" name="link" id="link" class="w-full border border-gray-300 rounded-lg p-2"
                    value="{{ old('link') }}" placeholder="Nhập link">
                @error('link')
                    <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>

            <!-- Type -->
            <div class="mb-4">
                <label for="type" class="block font-semibold mb-1">Loại Menu</label>
                <select name="type" id="type" class="w-full border border-gray-300 rounded-lg p-2">
                    @foreach ($menuTypes as $key => $value)
                        <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>
                            {{ $value }}</option>
                    @endforeach
                </select>
                @error('type')
                    <div class="text-red-500">{{ $message }}</div>
                @enderror
            </div>
             <div class="mb-4">
                <label for="position"  class="block font-semibold mb-1" >Vị trí hiển thị</label>
                <select name="position" class="w-full border border-gray-300 rounded-lg p-2">
                    <option value="mainmenu"
                        {{ old('position', $menu->position ?? '') == 'mainmenu' ? 'selected' : '' }}>Main Menu</option>
                    <option value="footer" {{ old('position', $menu->position ?? '') == 'footer' ? 'selected' : '' }}>
                        Footer</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Chọn menu cha:</label>
                <select name="parent_id" class="w-full border rounded px-3 py-2">
                    <option value="0">-- Không có --</option>
                    @foreach ($menus as $m)
                        <option value="{{ $m->id }}"
                            {{ isset($menu) && $menu->parent_id == $m->id ? 'selected' : '' }}>
                            {{ $m->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Trạng thái -->
            <div class="mb-4">
                <label for="status" class="block font-semibold mb-1">Trạng thái</label>
                <select name="status" id="status" class="w-full border border-gray-300 rounded-lg p-2">
                    <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Hiển thị</option>
                    <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Ẩn</option>
                </select>
            </div>

            <!-- Nút gửi form -->
            <div class="flex justify-between mt-6">
                <a href="{{ route('menu.index') }}"
                    class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">Quay lại</a>
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">Thêm
                    mới</button>
            </div>
        </form>
    </div>

</x-layout-admin>
