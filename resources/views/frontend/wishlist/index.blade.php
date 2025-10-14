<x-layout-site>
    <x-slot:title>Danh sách yêu thích</x-slot:title>

    <div class="max-w-5xl mx-auto px-4 py-10">
        <h2 class="text-3xl font-bold text-pink-500 mb-6">
            <i class="fas fa-heart mr-2"></i> Sản phẩm bạn yêu thích
        </h2>

        @if($wishlist->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                @foreach($wishlist as $item)
                    <div class="bg-white shadow-lg rounded-lg p-4 text-center">
                        <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" class="rounded-md mb-4">
                        <h3 class="font-semibold text-gray-800">{{ $item->product->name }}</h3>
                        <p class="text-pink-500 font-bold">{{ number_format($item->product->price) }} VNĐ</p>
                        <form action="{{ route('wishlist.remove') }}" method="POST" class="mt-3">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $item->product->id }}">
                            <button class="bg-red-500 text-white px-3 py-2 rounded-md hover:bg-red-600">
                                Xóa
                            </button>
                        </form>
                    </div>
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
