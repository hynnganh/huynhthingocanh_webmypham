<x-layout-site>
    <h1 class="text-3xl m-4 text-center font-extrabold text-pink-600 tracking-wider">
        <i class="fas fa-robot mr-2"></i> Trợ Lý AI Mua Sắm
    </h1>

    <div class="container mx-auto p-4 max-w-4xl">
        <div class="bg-white rounded-2xl shadow-2xl p-6 h-[80vh] flex flex-col border border-pink-100">
            
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
            <div class="flex items-center mt-auto border-t pt-4">
                <input id="chat-input" type="text" placeholder="Hỏi AI về sản phẩm, khuyến mãi,..."
                       class="border border-gray-300 rounded-l-xl px-5 py-3 flex-1 focus:ring-2 focus:ring-pink-400 focus:border-pink-400 text-gray-700 transition duration-200">
                <button id="chat-send" class="bg-pink-600 text-white px-6 py-3 rounded-r-xl font-semibold hover:bg-pink-700 transition duration-200 shadow-lg shadow-pink-200">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- JS Append Message -->
    <script>
        
        const chatBox = document.getElementById('chat-box');
        const chatInput = document.getElementById('chat-input');
        const chatSend = document.getElementById('chat-send');

        function appendMessage(role, content){
            const div = document.createElement('div');
            div.className = 'flex ' + (role==='user' ? 'justify-end':'justify-start');

            const wrapper = document.createElement('div');
            wrapper.className = 'max-w-xs sm:max-w-md lg:max-w-lg';

            const msg = document.createElement('div');
            msg.className = role==='user' 
                ? 'bg-pink-500 text-white rounded-t-xl rounded-bl-xl px-4 py-3 shadow-md'
                : 'bg-gray-100 text-gray-800 rounded-t-xl rounded-br-xl px-4 py-3 shadow-md border border-gray-200';

            if(typeof content === 'string'){
                msg.innerHTML = content.replace(/\n/g, '<br>');
            } else {
                const priceFormatted = Number(content.price).toLocaleString('vi-VN', {style: 'currency', currency: 'VND'}).replace('₫','<sup>₫</sup>');
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
            div.appendChild(wrapper);

            if(role !== 'user'){
                const aiAvatar = document.createElement('div');
                aiAvatar.className = 'w-8 h-8 rounded-full bg-pink-500 flex items-center justify-center text-white mr-2 flex-shrink-0';
                aiAvatar.innerHTML = '<i class="fas fa-robot text-sm"></i>';
                div.insertBefore(aiAvatar, wrapper);
            } else {
                const userAvatar = document.createElement('div');
                userAvatar.className = 'w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white ml-2 flex-shrink-0';
                userAvatar.innerHTML = '<i class="fas fa-user text-sm"></i>';
                div.appendChild(userAvatar);
            }

            chatBox.appendChild(div);
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        chatSend.addEventListener('click', ()=>{
            const prompt = chatInput.value.trim();
            if(!prompt) return;
            appendMessage('user', prompt);
            chatInput.value='';

            fetch('{{ route("chat.ai") }}',{
                method:'POST',
                headers:{
                    'Content-Type':'application/json',
                    'X-CSRF-TOKEN':'{{ csrf_token() }}'
                },
                body: JSON.stringify({prompt})
            })
            .then(res=>res.json())
            .then(data=>{
                if(data.answer) appendMessage('ai', data.answer);
                if(data.products) data.products.forEach(p=>appendMessage('ai', p));
            })
            .catch(err=>{
                appendMessage('ai','Lỗi kết nối server.');
                console.error(err);
            });
        });

        chatInput.addEventListener('keydown', e=>{
            if(e.key==='Enter'){
                e.preventDefault();
                chatSend.click();
            }
        });
    </script>
</x-layout-site>
