<x-layout-admin>
    <div class="content-wrapper">
        <!-- Header -->
        <div class="border border-blue-100 mb-3 rounded-lg p-2">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-blue-600">CHI TIẾT MENU</h2>
                <div class="text-right">
                    <a href="{{ route('menu.index') }}" class="bg-sky-500 px-4 py-2 rounded-xl mx-1 text-white flex items-center">
                        <i class="fa fa-arrow-left mr-2" aria-hidden="true"></i> Về danh sách
                    </a>
                </div>
            </div>
        </div>

        <!-- Menu Details -->
        <div class="border border-blue-100 rounded-lg p-4">
            <div class="flex gap-6">
                <!-- Left Side - Menu Details -->
                <div class="basis-2/3">
                    <div class="mb-4">
                        <label class="font-semibold text-gray-700"><strong>Tên Menu:</strong></label>
                        <div class="p-3 border-2 border-gray-300 rounded-lg bg-gray-50">{{ $menu->name }}</div>
                    </div>

                    <div class="mb-4">
                        <label class="font-semibold text-gray-700"><strong>Link:</strong></label>
                        <div class="p-3 border-2 border-gray-300 rounded-lg bg-gray-50">{{ $menu->link }}</div>
                    </div>

                    <div class="mb-4">
                        <label class="font-semibold text-gray-700"><strong>Loại Menu:</strong></label>
                        <div class="p-3 border-2 border-gray-300 rounded-lg bg-gray-50">{{ $menu->type }}</div>
                    </div>

                    <div class="mb-4">
                        <label class="font-semibold text-gray-700"><strong>Trạng thái:</strong></label>
                        <div class="p-3 border-2 border-gray-300 rounded-lg bg-gray-50">
                            @if($menu->status == 1)
                                <span class="text-green-600 font-semibold">Hiển thị</span>
                            @else
                                <span class="text-red-600 font-semibold">Ẩn</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Side - Parent Menu -->
                <div class="basis-1/3">
                    <div class="mb-4">
                        <label class="font-semibold text-gray-700"><strong>Menu Cha:</strong></label>
                        <div class="p-3 border-2 border-gray-300 rounded-lg bg-gray-50">
                            @if($menu->parent_id == 0)
                                -- Không có --
                            @else
                                {{ $menu->parent->name }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout-admin>
