<x-layout-site>
    <x-slot:title>
        Thông tin tài khoản
    </x-slot:title>
    <main class="p-4 md:p-8 max-w-5xl mx-auto mt-4 md:mt-10 mb-8">

        {{-- Phần hiển thị thông tin chính (Giữ nguyên) --}}
        <h1 class="text-4xl font-bold text-center text-pink-700 mb-8 tracking-wider">
            <i class="fas fa-user-circle mr-3"></i> TÀI KHOẢN CỦA TÔI
        </h1>
        <div class="bg-white shadow-2xl rounded-2xl border border-pink-200 p-6 md:p-8 mb-8 transform hover:shadow-pink-300/50 transition duration-300">
            <h2 class="text-3xl font-bold text-pink-600 mb-6 border-b-2 border-pink-300 pb-3 flex items-center">
                Thông tin cá nhân
            </h2>
            <div class="flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-8">
                <div class="flex-shrink-0 relative group">
                    <img src="{{ asset('assets/images/user/' . $user->avatar) }}" 
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
        
{{-- PHẦN ĐƠN HÀNG DÙNG TAB --}}
<div class="bg-white shadow-2xl rounded-2xl border border-pink-200 p-6 md:p-8 mb-8">
    <h2 class="text-3xl font-bold text-pink-600 mb-6 border-b-2 border-pink-300 pb-3 flex items-center">
        <i class="fas fa-box-open mr-3"></i> Quản lý Đơn hàng
    </h2>

    @php
        $status_map = [
            1 => ['label' => 'Chờ xác nhận', 'icon' => 'fas fa-clock', 'class' => 'bg-yellow-100 text-yellow-800 border-yellow-300'],
            2 => ['label' => 'Đang chuẩn bị', 'icon' => 'fas fa-box', 'class' => 'bg-orange-100 text-orange-800 border-orange-300'],
            3 => ['label' => 'Đang giao hàng', 'icon' => 'fas fa-truck', 'class' => 'bg-indigo-100 text-indigo-800 border-indigo-300'],
            4 => ['label' => 'Đã hoàn thành', 'icon' => 'fas fa-check-circle', 'class' => 'bg-green-100 text-green-800 border-green-300'],
            5 => ['label' => 'Đã hủy', 'icon' => 'fas fa-times-circle', 'class' => 'bg-red-100 text-red-800 border-red-300'],
            6 => ['label' => 'Đã trả hàng', 'icon' => 'fas fa-undo', 'class' => 'bg-purple-100 text-purple-800 border-purple-300'],
        ];

        if(!isset($orders) || !$orders instanceof \Illuminate\Support\Collection){
            $orders = collect([]);
        }

        $grouped_orders = [];
        $grouped_orders['all'] = $orders;
        foreach ($status_map as $status_id => $info){
            $grouped_orders[$status_id] = $orders->filter(fn($o) => $o->status == $status_id);
        }
        $grouped_orders['to_review'] = $orders->filter(fn($o) => $o->status == 4 && !$o->reviewed);

        $tabs = [
            'all' => ['label' => 'Tất cả', 'icon' => 'fas fa-list-alt'],
            1 => ['label' => 'Chờ xác nhận', 'icon' => 'fas fa-clock'],
            2 => ['label' => 'Đang chuẩn bị', 'icon' => 'fas fa-box'],
            3 => ['label' => 'Đang giao hàng', 'icon' => 'fas fa-truck'],
            4 => ['label' => 'Đã hoàn thành', 'icon' => 'fas fa-check-circle'],
            'to_review' => ['label' => 'Cần đánh giá', 'icon' => 'fas fa-star'],
            5 => ['label' => 'Đã hủy', 'icon' => 'fas fa-times-circle'],
            6 => ['label' => 'Đã trả hàng', 'icon' => 'fas fa-undo'],
        ];
        $tab_keys = array_keys($tabs);
    @endphp

    {{-- Tab Header --}}
    <div class="flex border-b border-pink-200 whitespace-nowrap mb-6 hide-scrollbar">
        @foreach ($tabs as $key => $tab)
            <button data-tab="{{ $key }}" 
                class="tab-btn py-3 px-4 text-sm font-semibold border-b-2 transition duration-200 
                    {{ $key === 'all' ? 'text-pink-600 border-pink-600' : 'text-gray-500 border-transparent hover:text-pink-500 hover:border-pink-300' }}">
                <i class="{{ $tab['icon'] }} mr-2"></i> 
                {{ $tab['label'] }} 
                ({{ $grouped_orders[$key]->count() ?? 0 }})
            </button>
        @endforeach
    </div>

    {{-- Tab Content --}}
    <div id="tab-content-container">
        @foreach ($tab_keys as $key)
            @php
                $current_orders = $grouped_orders[$key] ?? collect([]);
                $tab_info = $tabs[$key];
            @endphp

            <div id="content-{{ $key }}" 
                class="tab-content {{ $key === 'all' ? '' : 'hidden' }} space-y-4 transition-opacity duration-300">
                
                @if ($current_orders->isEmpty())
                    <div class="text-center py-8 bg-gray-50 rounded-lg border border-gray-200">
                        <i class="{{ $tab_info['icon'] }} text-4xl text-gray-400 mb-3"></i>
                        <p class="text-gray-500 text-lg">
                            @if($key === 'to_review')
                                Không có đơn hàng nào cần đánh giá.
                            @elseif($key === 'all')
                                Bạn chưa có đơn hàng nào.
                            @else
                                Không có đơn hàng ở trạng thái {{ $tab_info['label'] }}.
                            @endif
                        </p>
                        @if(in_array($key, ['all', 1]))
                            <a href="{{ route('site.home') }}" class="mt-4 inline-block text-white bg-pink-500 hover:bg-pink-600 px-6 py-2 rounded-full font-semibold transition duration-200">
                                Tiếp tục mua sắm
                            </a>
                        @endif
                    </div>
                @else
                    @foreach($current_orders as $order)
                        @php
                            $status_info = $status_map[$order->status] ?? ['label' => 'Chưa xác định', 'class' => 'bg-gray-100 text-gray-600 border-gray-300'];
                        @endphp

                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition duration-200 transform hover:translate-y-[-2px]">
                            <div class="flex justify-between items-center border-b border-gray-100 pb-2 mb-2">
                                <span class="font-bold text-gray-700">Mã đơn: #{{ $order->id }}</span>
                                <span class="text-sm font-medium {{ $status_info['class'] }} px-3 py-1 rounded-full border">
                                    {{ $status_info['label'] }}
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-2 text-sm text-gray-600">
                                <div><i class="fas fa-calendar-alt mr-2 text-pink-500"></i> Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</div>
                                <div><i class="fas fa-credit-card mr-2 text-pink-500"></i> Phương thức: <span class="font-semibold">{{ strtoupper($order->payment_method) }}</span></div>
                            </div>

                            <div class="text-right mt-3 pt-2 border-t border-gray-100 flex justify-end items-center space-x-3">
                                @if($key === 'to_review')
                                    @if(!$order->reviewed)
                                        <a href="{{ route('order.review', $order->id) }}" 
                                           class="inline-flex items-center px-2 py-1 border border-transparent text-base font-medium rounded-full shadow-lg text-white bg-pink-500 hover:bg-pink-600 transition duration-200">
                                            <i class="fas fa-star mr-2"></i> Đánh giá
                                        </a>
                                    @else
                                        <a href="{{ route('order.review.view', $order->id) }}" 
                                           class="inline-flex items-center px-2 py-1 border border-transparent text-base font-medium rounded-full shadow-lg text-white bg-green-500 hover:bg-green-600 transition duration-200">
                                            <i class="fas fa-eye mr-2"></i> Xem đánh giá
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('account.order.detail', $order->id) }}" 
                                       class="inline-flex items-center px-2 py-1 border border-transparent text-base font-medium rounded-full shadow-lg text-white bg-pink-500 hover:bg-pink-600 transition duration-200">
                                        <i class="fas fa-info-circle mr-2"></i> Chi tiết
                                    </a>

                                    @if($order->status == 1)
                                        <button onclick="showCancelModal({{ $order->id }})"
                                                class="inline-flex items-center px-2 py-1 border border-transparent text-base font-medium rounded-full shadow-lg text-white bg-red-500 hover:bg-red-600 transition duration-200">
                                            <i class="fas fa-times-circle mr-2"></i> Hủy đơn
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        @endforeach
    </div>
