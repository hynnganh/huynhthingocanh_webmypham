<x-layout-site>
    <h1 class="text-3xl m-4 text-center font-mono text-pink-600">Chat với AI</h1>
    <div class="container mx-auto p-4">
        <div class="bg-white rounded shadow p-4 h-[80vh] flex flex-col">

            {{-- Khung chat --}}
            <div id="chat-box" class="flex-1 overflow-y-auto mb-4 space-y-4">
                @foreach($history as $chat)
                    <div class="flex {{ $chat['role'] === 'user' ? 'justify-end' : 'justify-start' }}">
                        <div class="{{ $chat['role'] === 'user'
                                        ? 'bg-pink-600 text-white rounded-lg px-4 py-2 max-w-xs'
                                        : 'bg-gray-200 text-gray-800 rounded-lg px-4 py-2 max-w-xs' }}">
                            @if(is_array($chat['content']))
                                {{-- Nếu content là sản phẩm --}}
                                <div class="flex items-center bg-gray-100 p-4 rounded-lg shadow-md mb-2">
                                    @if($chat['content']['image'] ?? false)
                                        <img src="{{ $chat['content']['image'] }}" 
                                            class="rounded-lg w-20 h-20 object-cover shadow-xl mr-4" 
                                            alt="{{ $chat['content']['name'] ?? '' }}">
                                    @endif
                                    <div>
                                        <a href="{{ $chat['content']['detail_url'] ?? '#' }}" 
                                        class="text-gray-800 font-semibold block hover:text-pink-500">
                                        {{ $chat['content']['name'] ?? '' }}
                                        </a>
                                        <p class="text-sm text-gray-600">
                                            {{ isset($chat['content']['price']) ? number_format($chat['content']['price'],0,',','.') : '' }} <sup>₫</sup>
                                        </p>
                                    </div>
                                </div>
                            @else
                                {{ $chat['content'] }}
                            @endif

                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Loading --}}
            <div id="loading" class="hidden justify-start items-center space-x-2 mb-2">
                <div class="loader border-4 border-t-pink-500 border-gray-200 rounded-full w-6 h-6 animate-spin"></div>
            </div>

            {{-- Input chat --}}
            <div class="flex">
                <input id="chat-input" type="text" placeholder="Nhập tin nhắn..."
                       class="border rounded-l px-4 py-2 flex-1 focus:outline-none">
                <button id="chat-send" class="bg-pink-600 text-white px-4 py-2 rounded-r">Gửi</button>
            </div>

            {{-- Reset --}}
            <button id="chat-reset" class="text-sm text-blue-600 mt-2">Xóa đoạn chat</button>

        </div>
    </div>

    {{-- Loader CSS --}}
    <style>
    .loader {
      border-top-color: #ec4899; /* Tailwind pink-500 */
      animation: spin 1s linear infinite;
    }
    @keyframes spin {
      to { transform: rotate(360deg); }
    }
    </style>

    <script>
    const chatBox = document.getElementById('chat-box');
    const chatInput = document.getElementById('chat-input');
    const chatSend = document.getElementById('chat-send');
    const chatReset = document.getElementById('chat-reset');
    const loading = document.getElementById('loading');

    function appendMessage(role, content){
        const div = document.createElement('div');
        div.className = 'flex ' + (role==='user' ? 'justify-end':'justify-start');

        const msg = document.createElement('div');
        msg.className = role==='user' 
            ? 'bg-pink-600 text-white rounded-lg px-4 py-2 max-w-xs'
            : 'bg-gray-200 text-gray-800 rounded-lg px-4 py-2 max-w-xs';

        if(typeof content === 'string'){
            msg.textContent = content;
        } else {
            // Nếu là sản phẩm
            msg.innerHTML = `
                <div class="flex items-center bg-gray-100 p-4 rounded-lg shadow-md mb-2">
                    ${content.image ? `<img src="${content.image}" class="rounded-lg w-20 h-20 object-cover shadow-xl mr-4" />` : ''}
                    <div>
                        <a href="${content.detail_url}" class="text-gray-800 font-semibold block hover:text-pink-500">
                            ${content.name}
                        </a>
                        <p class="text-sm text-gray-600">${Number(content.price).toLocaleString()} <sup>₫</sup></p>
                    </div>
                </div>
            `;
        }

        div.appendChild(msg);
        chatBox.appendChild(div);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    function sendPrompt(){
        const prompt = chatInput.value.trim();
        if(!prompt) return;
        appendMessage('user', prompt);
        chatInput.value='';
        loading.classList.remove('hidden');

        fetch('{{ route("chat.ai") }}',{
            method:'POST',
            headers:{
                'Content-Type':'application/json',
                'X-CSRF-TOKEN':'{{ csrf_token() }}'
            },
            body:JSON.stringify({prompt})
        })
        .then(res=>res.json())
        .then(data=>{
            loading.classList.add('hidden');
            if(data.products && data.products.length>0){
                data.products.forEach(p=>appendMessage('ai',p));
            }
            if(data.answer){
                appendMessage('ai',data.answer);
            }
        })
        .catch(err=>{
            loading.classList.add('hidden');
            appendMessage('ai','Lỗi kết nối server.');
            console.error(err);
        });
    }

    // Gửi khi click button
    chatSend.addEventListener('click', sendPrompt);

    // Gửi khi nhấn Enter
    chatInput.addEventListener('keydown', (e)=>{
        if(e.key==='Enter'){
            e.preventDefault();
            sendPrompt();
        }
    });

    // Reset chat
    chatReset.addEventListener('click',()=>{
        fetch('{{ route("chat.ai.reset") }}')
            .then(()=>{
                chatBox.innerHTML='';
                appendMessage('ai','Xin chào 👋, mình có thể giúp gì cho bạn hôm nay?');
            });
    });
    </script>
</x-layout-site>
