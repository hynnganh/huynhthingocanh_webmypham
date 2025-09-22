<x-layout-admin>
    <div class="bg-white p-6 rounded-lg shadow">
        <h1 class="text-xl font-bold mb-4">Trả lời liên hệ</h1>

        <form action="{{ route('contact.update', ['contact' => $contact->id]) }}" method="POST"> @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block font-semibold mb-1">Tên người gửi</label>
                <input type="text" value="{{ $contact->name }}" class="w-full border px-3 py-2 rounded bg-gray-100"
                    readonly>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Email</label>
                <input type="email" value="{{ $contact->email }}" class="w-full border px-3 py-2 rounded bg-gray-100"
                    readonly>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Phone</label>
                <input type="text" value="{{ $contact->phone }}" class="w-full border px-3 py-2 rounded bg-gray-100"
                    readonly>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Tiêu đề</label>
                <input type="text" value="{{ $contact->title }}" class="w-full border px-3 py-2 rounded bg-gray-100"
                    readonly>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Nội dung</label>
                <textarea rows="4" class="w-full border px-3 py-2 rounded bg-gray-100" readonly>{{ $contact->content }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Phản hồi</label>
                <textarea name="reply_content" rows="4" class="w-full border px-3 py-2 rounded"
                    placeholder="Nhập nội dung phản hồi...">{{ old('reply_content', $contact->reply_content) }}</textarea>
            </div>

            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Gửi phản
                hồi</button>
        </form>
    </div>
</x-layout-admin>
