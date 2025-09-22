<x-layout-admin>
    <div class="content-wrapper">
        <div class="border border-blue-100 mb-6 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold text-blue-600">CHI TIẾT LIÊN HỆ</h2>
                <div class="text-right">
                    <a href="{{ route('contact.index') }}" class="bg-sky-500 px-4 py-2 rounded-lg text-white hover:bg-sky-600 transition">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i> Quay lại danh sách
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <!-- Name -->
            <div class="mb-6">
                <label class="font-semibold text-gray-800"><strong>Tên người gửi:</strong></label>
                <div class="p-3 border border-gray-300 rounded-lg bg-gray-50">{{ $contact->name }}</div>
            </div>

            <!-- Email -->
            <div class="mb-6">
                <label class="font-semibold text-gray-800"><strong>Email:</strong></label>
                <div class="p-3 border border-gray-300 rounded-lg bg-gray-50">{{ $contact->email }}</div>
            </div>

            <!-- Phone -->
            <div class="mb-6">
                <label class="font-semibold text-gray-800"><strong>Số điện thoại:</strong></label>
                <div class="p-3 border border-gray-300 rounded-lg bg-gray-50">{{ $contact->phone }}</div>
            </div>

            <!-- Title -->
            <div class="mb-6">
                <label class="font-semibold text-gray-800"><strong>Tiêu đề:</strong></label>
                <div class="p-3 border border-gray-300 rounded-lg bg-gray-50">{{ $contact->title }}</div>
            </div>

            <!-- Content -->
            <div class="mb-6">
                <label class="font-semibold text-gray-800"><strong>Nội dung:</strong></label>
                <div class="p-3 border border-gray-300 rounded-lg bg-gray-50">{{ $contact->content }}</div>
            </div>

            <!-- Status -->
            <div class="mb-6">
                <label class="font-semibold text-gray-800"><strong>Trạng thái:</strong></label>
                <div class="p-3 border border-gray-300 rounded-lg bg-gray-50">
                    @if ($contact->status == 1)
                        <span class="text-green-600 font-semibold">Đã trả lời</span>
                    @else
                        <span class="text-red-600 font-semibold">Chưa trả lời</span>
                    @endif
                </div>
            </div>

            <!-- Reply Content -->
            <div class="mb-6">
                <label class="font-semibold text-gray-800"><strong>Phản hồi:</strong></label>
                <div class="p-3 border border-gray-300 rounded-lg bg-gray-50">
                    {{ $contact->reply_content ?? 'Chưa có' }}
                </div>
            </div>

            <!-- Submission Date -->
            <div class="mb-6">
                <label class="font-semibold text-gray-800"><strong>Ngày gửi:</strong></label>
                <div class="p-3 border border-gray-300 rounded-lg bg-gray-50">{{ $contact->created_at->format('d/m/Y H:i') }}</div>
            </div>
        </div>
    </div>
</x-layout-admin>
