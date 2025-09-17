<x-layout-site>
    <x-slot:title>
        Liên hệ
    </x-slot:title>

    <main>
        <div class="flex flex-wrap">
            <!-- Contact Form Section -->
            <div class="w-full sm:w-2/3 p-6">
                <div class="contact-form bg-white shadow-lg p-6 rounded-lg">
                    @if(session('success'))
                        <div class="alert alert-success mb-4 p-4 bg-green-500 text-white rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif
                    <form action="{{ route('site.contact.store') }}" method="POST">
                        @csrf

                        <h2 class="text-3xl mb-6 text-center text-[#F7A7C1] animate-pulse">Liên hệ với chúng tôi</h2>
                        <div class="status alert alert-success" style="display: none"></div>

                        <!-- Tên -->
                        <div class="form-group mb-4">
                            <input type="text" name="contact_name"
                                   class="form-control w-full p-4 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#F7A7C1]"
                                   value="{{ old('contact_name', Auth::check() ? Auth::user()->name : '') }}"
                                   required placeholder="Tên">
                        </div>

                        <!-- Email -->
                        <div class="form-group mb-4">
                            <input type="email" name="contact_email"
                                   class="form-control w-full p-4 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#F7A7C1]"
                                   value="{{ old('contact_email', Auth::check() ? Auth::user()->email : '') }}"
                                   required placeholder="Địa chỉ email">
                        </div>

                        <!-- Số điện thoại -->
                        <div class="form-group mb-4">
                            <input type="phone" name="contact_phone"
                                   class="form-control w-full p-4 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#F7A7C1]"
                                   value="{{ old('contact_phone', Auth::check() ? Auth::user()->phone : '') }}"
                                   required placeholder="Số điện thoại">
                        </div>

                        <!-- Tiêu đề -->
                        <div class="form-group mb-4">
                            <input type="text" name="contact_subject"
                                   class="form-control w-full p-4 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#F7A7C1]"
                                   value="{{ old('contact_subject') }}"
                                   required placeholder="Tiêu đề lời nhắn">
                        </div>

                        <!-- Nội dung -->
                        <div class="form-group mb-4">
                            <textarea name="contact_message" id="message"
                                      class="form-control w-full p-4 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#F7A7C1]"
                                      rows="8" required placeholder="Nội dung lời nhắn">{{ old('contact_message') }}</textarea>
                        </div>

                        <div class="form-group">
                            <input type="submit" name="submit"
                                   class="btn w-full py-3 bg-[#F7A7C1] text-white rounded-lg shadow-lg hover:bg-[#8C1C13] transition-all duration-300"
                                   value="Gửi">
                        </div>
                    </form>
                </div>
            </div>

            <!-- Contact Information Section -->
            <div class="w-full sm:w-1/3 p-6">
                <div class="contact-info bg-white shadow-lg p-8 rounded-lg">
                    <h2 class="text-center text-3xl mb-6 text-[#F7A7C1]">Thông tin shop</h2>
                    <address class="text-gray-700 text-base">
                        <p class="mb-2"><strong>Địa chỉ:</strong> 103 Tăng Nhơn Phú, Phước Long B, TP.Thủ Đức</p>
                        <p class="mb-2"><strong>Điện thoại:</strong> +84 869803329</p>
                        <p><strong>Email:</strong> huynhthingocanh2008@gmail.com</p>
                    </address>
                </div>

                <!-- Social Networks Section -->
                <div class="social-networks mt-8 bg-white shadow-lg p-8 rounded-lg font-serif">
                    <h2 class="text-center text-3xl mb-6 text-[#F7A7C1]">Mạng xã hội</h2>
                    <div class="flex space-x-6 justify-center">
                        <a href="https://www.facebook.com/anhloveyou08" target="_blank"
                           class="text-lg text-blue-600 hover:text-blue-700 transition duration-300">
                            <i class="fab fa-facebook-f text-2xl"></i> Facebook
                        </a>
                        <a href="https://www.instagram.com/hynnganh" target="_blank"
                           class="text-lg text-pink-600 hover:text-pink-700 transition duration-300">
                            <i class="fab fa-instagram text-2xl"></i> Instagram
                        </a>
                        <a href="https://www.tiktok.com/@htnanh23" target="_blank"
                           class="text-lg text-black hover:text-gray-800 transition duration-300">
                            <i class="fab fa-tiktok text-2xl"></i> TikTok
                        </a>
                    </div>
                </div>

                <!-- Google Map Section -->
                <div class="w-full p-4">
                    <div class="relative">
                        <iframe
                            class="w-full h-80 sm:h-96 object-cover"
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1796.759615604243!2d106.77227704300134!3d10.829630916238354!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x317527003404296d%3A0xda14c6391b1f8c82!2zMTAzIFTEg25nIE5oxqFuIFBow7osIFBoxrDhu5tjIExvbmcgQiwgVGjhu6cgxJDhu6ljLCBI4buTIENow60gTWluaCA3MTU5MzksIFZp4buHdCBOYW0!5e0!3m2!1svi!2s!4v1741078354310!5m2!1svi!2s"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-layout-site>
