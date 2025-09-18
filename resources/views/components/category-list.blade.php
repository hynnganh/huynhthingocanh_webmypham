<h2 class="text-xl font-semibold text-pink-600 mb-4">Danh mục</h2>
<ul class="mb-6 space-y-2">
    @foreach ($category_list as $category)
        <li>
            <a href="{{ route('site.product') }}?category_slug={{ $category->slug }}">
                {{ $category->name }}
            </a>
        </li>
    @endforeach
</ul>
