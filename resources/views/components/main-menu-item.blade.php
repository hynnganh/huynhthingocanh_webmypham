@if ($menu_list !== null)
<li class="group relative borde hover:text-white p-2">
    <a href="{{ $menu->link }}" class="text-white hover:text-white block">
        {{ $menu->name }}
    </a>

    @if ($menu_list->count() > 0)
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

@else
  <li class="border-1 border-[#F191A8] rounded-lg hover:bg-[#F191A8] hover:text-white p-2">
    <a href="{{ $menu->link }}" class="text-white hover:text-white">{{ $menu->name }}</a>
</li>
@endif