</div>

{{-- 🔹 Modal Hủy đơn (nổi giữa màn hình) --}}
@foreach($orders as $order)
<div id="cancel-modal-{{ $order->id }}" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6 relative transform scale-95 opacity-0 transition-all duration-300">
        <button onclick="hideCancelModal({{ $order->id }})"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-800">
            <i class="fas fa-times"></i>
        </button>
        <h3 class="text-xl font-bold text-red-600 mb-2">Lý do hủy đơn #{{ $order->id }}</h3>
        <form action="{{ route('account.order.cancel', $order->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-2">
                <textarea name="cancel_note" rows="2" required
                          class="w-full p-3 border rounded-lg focus:ring-red-500 focus:border-red-500"
                          placeholder="Nhập lý do hủy đơn..."></textarea>
            </div>
            <div class="flex justify-end space-x-1">
                <button type="button" onclick="hideCancelModal({{ $order->id }})"
                        class="px-2 py-1 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition">
                    Hủy
                </button>
                <button type="submit"
                        class="px-2 py-1 rounded-lg bg-red-500 text-white hover:bg-red-600 transition">
                    Xác nhận hủy
                </button>
            </div>
        </form>
    </div>
</div>
@endforeach

{{-- 🔹 CSS --}}
<style>
    .tab-content { transition: opacity 0.3s ease; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; overflow-x: auto; }
    .hide-scrollbar::-webkit-scrollbar { display: none; }
