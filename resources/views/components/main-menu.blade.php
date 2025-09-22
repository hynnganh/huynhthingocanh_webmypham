@foreach ($menu_list as $menu_item)
    <x-main-menu-item :menuitem="$menu_item" />
@endforeach
