@foreach ($menu_list as $menu_item)
    <x-menu-footer-item :menuitem="$menu_item" />
@endforeach
