<div class="max-w-[1400px] mx-auto my-8 px-4">
    <header class="text-center mb-8">
        <h1 class="text-3xl font-bold text-pink-700 tracking-wider">
            <i class="fas fa-gift text-pink-500 mr-2"></i> Kho Voucher Của Bạn
        </h1>
        <p class="text-gray-500 mt-1">Chọn và sao chép mã ưu đãi tốt nhất.</p>
    </header>

    <div id="coupon-grid" class="flex overflow-x-auto space-x-5 pb-4">
    </div>
        
    <div id="toastNotification" class="fixed bottom-5 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded-xl shadow-2xl transition-all duration-300 opacity-0 translate-y-20 z-50">
        <i class="fas fa-check-circle mr-2"></i> Đã sao chép mã!
    </div>

</div>
    
<div id="detailModal" class="fixed inset-0 hidden items-center justify-center z-50 modal-backdrop transition-opacity duration-300" onclick="closeModal()">
    <div class="bg-white rounded-2xl p-6 md:p-8 shadow-3xl max-w-sm w-full transform transition-all duration-300 scale-95" onclick="event.stopPropagation()">
        
        <h2 class="text-2xl font-extrabold text-pink-600 mb-4 border-b pb-2">Chi Tiết Voucher</h2>
        
        <div class="space-y-3">
            <p class="text-gray-600 font-semibold">Mã:</p>
            <div id="modalCode" class="coupon-code-display !text-2xl !py-2"></div>
            
            <p class="text-gray-600 font-semibold pt-2">Giá trị ưu đãi:</p>
            <p id="modalDiscount" class="text-xl font-bold text-red-500"></p>
            
            <p class="text-gray-600 font-semibold pt-2">Điều kiện áp dụng:</p>
            <p id="modalCondition" class="text-gray-700 text-sm italic"></p>
        </div>
        
        <button onclick="closeModal()" class="mt-6 w-full bg-pink-500 text-white font-bold py-3 rounded-xl hover:bg-pink-600 transition duration-150 shadow-lg">
            Đóng
        </button>
    </div>
</div>

    <script>
        // Dữ liệu giả lập (Chuyển từ PHP Array sang JS Array)
        const coupons = [
            { code: 'COCOLUX25K', discount: '25.000đ', condition: 'Đơn hàng từ 299K' },
            { code: 'FREESHIP', discount: 'Miễn phí ship', condition: 'Đơn hàng từ 400K' },
            { code: 'VIP10', discount: 'Giảm 10%', condition: 'Chỉ áp dụng cho thành viên VIP' },
            { code: 'FLASH50K', discount: '50.000đ', condition: 'Đơn hàng đầu tiên từ 500K' },
            { code: 'BUY2GET1', discount: 'Mua 2 Tặng 1', condition: 'Áp dụng cho các sản phẩm chọn lọc' },
        ];

        // --- Hàm DOM và Render ---
        window.onload = function() {
            renderCoupons();
        };

        function renderCoupons() {
            const grid = document.getElementById('coupon-grid');
            grid.innerHTML = ''; // Clear existing content
            
            coupons.forEach(coupon => {
                const card = document.createElement('div');
                // Đã thêm flex-shrink-0 và w-72 để thẻ không co lại và có chiều rộng cố định
                card.className = 'coupon-card p-5 shadow-xl bg-white border border-pink-100 flex-shrink-0 w-72'; 
                card.innerHTML = `
                    <div class="mb-4">
                        <h4 class="text-lg font-bold text-red-600">${coupon.discount}</h4>
                        <p class="text-sm text-gray-500 line-clamp-2">${coupon.condition}</p>
                    </div>
                    
                    <!-- Phần hiển thị mã -->
                    <div class="mb-4">
                        <p class="text-xs font-semibold text-pink-400 mb-1 uppercase">Mã voucher:</p>
                        <div class="coupon-code-display">${coupon.code}</div>
                    </div>

                    <div class="flex space-x-3 mt-auto">
                        
                        <!-- Nút Chi tiết (Mở Modal) -->
                        <button onclick="openModal('${coupon.code}', '${coupon.discount}', '${coupon.condition}')"
                                class="flex-1 border border-pink-500 text-pink-500 font-semibold text-sm px-3 py-2 rounded-lg hover:bg-pink-50 transition duration-150">
                            <i class="fas fa-info-circle mr-1"></i> Chi tiết
                        </button>

                        <!-- Nút Sao chép Mã Voucher -->
                        <button id="copyBtn-${coupon.code}" onclick="copyCode(this, '${coupon.code}')" 
                                class="copy-button flex-1 bg-pink-500 text-white font-bold text-sm px-3 py-2 rounded-lg flex items-center justify-center hover:bg-pink-600 shadow-md">
                            <span class="mr-1">Sao chép</span>
                            <i class="fas fa-copy text-xs"></i>
                        </button>
                    </div>
                `;
                grid.appendChild(card);
            });
        }

        // --- Hàm Modal ---
        function openModal(code, discount, condition) {
            document.getElementById('modalCode').textContent = code;
            document.getElementById('modalDiscount').textContent = discount;
            document.getElementById('modalCondition').textContent = condition;
            
            const modal = document.getElementById('detailModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex', 'opacity-0');
            
            // Apply transition effect
            setTimeout(() => {
                modal.classList.remove('opacity-0');
            }, 10); 
        }

        function closeModal() {
            const modal = document.getElementById('detailModal');
            modal.classList.add('opacity-0');
            
            // Hide after transition
            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }, 300);
        }

        // --- Hàm Sao chép và Alert ---
        function copyCode(button, code) {
            const tempInput = document.createElement('textarea');
            tempInput.value = code;
            document.body.appendChild(tempInput);
            tempInput.select();
            
            try {
                const successful = document.execCommand('copy');
                if (successful) {
                    showToast(code);
                    
                    // Cập nhật trạng thái nút
                    const originalText = button.innerHTML;
                    button.innerHTML = '<i class="fas fa-check-circle mr-1"></i> Đã Sao Chép';
                    button.classList.add('bg-green-500', 'hover:bg-green-600');
                    button.classList.remove('bg-pink-500', 'hover:bg-pink-600');

                    setTimeout(() => {
                        button.innerHTML = originalText;
                        button.classList.remove('bg-green-500', 'hover:bg-green-600');
                        button.classList.add('bg-pink-500', 'hover:bg-pink-600');
                    }, 2000);
                }
            } catch (err) {
                console.error('Không thể sao chép:', err);
            }
            
            document.body.removeChild(tempInput);
        }

        function showToast(code) {
            const toast = document.getElementById('toastNotification');
            
            // Xóa timeout cũ nếu có
            clearTimeout(toast.timeoutId);
            
            toast.textContent = `Đã sao chép mã ${code} thành công!`;
            
            // Show toast
            toast.classList.remove('opacity-0', 'translate-y-20');
            toast.classList.add('opacity-100', 'translate-y-0');

            // Hide toast after 3 seconds
            toast.timeoutId = setTimeout(() => {
                toast.classList.remove('opacity-100', 'translate-y-0');
                toast.classList.add('opacity-0', 'translate-y-20');
            }, 3000);
        }
    </script>

