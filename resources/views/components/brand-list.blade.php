<h2 class="text-xl font-semibold text-pink-600 mb-4">Thương hiệu</h2>
<ul class="space-y-2">
    @foreach ($brand_list as $brand)
        <li><a href="{{ route('site.product') }}?brand_slug={{$brand->slug}}">{{ $brand->name }}</a></li>
    @endforeach
</ul>
