<x-layout-site>
    <h1 class="text-3xl m-4 text-center font-extrabold text-pink-600 tracking-wider">
        <i class="fas fa-robot mr-2"></i> Trợ Lý AI Mua Sắm 🛍️
    </h1>

    <div class="container mx-auto p-4 max-w-4xl">
        <div class="bg-white rounded-2xl shadow-2xl p-6 h-[80vh] flex flex-col border border-pink-100">
            
            <!-- Chat Box -->
            <div id="chat-box" class="flex-1 overflow-y-auto mb-4 space-y-6 pr-2 custom-scrollbar">
                @foreach($history as $chat)
                    <div class="flex {{ $chat['role'] === 'user' ? 'justify-end' : 'justify-start' }}">
                        @if($chat['role'] !== 'user')
                            <div class="w-8 h-8 rounded-full bg-pink-500 flex items-center justify-center text-white mr-2 flex-shrink-0">
                                <i class="fas fa-robot text-sm"></i>
                            </div>
                        @endif

                        <div class="max-w-xs sm:max-w-md lg:max-w-lg">
                            <div class="{{ $chat['role'] === 'user'
                                            ? 'bg-pink-500 text-white rounded-t-xl rounded-bl-xl px-4 py-3 shadow-md'
                                            : 'bg-gray-100 text-gray-800 rounded-t-xl rounded-br-xl px-4 py-3 shadow-md border border-gray-200' }}">
                                @if(is_array($chat['content']) && isset($chat['content']['name']))
                                    <div class="flex items-center bg-white p-3 rounded-xl shadow-inner border border-pink-100">
                                        @if($chat['content']['image'] ?? false)
                                            <img src="{{ $chat['content']['image'] }}" 
                                                class="rounded-lg w-16 h-16 object-cover mr-3 flex-shrink-0" 
                                                alt="{{ $chat['content']['name'] }}">
                                        @endif
                                        <div class="flex-grow">
                                            <a href="{{ $chat['content']['detail_url'] ?? '#' }}" 
                                               class="text-gray-800 font-bold block hover:text-pink-600 transition duration-200 text-sm line-clamp-2">
                                                {{ $chat['content']['name'] }}
                                            </a>
                                            <p class="text-xs text-red-500 font-semibold mt-1">
                                                {{ number_format($chat['content']['price'],0,',','.') }} <sup>₫</sup>
                                            </p>
                                        </div>
                                    </div>
                                @else
                                    {!! nl2br(e(is_array($chat['content']) ? json_encode($chat['content']) : $chat['content'])) !!}
                                @endif
                            </div>
                        </div>

                        @if($chat['role'] === 'user')
                            <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white ml-2 flex-shrink-0">
                                <i class="fas fa-user text-sm"></i>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Input + Send -->
            <div class="flex items-center mt-auto border-t pt-4 space-x-2">
                <input id="chat-input" type="text" placeholder="Hỏi AI về sản phẩm, khuyến mãi,..."
                       class="border border-gray-300 rounded-l-xl px-5 py-3 flex-1 focus:ring-2 focus:ring-pink-400 focus:border-pink-400 text-gray-700 transition duration-200">
                <button id="chat-send" 
                        class="bg-pink-600 text-white px-6 py-3 rounded-r-xl font-semibold hover:bg-pink-700 transition duration-200 shadow-lg shadow-pink-200">
                    <i class="fas fa-paper-plane"></i>
                </button>
                <!-- 🧹 Nút Reset -->
                <form action="{{ route('chat.ai.reset') }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="bg-gray-200 text-gray-700 px-4 py-3 rounded-xl hover:bg-gray-300 transition duration-200 font-semibold shadow">
                        <i class="fas fa-broom mr-1"></i> Xóa Chat
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- 🔄 Loading Animation Style -->
    <style>
        .loading-dots {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .loading-dots span {
            width: 8px;
            height: 8px;
            background: #ec4899;
            border-radius: 50%;
            margin: 0 2px;
            animation: blink 1.2s infinite ease-in-out;
        }
        .loading-dots span:nth-child(2) { animation-delay: 0.2s; }
        .loading-dots span:nth-child(3) { animation-delay: 0.4s; }

        @keyframes blink {
            0%, 80%, 100% { opacity: 0.3; transform: translateY(0); }
            40% { opacity: 1; transform: translateY(-3px); }
        }
    </style>

    <!-- JS -->
    <script>
        const chatBox = document.getElementById('chat-box');
        const chatInput = document.getElementById('chat-input');
        const chatSend = document.getElementById('chat-send');

        let loadingDiv = null;

        function scrollBottom() {
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        function appendMessage(role, content) {
            const div = document.createElement('div');
            div.className = 'flex ' + (role === 'user' ? 'justify-end' : 'justify-start');

            const wrapper = document.createElement('div');
            wrapper.className = 'max-w-xs sm:max-w-md lg:max-w-lg';

            const msg = document.createElement('div');
            msg.className = role === 'user'
                ? 'bg-pink-500 text-white rounded-t-xl rounded-bl-xl px-4 py-3 shadow-md'
                : 'bg-gray-100 text-gray-800 rounded-t-xl rounded-br-xl px-4 py-3 shadow-md border border-gray-200';

            if (typeof content === 'string') {
                msg.innerHTML = content.replace(/\n/g, '<br>');
            } else {
                const priceFormatted = Number(content.price).toLocaleString('vi-VN') + '<sup>₫</sup>';
                msg.innerHTML = `
                    <div class="flex items-center bg-white p-3 rounded-xl shadow-inner border border-pink-100">
                        ${content.image ? `<img src="${content.image}" class="rounded-lg w-16 h-16 object-cover mr-3 flex-shrink-0" />` : ''}
                        <div class="flex-grow">
                            <a href="${content.detail_url}" class="text-gray-800 font-bold block hover:text-pink-600 transition duration-200 text-sm line-clamp-2">${content.name}</a>
                            <p class="text-xs text-red-500 font-semibold mt-1">${priceFormatted}</p>
                        </div>
                    </div>
                `;
            }

            wrapper.appendChild(msg);

            if (role !== 'user') {
                const aiAvatar = document.createElement('div');
                aiAvatar.className = 'w-8 h-8 rounded-full bg-pink-500 flex items-center justify-center text-white mr-2 flex-shrink-0';
                aiAvatar.innerHTML = '<i class="fas fa-robot text-sm"></i>';
                div.appendChild(aiAvatar);
                div.appendChild(wrapper);
            } else {
                div.appendChild(wrapper);
                const userAvatar = document.createElement('div');
                userAvatar.className = 'w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white ml-2 flex-shrink-0';
                userAvatar.innerHTML = '<i class="fas fa-user text-sm"></i>';
                div.appendChild(userAvatar);
            }

            chatBox.appendChild(div);
            scrollBottom();
            return div;
        }

        function showLoading() {
            loadingDiv = appendMessage('ai', `<div class="loading-dots"><span></span><span></span><span></span></div>`);
        }

        function hideLoading() {
            if (loadingDiv) {
                loadingDiv.remove();
                loadingDiv = null;
            }
        }

        chatSend.addEventListener('click', () => {
            const prompt = chatInput.value.trim();
            if (!prompt) return;
            appendMessage('user', prompt);
            chatInput.value = '';
            showLoading();

            fetch('{{ route("chat.ai.ask") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ prompt })
            })
            .then(res => res.json())
            .then(data => {
                hideLoading();
                if (data.answer) appendMessage('ai', data.answer);
                if (data.products) data.products.forEach(p => appendMessage('ai', p));
            })
            .catch(err => {
                hideLoading();
                appendMessage('ai', '⚠️ Lỗi kết nối server.');
                console.error(err);
            });
        });

        chatInput.addEventListener('keydown', e => {
            if (e.key === 'Enter') {
                e.preventDefault();
                chatSend.click();
            }
        });

        scrollBottom();
    </script>
</x-layout-site>
