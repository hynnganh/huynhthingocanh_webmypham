<x-layout-site>
    <x-slot:title>
        Thông tin tài khoản
    </x-slot:title>

    <main class="p-4 md:p-8 max-w-5xl mx-auto mt-4 md:mt-10 mb-8">

        <h1 class="text-4xl font-bold text-center text-pink-700 mb-8 tracking-wider">
            <i class="fas fa-user-circle mr-3"></i> TÀI KHOẢN CỦA TÔI
        </h1>

        <div class="bg-white shadow-2xl rounded-2xl border border-pink-200 p-6 md:p-8 mb-8 transform hover:shadow-pink-300/50 transition duration-300">
            <h2 class="text-3xl font-bold text-pink-600 mb-6 border-b-2 border-pink-300 pb-3 flex items-center">
                Thông tin cá nhân
            </h2>

            <div class="flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-8">
                <div class="flex-shrink-0 relative group">
                    <img src="{{ asset('storage/user/' . $user->avatar) }}" 
                        alt="Avatar" 
                        class="w-32 h-32 rounded-full object-cover border-4 border-pink-400 shadow-lg group-hover:border-pink-600 transition duration-300 transform group-hover:scale-105"
                        id="user-main-avatar">

                </div>

                <div class="text-center md:text-left">
                    <p class="text-3xl font-extrabold text-gray-800 tracking-wide" id="user-main-name">{{ $user->name }}</p>
                    <p class="text-lg text-gray-500 mt-1">
                        <i class="fas fa-at mr-1 text-pink-400"></i> {{ $user->username }}
                    </p>
                    <button id="open-edit-modal" class="inline-block mt-3 text-sm font-semibold text-white bg-pink-500 hover:bg-pink-600 px-4 py-2 rounded-full shadow-md transition duration-200">
                        <i class="fas fa-edit mr-1"></i> Chỉnh sửa hồ sơ
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8 border-t border-pink-100 pt-6">
                @php
                    $details = [
                        ['icon' => 'fas fa-envelope', 'title' => 'Email', 'value' => $user->email, 'id' => 'user-detail-email'],
                        ['icon' => 'fas fa-phone-alt', 'title' => 'Số điện thoại', 'value' => $user->phone, 'id' => 'user-detail-phone'],
                        ['icon' => 'fas fa-map-marker-alt', 'title' => 'Địa chỉ', 'value' => $user->address, 'id' => 'user-detail-address'],
                    ];
                @endphp

                @foreach ($details as $detail)
                    <div class="p-4 bg-pink-50 rounded-lg border border-pink-200 shadow-inner hover:shadow-md transition duration-200">
                        <p class="font-bold text-pink-600 text-sm flex items-center mb-1">
                            <i class="{{ $detail['icon'] }} mr-2"></i> {{ $detail['title'] }}
                        </p>
                        <p class="text-gray-700 font-medium break-words" id="{{ $detail['id'] }}">{{ $detail['value'] ?: 'Chưa cập nhật' }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="space-y-6">
            
            <div class="bg-white shadow-2xl rounded-2xl border border-pink-200">
                
                <button id="accordion-orders-header" class="w-full text-left p-6 md:p-8 flex justify-between items-center hover:bg-pink-50 transition duration-200 rounded-t-2xl focus:outline-none">
                    <h3 class="text-2xl font-bold text-pink-600 flex items-center">
                        <i class="fas fa-box-open mr-3"></i> Đơn hàng đã đặt ({{ $orders->count() }})
                    </h3>
                    <i id="accordion-orders-icon" class="fas fa-chevron-down text-pink-500 transition-transform duration-300"></i>
                </button>

                <div id="accordion-orders-content" class="overflow-hidden max-h-0 transition-max-height duration-500 ease-in-out">
                    <div class="p-6 md:p-8 border-t border-pink-200">
                        @if($orders->isEmpty())
                            <div class="text-center py-8 bg-gray-50 rounded-lg border border-gray-200">
                                <i class="fas fa-shopping-basket text-4xl text-gray-400 mb-3"></i>
                                <p class="text-gray-500 text-lg">Bạn chưa có đơn hàng nào. Hãy bắt đầu mua sắm!</p>
                                <a href="{{ route('site.home') }}" class="mt-4 inline-block text-white bg-pink-500 hover:bg-pink-600 px-6 py-2 rounded-full font-semibold transition duration-200">
                                    Khám phá sản phẩm
                                </a>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($orders as $order)
                                    @php
                                        $status_class = [
                                            1 => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                            2 => 'bg-blue-100 text-blue-800 border-blue-300',
                                            3 => 'bg-orange-100 text-orange-800 border-orange-300',
                                            4 => 'bg-green-100 text-green-800 border-green-300',
                                            5 => 'bg-teal-100 text-teal-800 border-teal-300',
                                            6 => 'bg-red-100 text-red-800 border-red-300',
                                            7 => 'bg-purple-100 text-purple-800 border-purple-300',
                                            8 => 'bg-indigo-100 text-indigo-800 border-indigo-300',
                                            9 => 'bg-gray-100 text-gray-800 border-gray-300',
                                            10 => 'bg-pink-100 text-pink-800 border-pink-300',
                                        ][$order->status] ?? 'bg-gray-100 text-gray-600 border-gray-300';

                                        $status_text = [
                                            1 => 'Chờ xác nhận', 2 => 'Đã xác nhận', 3 => 'Đang chuẩn bị hàng', 
                                            4 => 'Đang giao hàng', 5 => 'Giao thành công', 6 => 'Đã hủy', 
                                            7 => 'Hoàn trả', 8 => 'Đổi hàng', 9 => 'Từ chối', 10 => 'Khác',
                                        ][$order->status] ?? 'Chưa xác định';
                                    @endphp
                                    
                                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition duration-200 transform hover:translate-y-[-2px]">
                                        <div class="flex justify-between items-center border-b border-gray-100 pb-2 mb-2">
                                            <span class="font-bold text-gray-700">Mã đơn: #{{ $order->id }}</span>
                                            <span class="text-sm font-medium {{ $status_class }} px-3 py-1 rounded-full border">
                                                {{ $status_text }}
                                            </span>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2 text-sm text-gray-600">
                                            <div><i class="fas fa-calendar-alt mr-2 text-pink-500"></i> Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</div>
                                            <div><i class="fas fa-credit-card mr-2 text-pink-500"></i> Phương thức: <span class="font-semibold">{{ strtoupper($order->payment_method) }}</span></div>
                                        </div>
                                        <div class="text-right mt-3 pt-2 border-t border-gray-100">
                                            <a href="{{ route('account.order.detail', $order->id) }}" class="text-pink-600 hover:text-pink-800 font-semibold transition duration-200">
                                                Xem chi tiết <i class="fas fa-chevron-right ml-1 text-xs"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-2xl rounded-2xl border border-pink-200">
                
                <button id="accordion-reviews-header" class="w-full text-left p-6 md:p-8 flex justify-between items-center hover:bg-pink-50 transition duration-200 rounded-t-2xl focus:outline-none">
                    <h3 class="text-2xl font-bold text-pink-600 flex items-center">
                        <i class="fas fa-star mr-3"></i> Sản phẩm đã đánh giá ({{ $user->reviews->count() }})
                    </h3>
                    <i id="accordion-reviews-icon" class="fas fa-chevron-down text-pink-500 transition-transform duration-300"></i>
                </button>

                <div id="accordion-reviews-content" class="overflow-hidden max-h-0 transition-max-height duration-500 ease-in-out">
                    <div class="p-6 md:p-8 border-t border-pink-200">
                        @if($user->reviews->isEmpty())
                            <div class="text-center py-8 bg-gray-50 rounded-lg border border-gray-200">
                                <i class="fas fa-comments text-4xl text-gray-400 mb-3"></i>
                                <p class="text-gray-500 text-lg">Bạn chưa đánh giá sản phẩm nào. Hãy chia sẻ ý kiến của bạn!</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($user->reviews as $review)
                                    <div class="bg-white border border-pink-100 rounded-lg p-4 shadow-md hover:shadow-lg transition duration-200">
                                        <div class="flex justify-between items-start mb-2">
                                            <a href="#" class="text-lg font-semibold text-gray-800 hover:text-pink-600 transition duration-200 line-clamp-1">
                                                {{ $review->product->name ?? 'Sản phẩm đã bị xóa' }}
                                            </a>
                                            <span class="text-sm text-gray-500 flex-shrink-0 ml-4">
                                                <i class="fas fa-calendar-alt mr-1"></i> {{ $review->created_at->format('d/m/Y') }}
                                            </span>
                                        </div>

                                        <div class="flex items-center mb-2">
                                            <span class="text-xl font-bold text-yellow-500 mr-2">
                                                @for ($i = 0; $i < $review->rating; $i++)
                                                    ★
                                                @endfor
                                                @for ($i = $review->rating; $i < 5; $i++)
                                                    <span class="text-gray-300">★</span>
                                                @endfor
                                            </span>
                                            <span class="text-pink-600 font-bold">({{ $review->rating }}/5)</span>
                                        </div>

                                        <p class="text-gray-700 italic border-l-4 border-pink-300 pl-3 py-1 bg-pink-50 rounded-r-md">
                                            "{{ $review->comment }}"
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div id="edit-profile-modal" class="fixed inset-0 z-[300] hidden items-center justify-center bg-black bg-opacity-60 transition-opacity duration-300">
        <div id="modal-form-content" class="bg-white rounded-xl shadow-2xl p-6 md:p-8 w-full max-w-lg transform transition-all duration-300 scale-95 opacity-0">
            <h3 class="text-2xl font-bold text-pink-600 mb-6 border-b border-pink-200 pb-3 flex items-center justify-between">
                Chỉnh sửa thông tin hồ sơ
                <button onclick="hideEditModal()" class="text-gray-400 hover:text-red-500 transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </h3>

            <form id="profile-edit-form" action="{{ route('account.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <input type="hidden" name="user_id" value="{{ $user->id }}">

                <div class="space-y-4">
                    <div class="flex flex-col items-center mb-4">
                        <label for="avatar_input" class="cursor-pointer">
                            <img id="current-avatar-modal" 
                                src="{{ asset('assets/images/user/' . $user->avatar) }}" 
                                alt="Current Avatar" 
                                class="w-24 h-24 rounded-full object-cover border-3 border-pink-400 shadow-md hover:border-pink-600 transition duration-300">
                            <span class="block text-center text-sm text-pink-500 mt-2 font-medium hover:underline">Thay đổi ảnh</span>
                        </label>
                        <input type="file" name="avatar" id="avatar_input" class="hidden" accept="image/*">
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Họ và Tên</label>
                        <input type="text" name="name" id="name_input" value="{{ $user->name }}" 
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500 transition duration-200" required>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="email_input" value="{{ $user->email }}" 
                            class="w-full p-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-500" readonly>
                        <p class="text-xs text-gray-500 mt-1">Email không thể thay đổi.</p>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại</label>
                        <input type="tel" name="phone" id="phone_input" value="{{ $user->phone }}" 
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500 transition duration-200">
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ</label>
                        <textarea name="address" id="address_input" rows="2" 
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500 transition duration-200">{{ $user->address }}</textarea>
                    </div>

                    <div class="pt-4 flex justify-end">
                        <button type="submit" class="px-6 py-2 text-white bg-pink-600 rounded-lg font-semibold hover:bg-pink-700 transition duration-200 shadow-lg shadow-pink-200">
                            <i class="fas fa-save mr-2"></i> Lưu thay đổi
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="toast-notification" class="fixed top-5 right-5 z-[500] p-4 rounded-lg shadow-xl text-white transition-all duration-300 transform translate-x-full opacity-0">
        <div class="flex items-center">
            <i id="toast-icon" class="fas mr-2"></i>
            <span id="toast-message" class="font-semibold"></span>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <style>
        #accordion-orders-content, #accordion-reviews-content {
            max-height: 0;
            transition: max-height 0.5s ease-in-out;
        }

        .rotate-180 {
            transform: rotate(180deg);
        }
        
        #edit-profile-modal {
            opacity: 0;
            z-index: 300;
        }
        #edit-profile-modal #modal-form-content {
            opacity: 0;
            transform: scale(0.95);
        }
        #edit-profile-modal.show {
            display: flex;
            opacity: 1;
        }
        #edit-profile-modal.show #modal-form-content {
            opacity: 1;
            transform: scale(1);
        }

        #toast-notification.show {
            transform: translateX(0);
            opacity: 1;
        }
    </style>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const orderHeader = document.getElementById('accordion-orders-header');
            const orderContent = document.getElementById('accordion-orders-content');
            const orderIcon = document.getElementById('accordion-orders-icon');

            const reviewHeader = document.getElementById('accordion-reviews-header');
            const reviewContent = document.getElementById('accordion-reviews-content');
            const reviewIcon = document.getElementById('accordion-reviews-icon');

            const editModal = document.getElementById('edit-profile-modal');
            const openEditButton = document.getElementById('open-edit-modal');
            
            const avatarInput = document.getElementById('avatar_input');
            const currentAvatarMain = document.getElementById('user-main-avatar');
            const currentAvatarModal = document.getElementById('current-avatar-modal');
            const profileEditForm = document.getElementById('profile-edit-form');

            function toggleAccordion(header, content, icon) {
                const isExpanded = content.classList.contains('is-expanded');
                
                if (isExpanded) {
                    content.style.maxHeight = '0';
                    content.classList.remove('is-expanded');
                    icon.classList.remove('rotate-180');
                } else {
                    content.style.maxHeight = content.scrollHeight + 'px';
                    content.classList.add('is-expanded');
                    icon.classList.add('rotate-180');
                }
            }

            orderHeader.addEventListener('click', () => {
                toggleAccordion(orderHeader, orderContent, orderIcon);
            });

            reviewHeader.addEventListener('click', () => {
                toggleAccordion(reviewHeader, reviewContent, reviewIcon);
            });

            new ResizeObserver(() => {
                if (orderContent.classList.contains('is-expanded')) {
                    orderContent.style.maxHeight = orderContent.scrollHeight + 'px';
                }
                if (reviewContent.classList.contains('is-expanded')) {
                    reviewContent.style.maxHeight = reviewContent.scrollHeight + 'px';
                }
            }).observe(document.body);

            function showEditModal() {
                editModal.classList.remove('hidden');
                editModal.classList.add('flex', 'show');
                document.getElementById('name_input').focus();
            }

            window.hideEditModal = function() {
                editModal.classList.remove('flex', 'show');
                editModal.classList.add('hidden');
            }
            
            openEditButton.addEventListener('click', function(e) {
                e.preventDefault();
                showEditModal();
            });
            
            if (avatarInput) {
                avatarInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            currentAvatarModal.src = e.target.result; 
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }


           profileEditForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('HTTP status ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            document.getElementById('user-main-name').textContent = data.user.name;
            document.getElementById('user-detail-phone').textContent = data.user.phone || 'Chưa cập nhật';
            document.getElementById('user-detail-address').textContent = data.user.address || 'Chưa cập nhật';
            
            if (data.avatar_path) {
                const newAvatarUrl = data.avatar_url;
                currentAvatarMain.src = newAvatarUrl;
                currentAvatarModal.src = newAvatarUrl;
            }

            hideEditModal();
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            console.warn('Cập nhật thất bại:', data.message || 'Không rõ lỗi');
        }
    })
    .catch(error => {
        console.error('Lỗi kết nối server:', error);
    });
});

        });
    </script>
</x-layout-site>