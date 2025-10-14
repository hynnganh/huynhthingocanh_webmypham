<x-layout-site>
    <x-slot:title>Danh sách yêu thích</x-slot:title>

    <div class="max-w-5xl mx-auto px-4 py-10">
        <h2 class="text-3xl font-bold text-pink-500 mb-6">
            <i class="fas fa-heart mr-2"></i> Sản phẩm bạn yêu thích
        </h2>

        @if($wishlist->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                @foreach($wishlist as $item)
                    <x-product-card :productrow="$item->product" />

                @endforeach
            </div>
        @else
            <div class="bg-white text-center p-10 rounded-xl shadow-xl">
                <i class="fas fa-heart-broken text-6xl text-gray-300 mb-4"></i>
                <p class="text-lg text-gray-600 font-medium">Bạn chưa có sản phẩm yêu thích nào.</p>
                <a href="{{ route('site.home') }}" class="mt-6 inline-block bg-pink-500 text-white font-semibold py-2 px-6 rounded-xl hover:bg-pink-600 transition shadow-md">
                    Khám phá sản phẩm ngay
                </a>
            </div>
        @endif
    </div>
</x-layout-site>