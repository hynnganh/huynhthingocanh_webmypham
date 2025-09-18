<ul class="flex justify-center items-center gap-6 py-2">
    @foreach ($menu_list as $menu_item)
        <x-main-menu-item :menuitem="$menu_item" />
    @endforeach
</ul>
