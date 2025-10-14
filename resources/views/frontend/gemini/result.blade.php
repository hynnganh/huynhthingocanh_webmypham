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
                                
                                @if(is_array($chat['content']))
                                    <div class="flex items-center bg-white p-3 rounded-xl shadow-inner border border-pink-100">
                                        @if($chat['content']['image'] ?? false)
                                            <img src="{{ $chat['content']['image'] }}" 
                                                class="rounded-lg w-16 h-16 object-cover mr-3 flex-shrink-0" 
                                                alt="{{ $chat['content']['name'] ?? '' }}">
                                        @endif
                                        <div class="flex-grow">
                                            <a href="{{ $chat['content']['detail_url'] ?? '#' }}" 
                                            class="text-gray-800 font-bold block hover:text-pink-600 transition duration-200 text-sm line-clamp-2">
                                            {{ $chat['content']['name'] ?? '' }}
                                            </a>
                                            <p class="text-xs text-red-500 font-semibold mt-1">
                                                {{ isset($chat['content']['price']) ? number_format($chat['content']['price'],0,',','.') : '' }} <sup>₫</sup>
                                            </p>
                                        </div>
                                    </div>
                                @else
                                    {!! nl2br(e($chat['content'])) !!}
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

            <div id="loading" class="hidden justify-start items-center space-x-2 mb-4 pl-10">
                <div class="w-8 h-8 rounded-full bg-pink-500 flex items-center justify-center text-white mr-2 flex-shrink-0">
                    <i class="fas fa-robot text-sm"></i>
                </div>
                <div class="bg-gray-100 text-gray-800 rounded-t-xl rounded-br-xl px-4 py-3 shadow-md border border-gray-200">
                    <div class="dot-flashing"></div>
                </div>
            </div>

            <div class="flex items-center mt-auto border-t pt-4">
                <input id="chat-input" type="text" placeholder="Hỏi AI về sản phẩm, khuyến mãi,..."
                        class="border border-gray-300 rounded-l-xl px-5 py-3 flex-1 focus:ring-2 focus:ring-pink-400 focus:border-pink-400 text-gray-700 transition duration-200">
                <button id="chat-send" class="bg-pink-600 text-white px-6 py-3 rounded-r-xl font-semibold hover:bg-pink-700 transition duration-200 shadow-lg shadow-pink-200">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>

            <div class="flex justify-end mt-2">
                <button id="chat-reset" class="text-sm text-gray-500 hover:text-red-500 transition duration-200 flex items-center">
                    <i class="fas fa-redo mr-1"></i> Xóa đoạn chat
                </button>
            </div>

        </div>
    </div>

    <div id="toast-notification" class="fixed bottom-5 right-5 z-[100] hidden items-center w-full max-w-xs p-4 space-x-4 text-gray-700 bg-white divide-x divide-gray-200 rounded-lg shadow-xl border" role="alert">
        <div class="inline-flex flex-shrink-0 justify-center items-center w-8 h-8 text-green-500 bg-green-100 rounded-full">
            <i class="fas fa-check"></i>
        </div>
        <div class="ml-3 text-sm font-normal" id="toast-message">Đã hoàn tất thao tác!</div>
        <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8" onclick="hideToast()">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div id="confirm-modal" class="fixed inset-0 z-[200] hidden items-center justify-center bg-black bg-opacity-50 transition-opacity duration-300">
        <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-sm transform transition-all duration-300 scale-95 opacity-0" id="modal-content">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i> Xác nhận Xóa
            </h3>
            <p class="text-gray-600 mb-6">Bạn có chắc chắn muốn xóa **toàn bộ** đoạn chat này không? Hành động này không thể hoàn tác.</p>
            <div class="flex justify-end space-x-3">
                <button id="modal-cancel" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition duration-150">
                    Hủy
                </button>
                <button id="modal-confirm" class="px-4 py-2 text-white bg-red-500 rounded-lg font-semibold hover:bg-red-600 transition duration-150">
                    Xóa ngay
                </button>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e9a8b8;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #ec4899;
        }

        .dot-flashing {
            position: relative;
            width: 8px;
            height: 8px;
            border-radius: 5px;
            background-color: #ec4899;
            color: #ec4899;
            animation: dotFlashing 1s infinite alternate;
            margin: 0 4px;
            display: inline-block;
        }

        .dot-flashing::before, .dot-flashing::after {
            content: "";
            display: inline-block;
            position: absolute;
            top: 0;
        }

        .dot-flashing::before {
            left: -12px;
            width: 8px;
            height: 8px;
            border-radius: 5px;
            background-color: #ec4899;
            color: #ec4899;
            animation: dotFlashing 1s infinite alternate;
            animation-delay: 0s;
        }

        .dot-flashing::after {
            left: 12px;
            width: 8px;
            height: 8px;
            border-radius: 5px;
            background-color: #ec4899;
            color: #ec4899;
            animation: dotFlashing 1s infinite alternate;
            animation-delay: 0.2s;
        }

        @keyframes dotFlashing {
            0% {
                background-color: #ec4899;
            }
            50%, 100% {
                background-color: #fce7f3;
            }
        }
        
        @keyframes slideUp {
            0% {
                transform: translateY(100%);
                opacity: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }
        .animate-slideUp {
            animation: slideUp 0.3s ease-out forwards;
        }

        .modal-show {
            opacity: 1 !important;
        }
        .modal-show #modal-content {
            opacity: 1 !important;
            transform: scale(1) !important;
        }
    </style>

    <script>
    const chatBox = document.getElementById('chat-box');
    const chatInput = document.getElementById('chat-input');
    const chatSend = document.getElementById('chat-send');
    const chatReset = document.getElementById('chat-reset');
    const loading = document.getElementById('loading');
    
    const toast = document.getElementById('toast-notification');
    const toastMessage = document.getElementById('toast-message');

    const confirmModal = document.getElementById('confirm-modal');
    const modalContent = document.getElementById('modal-content');
    const modalCancel = document.getElementById('modal-cancel');
    const modalConfirm = document.getElementById('modal-confirm');

    function showModal() {
        confirmModal.classList.remove('hidden');
        confirmModal.classList.add('flex', 'modal-show');
        
        setTimeout(() => {
            modalContent.classList.add('modal-show');
        }, 10);
    }

    function hideModal() {
        modalContent.classList.remove('modal-show');
        
        setTimeout(() => {
            confirmModal.classList.remove('flex', 'modal-show');
            confirmModal.classList.add('hidden');
        }, 300);
    }

    function showToast(message, type = 'success') {
        let bgColor = 'bg-green-100', iconColor = 'text-green-500', icon = 'fas fa-check', borderColor = 'border-green-200';
        if (type === 'error') {
            bgColor = 'bg-red-100'; iconColor = 'text-red-500'; icon = 'fas fa-exclamation-triangle'; borderColor = 'border-red-200';
        }
        
        toastMessage.textContent = message;
        toast.className = `fixed bottom-5 right-5 z-[100] items-center w-full max-w-xs p-4 space-x-4 text-gray-700 bg-white divide-x divide-gray-200 rounded-lg shadow-xl border ${borderColor} flex animate-slideUp`;
        
        const iconDiv = toast.querySelector('div:first-child');
        iconDiv.className = `inline-flex flex-shrink-0 justify-center items-center w-8 h-8 ${iconColor} ${bgColor} rounded-full`;
        iconDiv.innerHTML = `<i class="${icon}"></i>`;

        setTimeout(() => {
            hideToast();
        }, 3000);
    }
    
    function hideToast() {
        toast.classList.remove('flex', 'animate-slideUp');
        toast.classList.add('hidden');
    }

    chatBox.scrollTop = chatBox.scrollHeight;

    function appendMessage(role, content){
        const div = document.createElement('div');
        div.className = 'flex ' + (role==='user' ? 'justify-end':'justify-start');

        const wrapper = document.createElement('div');
        wrapper.className = 'max-w-xs sm:max-w-md lg:max-w-lg';

        const msg = document.createElement('div');
        msg.className = role==='user' 
            ? 'bg-pink-500 text-white rounded-t-xl rounded-bl-xl px-4 py-3 shadow-md transition duration-300 transform scale-y-0 origin-bottom'
            : 'bg-gray-100 text-gray-800 rounded-t-xl rounded-br-xl px-4 py-3 shadow-md border border-gray-200 transition duration-300 transform scale-y-0 origin-bottom';

        if(typeof content === 'string'){
            msg.innerHTML = content.replace(/\n/g, '<br>');
        } else {
            const priceFormatted = Number(content.price).toLocaleString('vi-VN', {style: 'currency', currency: 'VND'}).replace('₫', '<sup>₫</sup>');

            msg.innerHTML = `
                <div class="flex items-center bg-white p-3 rounded-xl shadow-inner border border-pink-100">
                    ${content.image ? `<img src="${content.image}" class="rounded-lg w-16 h-16 object-cover mr-3 flex-shrink-0" />` : ''}
                    <div class="flex-grow">
                        <a href="${content.detail_url}" class="text-gray-800 font-bold block hover:text-pink-600 transition duration-200 text-sm line-clamp-2">
                            ${content.name}
                        </a>
                        <p class="text-xs text-red-500 font-semibold mt-1">${priceFormatted}</p>
                    </div>
                </div>
            `;
        }

        if(role !== 'user'){
            const aiAvatar = document.createElement('div');
            aiAvatar.className = 'w-8 h-8 rounded-full bg-pink-500 flex items-center justify-center text-white mr-2 flex-shrink-0';
            aiAvatar.innerHTML = '<i class="fas fa-robot text-sm"></i>';
            div.appendChild(aiAvatar);
        }

        wrapper.appendChild(msg);
        div.appendChild(wrapper);

        if(role === 'user'){
            const userAvatar = document.createElement('div');
            userAvatar.className = 'w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white ml-2 flex-shrink-0';
            userAvatar.innerHTML = '<i class="fas fa-user text-sm"></i>';
            div.appendChild(userAvatar);
        }

        chatBox.appendChild(div);
        
        setTimeout(() => {
            msg.classList.remove('scale-y-0');
            msg.classList.add('scale-y-100');
            chatBox.scrollTop = chatBox.scrollHeight;
        }, 10);
    }

    function sendPrompt(){
        const prompt = chatInput.value.trim();
        if(!prompt) return;
        
        loading.classList.add('hidden'); 
        
        appendMessage('user', prompt);
        chatInput.value='';
        
        loading.classList.remove('hidden');
        chatBox.scrollTop = chatBox.scrollHeight;

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
                if(data.answer) {
                    appendMessage('ai', data.answer);
                } else {
                    appendMessage('ai', 'Đây là một số sản phẩm mình tìm được:');
                }
                data.products.forEach(p=>appendMessage('ai',p));
            } else if(data.answer){
                appendMessage('ai',data.answer);
            } else {
                appendMessage('ai','Mình xin lỗi, mình không tìm thấy thông tin phù hợp.');
            }
        })
        .catch(err=>{
            loading.classList.add('hidden');
            appendMessage('ai','Lỗi kết nối server. Vui lòng thử lại sau.');
            console.error(err);
        });
    }

    chatSend.addEventListener('click', sendPrompt);

    chatInput.addEventListener('keydown', (e)=>{
        if(e.key==='Enter'){
            e.preventDefault();
            sendPrompt();
        }
    });

    chatReset.addEventListener('click',()=>{
        showModal();
    });
    
    modalCancel.addEventListener('click', hideModal);

    modalConfirm.addEventListener('click', () => {
        hideModal();
        
        fetch('{{ route("chat.ai.reset") }}')
            .then(()=>{
                chatBox.innerHTML='';
                appendMessage('ai','Xin chào 👋, mình có thể giúp gì cho bạn hôm nay?');
                showToast('Đã xóa lịch sử chat!', 'success'); 
            })
            .catch(err => {
                console.error(err);
                showToast('Lỗi: Không thể xóa lịch sử chat trên server.', 'error');
            });
    });
    
    if(chatBox.children.length === 0){
        appendMessage('ai', 'Xin chào 👋, mình có thể giúp gì cho bạn hôm nay?');
    }
    
    </script>
</x-layout-site>