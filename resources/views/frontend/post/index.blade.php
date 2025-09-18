<!-- resources/views/posts/index.blade.php -->

<x-layout-site>
    <x-slot:title>
        Tất cả bài viết
    </x-slot:title>

    <main class="py-10 px-4 bg-gray-50">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-3xl font-semibold text-center mb-6 text-pink-600">Tất cả bài viết</h2>
    
            <!-- Hiển thị các bài viết -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($posts as $post)
                    <div class="bg-white p-6 border border-gray-200 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 ease-in-out">
                        <img src="{{ asset('assets/images/post/'.$post->thumbnail) }}" alt="{{ $post->title }}" class="w-full h-48 object-cover rounded-lg mb-4">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $post->title }}</h3>
                        <p class="text-sm text-gray-600 mb-4">{{ \Illuminate\Support\Str::limit($post->detail, 120) }}</p>
                        <a href="{{ route('site.post.show', $post) }}" class="text-pink-500 hover:text-pink-600 font-medium">Đọc thêm</a>
                    </div>
                @endforeach
            </div>
    
            <!-- Phân trang -->
            <div class="mt-8 text-center">
                {{ $posts->links() }} 
            </div>
        </div>
    </main>
    
</x-layout-site>