</style>

{{-- 🔹 JS --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Tab ---
        const tabButtons = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');
        function switchTab(targetTabKey) {
            tabButtons.forEach(btn => {
                const btnKey = btn.getAttribute('data-tab');
                btn.classList.remove('text-pink-600', 'border-pink-600', 'text-green-600', 'border-green-600');
                btn.classList.add('text-gray-500', 'border-transparent', 'hover:text-pink-500', 'hover:border-pink-300');
                if (btnKey === targetTabKey) {
                    if (targetTabKey === 'to_review') {
                        btn.classList.add('text-green-600', 'border-green-600');
                        btn.classList.remove('text-gray-500', 'hover:text-pink-500', 'hover:border-pink-300');
                    } else {
                        btn.classList.add('text-pink-600', 'border-pink-600');
                        btn.classList.remove('text-gray-500', 'hover:text-pink-500', 'hover:border-pink-300');
                    }
                }
            });
            tabContents.forEach(content => {
                const contentKey = content.id.replace('content-', '');
                if (contentKey === targetTabKey) {
                    content.classList.remove('hidden'); content.classList.add('opacity-100');
                } else {
                    content.classList.add('hidden'); content.classList.remove('opacity-100');
                }
            });
        }
        tabButtons.forEach(btn => btn.addEventListener('click', () => switchTab(btn.getAttribute('data-tab'))));
        switchTab('all');

        // --- Modal hủy đơn ---
        window.showCancelModal = function(orderId) {
            const modal = document.getElementById('cancel-modal-' + orderId);
            if(modal) {
                modal.classList.remove('hidden');
                const content = modal.querySelector('div');
                setTimeout(() => {
                    content.classList.add('opacity-100', 'scale-100');
                }, 10);
            }
        }
        window.hideCancelModal = function(orderId) {
            const modal = document.getElementById('cancel-modal-' + orderId);
            if(modal) {
                const content = modal.querySelector('div');
                content.classList.remove('opacity-100', 'scale-100');
                setTimeout(() => modal.classList.add('hidden'), 200);
            }
        }
    });
