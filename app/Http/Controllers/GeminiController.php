<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Product;

class GeminiController extends Controller
{
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

    public function ask(Request $request)
    {
        $request->validate(['prompt' => 'required|string']);
        $prompt = trim($request->input('prompt'));
        $lowerPrompt = strtolower($prompt);

        $history = session('history', []);
        $history[] = ['role' => 'user', 'content' => $prompt];

        $productsData = collect();
        $answerText = "";

        try {
            // --- 1️⃣ Trường hợp “rẻ nhất” / “đắt nhất” ---
            if (str_contains($lowerPrompt, 'rẻ nhất')) {
                $product = Product::orderBy('price_sale', 'asc')->first();
                if ($product) {
                    $answerText = "Sản phẩm rẻ nhất hiện tại là 💸 **{$product->name}** — giá chỉ " 
                        . number_format($product->price_sale, 0, ',', '.') . "₫.";
                    $productsData = collect([[
                        'name' => $product->name,
                        'description' => $product->description,
                        'price' => $product->price_sale,
                        'image' => $product->thumbnail ? asset('assets/images/product/' . $product->thumbnail) : '',
                        'detail_url' => route('site.product-detail', $product->slug),
                        'buy_url' => route('cart.add', ['id' => $product->id])
                    ]]);
                } else {
                    $answerText = "Không tìm thấy sản phẩm rẻ nhất 😥.";
                }

            } elseif (str_contains($lowerPrompt, 'đắt nhất')) {
                $product = Product::orderBy('price_sale', 'desc')->first();
                if ($product) {
                    $answerText = "Sản phẩm đắt nhất hiện tại là 💎 **{$product->name}** — giá " 
                        . number_format($product->price_sale, 0, ',', '.') . "₫.";
                    $productsData = collect([[
                        'name' => $product->name,
                        'description' => $product->description,
                        'price' => $product->price_sale,
                        'image' => $product->thumbnail ? asset('assets/images/product/' . $product->thumbnail) : '',
                        'detail_url' => route('site.product-detail', $product->slug),
                        'buy_url' => route('cart.add', ['id' => $product->id])
                    ]]);
                } else {
                    $answerText = "Không tìm thấy sản phẩm đắt nhất 😥.";
                }

            } else {
                // --- 2️⃣ Tìm sản phẩm theo prompt ---
                $products = Product::whereRaw('LOWER(name) LIKE ?', ['%' . $lowerPrompt . '%'])
                    ->orWhereRaw('LOWER(detail) LIKE ?', ['%' . $lowerPrompt . '%'])
                    ->get();

                // --- 3️⃣ Nếu không tìm thấy, tìm theo từng từ khóa ---
                if ($products->isEmpty()) {
                    $keywords = explode(' ', $lowerPrompt);
                    $queryBuilder = Product::query();
                    foreach ($keywords as $word) {
                        $queryBuilder->orWhereRaw('LOWER(name) LIKE ?', ['%' . $word . '%'])
                                     ->orWhereRaw('LOWER(detail) LIKE ?', ['%' . $word . '%']);
                    }
                    $products = $queryBuilder->limit(5)->get();
                }

                // --- 4️⃣ Nếu có sản phẩm ---
                if ($products->isNotEmpty()) {
                    $productsData = $products->map(function ($p) {
                        return [
                            'name' => $p->name,
                            'description' => $p->description,
                            'price' => $p->price_sale,
                            'image' => $p->thumbnail ? asset('assets/images/product/' . $p->thumbnail) : '',
                            'detail_url' => route('site.product-detail', $p->slug),
                            'buy_url' => route('cart.add', ['id' => $p->id]),
                        ];
                    });
                    $answerText = "Mình đã tìm thấy " . $productsData->count() . " sản phẩm phù hợp 💅.";
                } 
                // --- 5️⃣ Nếu không có → gọi Gemini ---
                else {
                    Log::info("Không tìm thấy sản phẩm, gọi Gemini API cho prompt: {$prompt}");

                    $apiKey = env('GEMINI_API_KEY');
                    $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-preview-09-2025:generateContent?key={$apiKey}";

                    $response = Http::withHeaders([
                        'Content-Type' => 'application/json',
                    ])->post($apiUrl, [
                        'contents' => [[
                            'parts' => [[
                                'text' => "Bạn là trợ lý AI thân thiện, nói chuyện tự nhiên, dễ hiểu. 
                                Hãy trả lời ngắn gọn, dễ hiểu nhất cho câu hỏi sau:\n\n{$prompt}"
                            ]]
                        ]]
                    ]);

                    if ($response->failed()) {
                        Log::error('Gemini API failed', [
                            'status' => $response->status(),
                            'body' => $response->body(),
                        ]);
                        $answerText = "Lỗi Gemini: Không thể nhận được câu trả lời từ AI 😥 (Mã lỗi: {$response->status()})";
                    } else {
                        $data = $response->json();
                        $answerText = data_get($data, 'candidates.0.content.parts.0.text', 
                            'Xin lỗi, mình không thể trả lời câu hỏi này ngay bây giờ 😥.');
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error("Lỗi khi xử lý prompt AI", ['error' => $e->getMessage()]);
            $answerText = "Đã xảy ra lỗi trong hệ thống 😭. Vui lòng thử lại sau.";
        }

        // --- 6️⃣ Lưu lịch sử ---
        $history[] = ['role' => 'ai', 'content' => $answerText];
        session(['history' => $history]);

        return response()->json([
            'answer' => $answerText,
            'products' => $productsData->values(),
        ]);
    }

    public function reset()
    {
        session()->forget('history');
        return redirect()->route('chat.ai.form');
    }
}
