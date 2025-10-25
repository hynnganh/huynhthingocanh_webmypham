<x-layout-site title="Hồ sơ cá nhân">
    <div class="container mx-auto py-12 px-4">
        <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-2xl overflow-hidden border border-pink-300">
            <div class="relative bg-gradient-to-r from-pink-300 to-pink-500 h-48 flex items-center justify-center">
                <div class="absolute top-4 left-4">
                    <a href="{{ route('home') }}"
                       class="bg-white/70 text-pink-600 px-4 py-2 rounded-lg font-semibold shadow hover:bg-white hover:text-pink-700 transition">
                        ← Quay lại
                    </a>
                </div>

                <div class="relative group">
                    <img 
                        id="user-main-avatar"
                        src="{{ filter_var($user->avatar, FILTER_VALIDATE_URL) 
                                ? $user->avatar 
                                : asset('asset/images/user/' . ($user->avatar ?? 'default.png')) }}" 
                        alt="Avatar" 
                        class="rounded-full border-4 border-white shadow-md object-cover w-32 h-32"
                    />
                    <button data-modal-target="avatar-modal" data-modal-toggle="avatar-modal"
                            class="absolute bottom-0 right-0 bg-white text-pink-600 p-2 rounded-full shadow hover:bg-pink-100 transition">
                        <i class="fa-solid fa-camera"></i>
                    </button>
                </div>
            </div>

            <div class="p-8">
                <h2 class="text-3xl font-bold text-pink-600 text-center mb-6">Thông tin cá nhân</h2>

                <form id="update-profile-form" enctype="multipart/form-data"
                      class="space-y-6 bg-pink-50 p-6 rounded-xl shadow-inner">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-pink-700 mb-2">Họ tên</label>
                        <input type="text" name="name" value="{{ $user->name }}"
                               class="w-full border border-pink-300 rounded-lg p-3 focus:ring-2 focus:ring-pink-400 outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-pink-700 mb-2">Số điện thoại</label>
                        <input type="text" name="phone" value="{{ $user->phone }}"
                               class="w-full border border-pink-300 rounded-lg p-3 focus:ring-2 focus:ring-pink-400 outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-pink-700 mb-2">Địa chỉ</label>
                        <input type="text" name="address" value="{{ $user->address }}"
                               class="w-full border border-pink-300 rounded-lg p-3 focus:ring-2 focus:ring-pink-400 outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-pink-700 mb-2">Tên đăng nhập</label>
                        <input type="text" name="username" value="{{ $user->username }}"
                               class="w-full border border-pink-300 rounded-lg p-3 focus:ring-2 focus:ring-pink-400 outline-none">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="bg-pink-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-pink-600 transition transform hover:scale-105 shadow">
                            <i class="fa-solid fa-floppy-disk mr-2"></i> Cập nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal đổi avatar -->
    <div id="avatar-modal" tabindex="-1" aria-hidden="true"
         class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-xl w-96 p-6 relative">
            <button data-modal-hide="avatar-modal"
                    class="absolute top-3 right-3 text-gray-500 hover:text-pink-600">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>

            <h3 class="text-xl font-bold text-pink-600 mb-4 text-center">Cập nhật ảnh đại diện</h3>
            <div class="flex flex-col items-center gap-4">
                <img 
                    id="current-avatar-modal"
                    src="{{ filter_var($user->avatar, FILTER_VALIDATE_URL) 
                            ? $user->avatar 
                            : asset('asset/images/user/' . ($user->avatar ?? 'default.png')) }}"
                    alt="Current Avatar"
                    class="w-24 h-24 rounded-full object-cover border-4 border-pink-400 shadow-md hover:border-pink-600 transition duration-300"
                >

                <input type="file" id="avatar-input" name="avatar" accept="image/*"
                       class="block w-full text-sm text-gray-600 bg-pink-50 border border-pink-200 rounded-lg cursor-pointer focus:outline-none">

                <button id="upload-avatar-btn"
                        class="bg-pink-500 text-white px-5 py-2 rounded-lg font-semibold hover:bg-pink-600 transition">
                    <i class="fa-solid fa-upload mr-2"></i> Tải lên
                </button>
            </div>
        </div>
    </div>

    <!-- Toast -->
    <div id="toast" class="hidden fixed bottom-6 right-6 bg-pink-600 text-white px-5 py-3 rounded-lg shadow-lg transition transform translate-y-4 opacity-0">
        <span id="toast-message">Đang xử lý...</span>
    </div>

    <script>
        // ✅ Hiển thị Toast
        function showToast(message, isSuccess = true) {
            const toast = document.getElementById("toast");
            const msg = document.getElementById("toast-message");
            toast.classList.remove("hidden");
            toast.classList.add("opacity-100", "translate-y-0");
            toast.style.backgroundColor = isSuccess ? "#ec4899" : "#ef4444";
            msg.textContent = message;
            setTimeout(() => {
                toast.classList.remove("opacity-100", "translate-y-0");
                toast.classList.add("opacity-0", "translate-y-4");
                setTimeout(() => toast.classList.add("hidden"), 500);
            }, 2500);
        }

        // ✅ Cập nhật thông tin profile
        const profileForm = document.getElementById("update-profile-form");
        profileForm.addEventListener("submit", async (e) => {
            e.preventDefault();
            const formData = new FormData(profileForm);

            try {
                const response = await fetch("{{ route('profile.update') }}", {
                    method: "POST",
                    body: formData
                });
                const data = await response.json();
                if (!response.ok) throw new Error(data.message || "Lỗi cập nhật!");

                showToast(data.message);
                if (data.avatar_url) {
                    document.getElementById("user-main-avatar").src = data.avatar_url;
                    document.getElementById("current-avatar-modal").src = data.avatar_url;
                }
                setTimeout(() => window.location.reload(), 1500);
            } catch (error) {
                showToast(error.message || "Lỗi kết nối server!", false);
            }
        });

        // ✅ Upload avatar mới
        const uploadBtn = document.getElementById("upload-avatar-btn");
        const avatarInput = document.getElementById("avatar-input");

        uploadBtn.addEventListener("click", async () => {
            if (!avatarInput.files.length) return showToast("Vui lòng chọn ảnh!", false);

            const formData = new FormData();
            formData.append("avatar", avatarInput.files[0]);
            formData.append("_token", "{{ csrf_token() }}");

            try {
                const response = await fetch("{{ route('profile.update') }}", {
                    method: "POST",
                    body: formData
                });
                const data = await response.json();
                if (!response.ok) throw new Error(data.message || "Lỗi tải ảnh!");

                showToast("Cập nhật ảnh thành công!");
                if (data.avatar_url) {
                    document.getElementById("user-main-avatar").src = data.avatar_url;
                    document.getElementById("current-avatar-modal").src = data.avatar_url;
                }
                setTimeout(() => window.location.reload(), 1500);
            } catch (error) {
                showToast(error.message || "Lỗi server khi tải ảnh!", false);
            }
        });
    </script>
</x-layout-site>
