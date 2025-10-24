<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use Illuminate\Support\Str;

class GeminiController extends Controller
{
    // 🧠 Trang chat AI
    public function index()
    {
        $history = session('history', []);
        if (empty($history)) {
            $history[] = [
                'role' => 'ai',
                'content' => 'Xin chào 👋, mình có thể giúp gì cho bạn hôm nay?'
            ];
            session(['history' => $history]);
        }
        return view('frontend.gemini.result', compact('history'));
    }

    // 💬 Xử lý khi người dùng gửi tin nhắn
    public function ask(Request $request)
    {
        $request->validate(['prompt' => 'required|string']);
        $prompt = $request->input('prompt');
        $lowerPrompt = strtolower($prompt);

        $history = session('history', []);
        $history[] = ['role' => 'user', 'content' => $prompt];

        // ⚖️ 1️⃣ So sánh giá
        if (Str::contains($lowerPrompt, ['so sánh', 'so sanh', 'rẻ hơn', 'đắt hơn'])) {
            return $this->comparePrice($lowerPrompt, $history);
        }

        // 💸 2️⃣ Tìm sản phẩm rẻ nhất / đắt nhất
        if (Str::contains($lowerPrompt, ['rẻ nhất', 'đắt nhất', 'cao nhất', 'thấp nhất'])) {
            return $this->findCheapestAndMostExpensive($lowerPrompt, $history);
        }

        // 🔍 3️⃣ Tìm sản phẩm theo từ khóa (trong name và detail)
        $products = Product::whereRaw('LOWER(name) LIKE ?', ['%' . $lowerPrompt . '%'])
            ->orWhereRaw('LOWER(detail) LIKE ?', ['%' . $lowerPrompt . '%'])
            ->limit(5)
            ->get();

        // Nếu không tìm thấy → tìm theo từng từ
        if ($products->isEmpty()) {
            $keywords = explode(' ', $lowerPrompt);
            $queryBuilder = Product::query();
            foreach ($keywords as $word) {
                $queryBuilder->orWhereRaw('LOWER(name) LIKE ?', ['%' . $word . '%'])
                             ->orWhereRaw('LOWER(detail) LIKE ?', ['%' . $word . '%']);
            }
            $products = $queryBuilder->limit(5)->get();
        }

        // Nếu vẫn không có
        if ($products->isEmpty()) {
            $answerText = "Xin lỗi 😢, mình không tìm thấy sản phẩm nào phù hợp.";
            $history[] = ['role' => 'ai', 'content' => $answerText];
            session(['history' => $history]);
            return response()->json(['products' => [], 'answer' => $answerText]);
        }

        // ✅ Nếu có kết quả
        $answerText = "Mình đã tìm thấy " . $products->count() . " sản phẩm phù hợp ✨";
        $productsData = $this->formatProducts($products);

        $history[] = ['role' => 'ai', 'content' => $answerText];
        session(['history' => $history]);

        return response()->json([
            'answer' => $answerText,
            'products' => $productsData,
        ]);
    }

    // ⚖️ So sánh giá giữa nhiều sản phẩm
    private function comparePrice($prompt, &$history)
    {
        preg_match_all('/([\p{L}\p{N}\s]+)/u', $prompt, $matches);
        $words = collect($matches[1])->filter(fn($w) => strlen(trim($w)) > 2)->values();

        if ($words->count() < 2) {
            $answer = "Hãy nhập tên của ít nhất hai sản phẩm để mình so sánh giá nha 💬";
            $history[] = ['role' => 'ai', 'content' => $answer];
            session(['history' => $history]);
            return response()->json(['answer' => $answer]);
        }

        $products = Product::where(function ($query) use ($words) {
            foreach ($words as $word) {
                $query->orWhere('name', 'like', '%' . trim($word) . '%');
            }
        })->limit(5)->get();

        if ($products->count() < 2) {
            $answer = "Mình chỉ tìm thấy " . $products->count() . " sản phẩm thôi 😅. Hãy thử nhập rõ hơn nha.";
            $history[] = ['role' => 'ai', 'content' => $answer];
            session(['history' => $history]);
            return response()->json(['answer' => $answer]);
        }

        $sorted = $products->sortBy('price_sale')->values();
        $cheapest = $sorted->first();
        $mostExpensive = $sorted->last();

        $answer = "💰 So sánh giá:\n\n";
        foreach ($products as $p) {
            $answer .= "• {$p->name}: " . number_format($p->price_sale, 0, ',', '.') . "₫\n";
        }
        $answer .= "\n👉 **Rẻ nhất:** {$cheapest->name} — " . number_format($cheapest->price_sale, 0, ',', '.') . "₫";
        $answer .= "\n👉 **Đắt nhất:** {$mostExpensive->name} — " . number_format($mostExpensive->price_sale, 0, ',', '.') . "₫";

        $history[] = ['role' => 'ai', 'content' => nl2br($answer)];
        session(['history' => $history]);

        return response()->json([
            'answer' => nl2br($answer),
            'products' => $this->formatProducts($products)
        ]);
    }

    // 💸 Tìm sản phẩm rẻ nhất / đắt nhất toàn site (trả về format giống tìm kiếm)
    private function findCheapestAndMostExpensive($prompt, &$history)
    {
        $cheapest = Product::orderBy('price_sale', 'asc')->first();
        $mostExpensive = Product::orderBy('price_sale', 'desc')->first();

        if (!$cheapest || !$mostExpensive) {
            $answer = "Hiện tại chưa có dữ liệu sản phẩm để so sánh 😅";
            $history[] = ['role' => 'ai', 'content' => $answer];
            session(['history' => $history]);
            return response()->json(['answer' => $answer]);
        }

        $answer = "📊 Kết quả tìm thấy:\n";
        $answer .= "\n💰 **Sản phẩm rẻ nhất:** {$cheapest->name} — " . number_format($cheapest->price_sale, 0, ',', '.') . "₫";
        $answer .= "\n👑 **Sản phẩm đắt nhất:** {$mostExpensive->name} — " . number_format($mostExpensive->price_sale, 0, ',', '.') . "₫";

        $products = collect([$cheapest, $mostExpensive]);

        $history[] = ['role' => 'ai', 'content' => nl2br($answer)];
        session(['history' => $history]);

        return response()->json([
            'answer' => nl2br($answer),
            'products' => $this->formatProducts($products)
        ]);
    }

    // Hàm format dữ liệu sản phẩm chuẩn để dùng chung
    private function formatProducts($products)
    {
        return $products->take(5)->map(function ($p) {
            return [
                'name' => $p->name,
                'detail' => $p->detail,
                'price' => $p->price_sale,
                'image' => $p->thumbnail ? asset('assets/images/product/' . $p->thumbnail) : '',
                'detail_url' => route('site.product-detail', $p->slug),
                'buy_url' => route('cart.add', ['id' => $p->id]),
            ];
        });
    }

    // 🔄 Reset lịch sử chat
    public function reset()
    {
        session()->forget('history');
        return redirect()->route('chat.ai.form');
    }
}
