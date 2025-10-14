
<div class="w-full">
    <ul class="flex justify-between items-center w-full max-w-6xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
        @foreach ($menu_list as $menu_item)
            <x-main-menu-item :menuitem="$menu_item" />
        @endforeach
    </ul>
</div>