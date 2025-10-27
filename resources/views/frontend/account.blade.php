<x-layout-site>
    <x-slot:title>Thông tin tài khoản</x-slot:title>

    <main class="p-4 md:p-8 max-w-5xl mx-auto mt-4 md:mt-10 mb-8">
        <h1 class="text-4xl font-bold text-center text-pink-700 mb-8 tracking-wider">
            <i class="fas fa-user-circle mr-3"></i> TÀI KHOẢN CỦA TÔI
        </h1>

        {{-- 🧍 Thông tin cá nhân --}}
        <div class="bg-white shadow-2xl rounded-2xl border border-pink-200 p-6 md:p-8 mb-8">
            <h2 class="text-3xl font-bold text-pink-600 mb-6 border-b-2 border-pink-300 pb-3 flex items-center justify-between">
                Thông tin cá nhân
                <button type="button" id="toggleEditBtn"
                    class="text-sm font-semibold text-pink-500 hover:text-pink-700 transition duration-200 bg-pink-100 px-3 py-1 rounded-lg">
                    <span id="toggleEditText">Chỉnh sửa</span> <i class="fas fa-edit ml-1"></i>
                </button>
            </h2>

            <form id="profile-edit-form" action="{{ route('account.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-8">
                    {{-- Avatar --}}
                    <div class="relative group">
                        <label for="avatar_input" class="cursor-pointer">
                            <img id="user-main-avatar"
                                src="{{ filter_var($user->avatar, FILTER_VALIDATE_URL)
                                    ? $user->avatar
                                    : asset('assets/images/user/' . ($user->avatar ?? 'default.png')) }}"
                                alt="Avatar"
                                class="w-32 h-32 rounded-full object-cover border-4 border-pink-400 shadow-lg hover:border-pink-600 transition duration-300 transform hover:scale-105">
                        </label>
                        {{-- input file chỉ hiện khi đang ở chế độ chỉnh sửa --}}
                        <input type="file" name="avatar" id="avatar_input" class="hidden" accept="image/*">
                    </div>

                    <div class="flex-1 w-full">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            {{-- Họ và tên --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Họ và tên</label>
                                <span id="view-name" class="view-mode-field text-gray-900 font-medium block h-10 pt-2">{{ $user->name }}</span>
                                <input type="text" name="name" value="{{ $user->name }}"
                                    class="input-edit-mode hidden w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-pink-400 focus:border-pink-400 transition">
                            </div>

                            {{-- Email (Luôn là trường input chỉ đọc) --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                                <input type="email" name="email" value="{{ $user->email }}" readonly
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-100 text-gray-500">
                            </div>

                            {{-- Số điện thoại --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Số điện thoại</label>
                                <span id="view-phone" class="view-mode-field text-gray-900 font-medium block h-10 pt-2">{{ $user->phone ?? 'Chưa cập nhật' }}</span>
                                <input type="text" name="phone" value="{{ $user->phone }}"
                                    class="input-edit-mode hidden w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-pink-400 focus:border-pink-400 transition">
                            </div>

                            {{-- Địa chỉ --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Địa chỉ</label>
                                <span id="view-address" class="view-mode-field text-gray-900 font-medium block h-10 pt-2">{{ $user->address ?? 'Chưa cập nhật' }}</span>
                                <input type="text" name="address" value="{{ $user->address }}"
                                    class="input-edit-mode hidden w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-pink-400 focus:border-pink-400 transition">
                            </div>
                        </div>

                        {{-- Nút Lưu chỉ hiện khi đang ở chế độ chỉnh sửa --}}
                        <div class="mt-6 text-right edit-mode-controls hidden">
                            <button id="saveBtn" type="submit"
                                class="flex items-center justify-center gap-2 bg-gradient-to-r from-pink-500 to-pink-600 text-white font-semibold py-2 px-6 rounded-lg shadow hover:from-pink-600 hover:to-pink-700 transition">
                                <svg id="loadingIcon" class="hidden w-5 h-5 animate-spin text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                                <span id="saveText">Lưu thay đổi</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- 💬 Đơn hàng --}}
        <div class="bg-white shadow-2xl rounded-2xl border border-pink-200 p-6 md:p-8">
            <h2 class="text-3xl font-bold text-pink-600 mb-6 border-b-2 border-pink-300 pb-3 flex items-center">
                <i class="fas fa-box-open mr-3"></i> Đơn hàng của bạn ({{ $orders->count() }})
            </h2>

            @if($orders->isEmpty())
                <div class="text-center py-8 bg-gray-50 rounded-lg border border-gray-200">
                    <i class="fas fa-shopping-basket text-4xl text-gray-400 mb-3"></i>
                    <p class="text-gray-500 text-lg">Bạn chưa có đơn hàng nào.</p>
                    <a href="{{ route('site.home') }}"
                        class="mt-4 inline-block text-white bg-pink-500 hover:bg-pink-600 px-6 py-2 rounded-full font-semibold transition duration-200">
                        Mua sắm ngay
                    </a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($orders as $order)
                        @php
                            $statusText = [
                                1 => 'Chờ xác nhận', 2 => 'Đã xác nhận', 3 => 'Đang chuẩn bị hàng',
                                4 => 'Đang giao hàng', 5 => 'Hoàn tất'
                            ][$order->status] ?? 'Không xác định';
                        @endphp
                        <div class="border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition duration-200">
                            <div class="flex justify-between items-center mb-2">
                                <p class="font-bold text-gray-800">Mã đơn: #{{ $order->id }}</p>
                                <span class="px-3 py-1 text-sm bg-pink-100 text-pink-700 rounded-full font-medium">
                                    {{ $statusText }}
                                </span>
                            </div>
                            <div class="text-sm text-gray-600">
                                <i class="fas fa-calendar-alt mr-2 text-pink-500"></i>
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </div>
                            <div class="text-right mt-3">
                                <a href="{{ route('account.order.detail', $order->id) }}"
                                    class="text-pink-600 hover:text-pink-800 font-semibold transition">
                                    Xem chi tiết →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </main>

    {{-- ✅ Toast thông báo --}}
    <div id="toast-notification"
        class="fixed top-5 right-5 z-[999] p-4 rounded-lg shadow-xl text-white bg-green-500 transition-all duration-300 transform translate-x-full opacity-0">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <span id="toast-message" class="font-semibold">Cập nhật thành công!</span>
        </div>
    </div>

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById("profile-edit-form");
            const saveBtn = document.getElementById("saveBtn");
            const loadingIcon = document.getElementById("loadingIcon");
            const saveText = document.getElementById("saveText");
            const toast = document.getElementById("toast-notification");

            const toggleEditBtn = document.getElementById("toggleEditBtn");
            const toggleEditText = document.getElementById("toggleEditText");
            const editModeInputs = document.querySelectorAll('.input-edit-mode');
            const viewModeSpans = document.querySelectorAll('.view-mode-field'); // Dùng class để nhóm các span view
            const saveControls = document.querySelector('.edit-mode-controls');
            const avatarInput = document.getElementById("avatar_input");
            const avatarImg = document.getElementById("user-main-avatar");

            let isEditing = false;

            // --- Chức năng Chuyển đổi View/Edit Mode ---
            const toggleEditMode = () => {
                isEditing = !isEditing;

                if (isEditing) {
                    // Chuyển sang EDIT mode
                    toggleEditText.textContent = "Hủy";
                    toggleEditBtn.classList.remove('bg-pink-100', 'text-pink-500', 'hover:text-pink-700');
                    toggleEditBtn.classList.add('bg-red-100', 'text-red-500', 'hover:text-red-700');
                    saveControls.classList.remove('hidden');
                    avatarInput.classList.remove('hidden'); 
                    
                    editModeInputs.forEach(input => input.classList.remove('hidden'));
                    viewModeSpans.forEach(span => span.classList.add('hidden'));

                } else {
                    // Chuyển sang VIEW mode (Hủy)
                    toggleEditText.textContent = "Chỉnh sửa";
                    toggleEditBtn.classList.add('bg-pink-100', 'text-pink-500', 'hover:text-pink-700');
                    toggleEditBtn.classList.remove('bg-red-100', 'text-red-500', 'hover:text-red-700');
                    saveControls.classList.add('hidden');
                    avatarInput.classList.add('hidden'); 
                    
                    // Đặt lại giá trị input về giá trị ban đầu (được Blade render)
                    form.reset(); 
                    
                    editModeInputs.forEach(input => input.classList.add('hidden'));
                    viewModeSpans.forEach(span => span.classList.remove('hidden'));
                }
            };
            
            toggleEditBtn.addEventListener('click', toggleEditMode);


            // --- Xem ảnh trước khi chọn avatar ---
            avatarInput.addEventListener("change", e => {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = ev => avatarImg.src = ev.target.result;
                    reader.readAsDataURL(file);
                }
            });

            // --- Gửi form AJAX ---
            form.addEventListener("submit", e => {
                e.preventDefault();

                if (!isEditing) {
                    // Nếu người dùng cố gắng submit khi chưa ở chế độ Edit, chặn lại
                    alert("Vui lòng nhấn 'Chỉnh sửa' trước khi lưu.");
                    return;
                }
                
                saveBtn.disabled = true;
                loadingIcon.classList.remove("hidden");
                saveText.textContent = "Đang lưu...";

                const formData = new FormData(form);
                
                // Thêm _method=PUT vào FormData
                if (form.querySelector('input[name="_method"]')) {
                    formData.append('_method', form.querySelector('input[name="_method"]').value);
                }
                
                fetch(form.action, {
                    method: "POST", // Dùng POST cho FormData, nhưng truyền _method=PUT
                    headers: { 
                        "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value 
                    },
                    body: formData
                })
                    .then(res => {
                        if (!res.ok) {
                             // Xử lý lỗi validation hoặc server
                            return res.json().then(errorData => {
                                console.error('Lỗi API:', errorData);
                                alert("Lưu thất bại. Vui lòng kiểm tra lại thông tin và thử lại!");
                                throw new Error('API request failed');
                            });
                        }
                        return res.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Hiện toast thông báo thành công
                            toast.style.backgroundColor = 'rgb(16, 185, 129)'; // green-500
                            document.getElementById('toast-message').textContent = 'Cập nhật thành công!';
                            toast.classList.add("show");
                            toast.style.transform = "translateX(0)";
                            toast.style.opacity = "1";
                            
                            // Ẩn toast và tải lại trang để cập nhật thông tin
                            setTimeout(() => {
                                toast.style.transform = "translateX(100%)";
                                toast.style.opacity = "0";
                            }, 2000);
                            
                            setTimeout(() => location.reload(), 2200); 
                        }
                    })
                    .catch(err => {
                        console.error("Lỗi:", err);
                    })
                    .finally(() => {
                        // Khôi phục trạng thái nút (trước khi reload)
                        saveBtn.disabled = false;
                        loadingIcon.classList.add("hidden");
                        saveText.textContent = "Lưu thay đổi";
                    });
            });
        });
    </script>

    <style>
        #toast-notification.show {
            /* Đảm bảo toast luôn hiện ra (override Tailwind's transform/opacity ban đầu) */
            transform: translateX(0) !important; 
            opacity: 1 !important;
        }
    </style>
</x-layout-site>