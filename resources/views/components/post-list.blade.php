<div class="bg-[#ffffff] p-3 mb-3 border border-gray-300 rounded-lg">
    <h1 class="text-3xl mb-3 text-center font-bold font-mono text-[#FF66B2] animate-pulse">Bài Viết Mới Nhất</h1>
    
    <div class="space-y-6">
        @foreach ($posts as $post)
            <div class="bg-white border border-gray-200 rounded-lg shadow hover:shadow-lg transition duration-300 overflow-hidden">
                <div class="flex flex-col sm:flex-row items-center p-4">
                    <!-- Thumbnail -->
                    <div class="w-full sm:w-1/3 h-48 overflow-hidden mb-4 sm:mb-0">
                        <img src="{{ asset('assets/images/post/'.$post->thumbnail) }}" alt="{{ $post->title }}" class="w-full h-full object-cover rounded-md">
                    </div>

                    <!-- Title and Detail -->
                    <div class="w-full sm:w-2/3 pl-0 sm:pl-4">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $post->title }}</h3>
                        
                        <!-- Detail (excerpt or short description) -->
                        <p class="text-sm text-gray-500 mb-4">
                            {{ \Illuminate\Support\Str::limit($post->detail, 100) }}
                        </p>

                        <!-- Created Date -->
                        <p class="text-xs text-gray-400 mb-4">
                            {{ \Carbon\Carbon::parse($post->created_at)->format('d/m/Y') }}
                        </p>

                        <!-- Read More Button -->
                        <a href="{{ route('site.post.show', $post) }}"
                           class="inline-block bg-[#FF66B2] text-white px-4 py-2 rounded hover:bg-[#FF3399] transition">
                            Xem chi tiết
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
