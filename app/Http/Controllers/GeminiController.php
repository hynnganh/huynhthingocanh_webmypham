<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use Illuminate\Support\Str;

class GeminiController extends Controller
{
    // 🧠 Load trang chat + lịch sử
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

    // 💬 Xử lý khi người dùng gửi prompt
    public function ask(Request $request)
    {
        $request->validate(['prompt' => 'required|string']);
        $prompt = $request->input('prompt');
        $lowerPrompt = strtolower($prompt);

        $history = session('history', []);
        $history[] = ['role' => 'user', 'content' => $prompt];

        // 🔎 1️⃣ Kiểm tra xem người dùng có muốn so sánh giá không
        if (Str::contains($lowerPrompt, ['so sánh', 'so sanh', 'rẻ hơn', 'đắt hơn'])) {
            return $this->comparePrice($lowerPrompt, $history);
        }

        // 🔎 2️⃣ Tìm sản phẩm như cũ
        $products = Product::whereRaw('LOWER(name) LIKE ?', ['%' . $lowerPrompt . '%'])
            ->orWhereRaw('LOWER(description) LIKE ?', ['%' . $lowerPrompt . '%'])
            ->get();

        // Nếu không tìm thấy, tìm theo từng từ khóa
        if ($products->isEmpty()) {
            $keywords = explode(' ', $lowerPrompt);
            $queryBuilder = Product::query();
            foreach ($keywords as $word) {
                $queryBuilder->orWhereRaw('LOWER(name) LIKE ?', ['%' . $word . '%'])
                    ->orWhereRaw('LOWER(description) LIKE ?', ['%' . $word . '%']);
            }
            $products = $queryBuilder->limit(5)->get();
        }

        // Nếu vẫn không có sản phẩm
        if ($products->isEmpty()) {
            $answerText = "Xin lỗi 😢, mình không tìm thấy sản phẩm nào phù hợp.";
            $history[] = ['role' => 'ai', 'content' => $answerText];
            session(['history' => $history]);

            return response()->json([
                'products' => [],
                'answer' => $answerText
            ]);
        }

        // Nếu có kết quả
        $answerText = "Mình đã tìm thấy " . $products->count() . " sản phẩm phù hợp ✨";
        $productsData = $products->map(function ($p) {
            return [
                'name' => $p->name,
                'description' => $p->description,
                'price' => $p->price_sale,
                'image' => $p->thumbnail ? asset('assets/images/product/' . $p->thumbnail) : '',
                'detail_url' => route('site.product-detail', $p->slug),
                'buy_url' => route('cart.add', ['id' => $p->id])
            ];
        });

        $history[] = ['role' => 'ai', 'content' => $answerText];
        session(['history' => $history]);

        return response()->json([
            'answer' => $answerText,
            'products' => $productsData,
        ]);
    }

    // ⚖️ Hàm riêng xử lý so sánh giá
    private function comparePrice($prompt, &$history)
    {
        // Tách tên sản phẩm từ prompt
        preg_match_all('/so sánh|so sanh|rẻ hơn|đắt hơn|(\w+(?:\s+\w+){0,3})/', $prompt, $matches);
        $words = collect($matches[1])->filter()->values();

        if ($words->count() < 2) {
            $answer = "Hãy nhập tên của ít nhất hai sản phẩm để mình so sánh giá nha 💬";
            $history[] = ['role' => 'ai', 'content' => $answer];
            session(['history' => $history]);
            return response()->json(['answer' => $answer]);
        }

        // Tìm sản phẩm khớp
        $products = Product::where(function ($query) use ($words) {
            foreach ($words as $word) {
                $query->orWhere('name', 'like', '%' . trim($word) . '%');
            }
        })->get();

        if ($products->count() < 2) {
            $answer = "Mình chỉ tìm thấy " . $products->count() . " sản phẩm thôi 😅. Hãy thử nhập tên đầy đủ hơn nha.";
            $history[] = ['role' => 'ai', 'content' => $answer];
            session(['history' => $history]);
            return response()->json(['answer' => $answer]);
        }

        // Sắp xếp theo giá
        $sorted = $products->sortBy('price_sale')->values();
        $cheapest = $sorted->first();
        $mostExpensive = $sorted->last();

        $answer = "💰 So sánh giá:\n\n";
        foreach ($products as $p) {
            $answer .= "• {$p->name}: " . number_format($p->price_sale, 0, ',', '.') . "₫\n";
        }
        $answer .= "\n👉 Sản phẩm **rẻ nhất** là **{$cheapest->name}**, giá " . number_format($cheapest->price_sale, 0, ',', '.') . "₫.";
        $answer .= "\n👉 Sản phẩm **đắt nhất** là **{$mostExpensive->name}**, giá " . number_format($mostExpensive->price_sale, 0, ',', '.') . "₫.";

        $history[] = ['role' => 'ai', 'content' => nl2br($answer)];
        session(['history' => $history]);

        return response()->json(['answer' => nl2br($answer)]);
    }

    // 🔄 Reset lịch sử chat
    public function reset()
    {
        session()->forget('history');
        return redirect()->route('chat.ai.form');
    }
}
