<x-layout-site>
    <x-slot:title>
        Chi tiết bài viết
    </x-slot:title>

    <main class="bg-pink-50 py-10">
        <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-lg grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Chi tiết bài viết chính -->
            <div class="col-span-2 border border-gray-300 rounded-lg p-4 flex flex-col">
                <div class="w-full">
                    <h1 class="text-3xl mb-3 text-center font-bold font-mono text-[#FF66B2] animate-pulse">{{ $post->title }}</h1>

                    <div class="mb-6">
                        <img src="{{ asset('assets/images/post/'.$post->thumbnail) }}" alt="{{ $post->title }}" class="w-full h-64 object-cover rounded-md shadow-lg mb-4">
                    </div>

                    <div class="mb-6">
                        <p class="text-xs text-gray-400 mb-4">
                            {{ \Carbon\Carbon::parse($post->created_at)->format('d/m/Y') }}
                        </p>
                        <p class="text-lg text-gray-700">
                            {!! nl2br(e($post->detail)) !!}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Các bài viết cùng chủ đề nằm bên phải -->
            <div class="col-span-1 border border-gray-300 rounded-lg p-4">
                <h3 class="text-xl font-semibold text-pink-600 mb-4">Bài viết cùng chủ đề</h3>
            
                @if($relatedPosts->count() > 0)
                    @foreach($relatedPosts as $relatedPost)
                        <div class="flex items-center bg-gray-100 p-4 rounded-lg shadow-md mb-4">
                            <img src="{{ asset('assets/images/post/' . $relatedPost->thumbnail) }}"
                                 class="rounded-lg w-20 h-20 object-cover shadow-xl mr-4"
                                 alt="{{ $relatedPost->title }}" />
                            <div>
                                <a href="{{ route('site.post.show', ['post' => $relatedPost->id]) }}"
                                    class="text-gray-800 font-semibold block hover:text-pink-500">
                                     {{ $relatedPost->title }}
                                 </a>
                                <p class="text-sm text-gray-600">{{ \Illuminate\Support\Str::limit($relatedPost->detail, 80) }}</p>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-gray-500 italic">Không có bài viết nào liên quan.</p>
                @endif
            </div>
        </div>
    </main>
</x-layout-site>
