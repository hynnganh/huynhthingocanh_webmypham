<x-layout-site>
    <x-slot:title>Thông báo</x-slot:title>

    <main class="container mx-auto mt-10 mb-12 max-w-3xl p-4">
        <h1 class="text-3xl font-bold text-pink-600 mb-6 text-center">
            <i class="fas fa-bell"></i> Thông báo
        </h1>

        @php
            // Lấy tất cả contact của user đã được admin trả lời
            $notifications = \App\Models\Contact::where('user_id', auth()->id())
                                                ->where('status', 1)
                                                ->orderBy('updated_at', 'desc')
                                                ->get();
        @endphp

        @if($notifications->isEmpty())
            <p class="text-center text-gray-500">Hiện chưa có thông báo nào.</p>
        @else
            <ul class="space-y-4">
                @foreach($notifications as $contact)
                    @php
                        // Nội dung phản hồi admin: nếu dùng reply_id lấy bản ghi khác, còn không thì dùng reply_content
                        $adminReply = $contact->reply ? $contact->reply->content : ($contact->reply_content ?? 'Admin đã trả lời');
                    @endphp

                    <li class="bg-white border border-pink-100 rounded-xl shadow-md p-4 hover:shadow-lg transition flex justify-between items-center">
                        <div>
                            <p class="font-semibold text-gray-800">Admin đã trả lời tin nhắn của bạn:</p>
                            <p class="text-gray-600 mt-1">{{ Str::limit($adminReply, 100) }}</p>
                        </div>
                        <button 
                            class="text-pink-600 font-semibold hover:underline" 
                            onclick="document.getElementById('modal-{{ $contact->id }}').classList.remove('hidden')">
                            Xem chi tiết
                        </button>
                    </li>

                    <!-- Modal chi tiết -->
                    <div id="modal-{{ $contact->id }}" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-50">
                        <div class="bg-white rounded-xl shadow-lg w-full max-w-xl p-6 relative">
                            <button onclick="document.getElementById('modal-{{ $contact->id }}').classList.add('hidden')" 
                                    class="absolute top-3 right-3 text-gray-500 hover:text-gray-800">
                                <i class="fas fa-times"></i>
                            </button>

                            <h2 class="text-2xl font-bold text-pink-600 mb-4">Chi tiết liên hệ</h2>
                            <div class="space-y-3 text-gray-700">
                                <p><span class="font-semibold">Tên:</span> {{ $contact->name }}</p>
                                <p><span class="font-semibold">Email:</span> {{ $contact->email }}</p>
                                <p><span class="font-semibold">Số điện thoại:</span> {{ $contact->phone }}</p>
                                <p><span class="font-semibold">Tiêu đề:</span> {{ $contact->title }}</p>
                                <p><span class="font-semibold">Tin nhắn của bạn:</span></p>
                                <div class="bg-gray-100 p-3 rounded-lg border border-gray-200">{{ $contact->content }}</div>

                                <p class="mt-3"><span class="font-semibold text-green-600">Phản hồi của admin:</span></p>
                                <div class="bg-gray-100 p-3 rounded-lg border border-gray-200">
                                    {{ $adminReply }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </ul>
        @endif
    </main>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</x-layout-site>