</script>


        
    </main>

    {{-- Modal Chỉnh sửa hồ sơ (Giữ nguyên) --}}
    <div id="edit-profile-modal" class="fixed inset-0 z-[300] hidden items-center justify-center bg-black bg-opacity-60 transition-opacity duration-300">
        <div id="modal-form-content" class="bg-white rounded-xl shadow-2xl p-6 md:p-8 w-full max-w-lg transform transition-all duration-300 scale-95 opacity-0 max-h-[90vh] overflow-y-auto">
            <h3 class="text-2xl font-bold text-pink-600 mb-6 border-b border-pink-200 pb-3 flex items-center justify-between sticky top-0 bg-white z-10">
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
                        @error('avatar')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Họ và Tên</label>
                        <input type="text" name="name" id="name_input" value="{{ old('name', $user->name) }}" 
                            class="w-full p-3 border rounded-lg focus:ring-pink-500 focus:border-pink-500 transition duration-200 
                                {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }}" required>
                        @error('name')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Tên đăng nhập</label>
                        <input type="text" name="username" id="username_input" value="{{ old('username', $user->username) }}" 
                            class="w-full p-3 border rounded-lg focus:ring-pink-500 focus:border-pink-500 transition duration-200 
                                {{ $errors->has('username') ? 'border-red-500' : 'border-gray-300' }}" required>
                        @error('username')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="email_input" value="{{ $user->email }}" 
                            class="w-full p-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-500" readonly>
                        <p class="text-xs text-gray-500 mt-1">Email không thể thay đổi.</p>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại</label>
                        <input type="tel" name="phone" id="phone_input" value="{{ old('phone', $user->phone) }}" 
                            class="w-full p-3 border rounded-lg focus:ring-pink-500 focus:border-pink-500 transition duration-200
                                {{ $errors->has('phone') ? 'border-red-500' : 'border-gray-300' }}">
                        @error('phone')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ</label>
                        <textarea name="address" id="address_input" rows="2" 
                            class="w-full p-3 border rounded-lg focus:ring-pink-500 focus:border-pink-500 transition duration-200
                                {{ $errors->has('address') ? 'border-red-500' : 'border-gray-300' }}">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <h4 class="text-lg font-bold text-pink-500 pt-4 border-t border-pink-100 mt-6">Đổi mật khẩu (Không bắt buộc)</h4>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu mới</label>
                        <input type="password" name="password" id="password_input"
                            class="w-full p-3 border rounded-lg focus:ring-pink-500 focus:border-pink-500 transition duration-200
                                {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }}" placeholder="Để trống nếu không đổi">
                        @error('password')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Xác nhận mật khẩu mới</label>
                        <input type="password" name="password_confirmation" id="password_confirmation_input"
                            class="w-full p-3 border rounded-lg focus:ring-pink-500 focus:border-pink-500 transition duration-200
                                {{ $errors->has('password_confirmation') ? 'border-red-500' : 'border-gray-300' }}" placeholder="Nhập lại mật khẩu mới">
                        @error('password_confirmation')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4 flex justify-end">
                        <button type="submit" id="save-changes-btn" class="px-6 py-2 text-white bg-pink-600 rounded-lg font-semibold hover:bg-pink-700 transition duration-200 shadow-lg shadow-pink-200">
                            <i class="fas fa-save mr-2"></i> Lưu thay đổi
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <style>
        /* CSS ẨN SCROLLBAR cho tab header */
        .hide-scrollbar {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
            overflow-x: auto; /* Giữ chức năng cuộn ngang */
        }
        .hide-scrollbar::-webkit-scrollbar {
            display: none; /* Chrome, Safari and Opera */
        }
        
        /* CSS cho Loading spinner (Giữ nguyên) */
        .spinner {
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid #fff; 
            border-radius: 50%;
            width: 1.25rem; 
            height: 1.25rem; 
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* CSS cho Modal (Giữ nguyên) */
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
    </style>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- Tab Logic (Đơn hàng) ---
            const tabButtons = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');
            
            function switchTab(targetTabKey) {
                tabButtons.forEach(btn => {
                    const btnKey = btn.getAttribute('data-tab');
                    
                    // Reset classes
                    btn.classList.remove('text-pink-600', 'border-pink-600', 'text-green-600', 'border-green-600');
                    btn.classList.add('text-gray-500', 'border-transparent', 'hover:text-pink-500', 'hover:border-pink-300');

                    // Apply active classes
                    if (btnKey === targetTabKey) {
                        if (targetTabKey === 'to_review') {
                            btn.classList.add('text-green-600', 'border-green-600');
                            btn.classList.remove('text-gray-500', 'hover:text-pink-500', 'hover:border-pink-300');
                        } else {
                            btn.classList.add('text-pink-600', 'border-pink-600');
                            btn.classList.remove('text-gray-500', 'hover:text-pink-500', 'hover:border-pink-300');
                        }
                    }
                });

                tabContents.forEach(content => {
                    const contentKey = content.id.replace('content-', '');
                    if (contentKey === targetTabKey) {
                        content.classList.remove('hidden');
                        content.classList.add('opacity-100');
                    } else {
                        content.classList.add('hidden');
                        content.classList.remove('opacity-100');
                    }
                });
            }

            tabButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const targetTab = btn.getAttribute('data-tab');
                    switchTab(targetTab);
                });
            });

            // Khởi tạo tab đầu tiên khi tải trang
            switchTab('all'); // Mặc định hiển thị tab Tất cả
            // ------------------------------------

            // --- Modal Logic & Auto-show on Validation Error (Giữ nguyên) ---
            const editModal = document.getElementById('edit-profile-modal');
            const openEditButton = document.getElementById('open-edit-modal');
            
            function showEditModal() {
                editModal.classList.remove('hidden');
                editModal.classList.add('flex', 'show');
            }

            window.hideEditModal = function() {
                editModal.classList.remove('flex', 'show');
                editModal.classList.add('hidden');
            }
            
            openEditButton.addEventListener('click', function(e) {
                e.preventDefault();
                showEditModal();
            });

            // Tự động mở Modal nếu có lỗi validation sau khi redirect
            @if ($errors->any() || session('show_edit_modal'))
                showEditModal();
            @endif
            // ------------------------------------
            
            // --- Avatar Preview Logic (Giữ nguyên) ---
            const avatarInput = document.getElementById('avatar_input');
            const currentAvatarModal = document.getElementById('current-avatar-modal');
            
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
            // ------------------------------------
            
            // --- Loading Effect cho Form Submit (Giữ nguyên) ---
            const profileEditForm = document.getElementById('profile-edit-form');
            const saveButton = document.getElementById('save-changes-btn');

            profileEditForm.addEventListener('submit', function() {
                // 1. Vô hiệu hóa nút
                saveButton.disabled = true;
                
                // 2. Thay đổi nội dung nút thành loading
                saveButton.innerHTML = `
                    <div class="flex items-center justify-center">
                        <div class="spinner mr-2"></div>
                        <span>Đang lưu...</span>
                    </div>
                `;
                
                // 3. Thay đổi màu sắc/kiểu dáng để nhấn mạnh trạng thái loading
                saveButton.classList.remove('bg-pink-600', 'hover:bg-pink-700', 'shadow-pink-200');
                saveButton.classList.add('bg-pink-400', 'cursor-wait');
            });
            // ----------------------------------------------------
        });
    </script>
</x-layout-site>