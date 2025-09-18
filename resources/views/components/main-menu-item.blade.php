<li class="group relative">
    <a href="{{ $menu->link }}" class="px-4 py-2 block text-white hover:bg-pink-600 transition">
        {{ $menu->name }}
    </a>

    @if ($menu_list !== null && $menu_list->count() > 0)
        <ul class="absolute left-0 top-full hidden group-hover:block bg-white shadow-lg p-2 rounded z-50 min-w-[180px]">
            @foreach ($menu_list as $item)
                <li>
                    <a href="{{ $item->link }}"
                       class="block px-4 py-2 text-gray-800 hover:bg-gray-100 rounded">
                        {{ $item->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</li>
