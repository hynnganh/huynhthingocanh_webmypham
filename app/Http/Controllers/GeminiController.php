<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class GeminiController extends Controller
{
    // Load trang chat + lịch sử
    public function index()
    {
        $history = session('history', []);

        if(empty($history)){
            $history[] = [
                'role' => 'ai',
                'content' => 'Xin chào 👋, mình có thể giúp gì cho bạn hôm nay?'
            ];
            session(['history' => $history]);
        }

        return view('frontend.gemini.result', compact('history'));
    }

    // Xử lý gửi prompt
    public function ask(Request $request)
    {
        $request->validate(['prompt' => 'required|string']);
        $prompt = $request->input('prompt');
        $lowerPrompt = strtolower($prompt);

        $history = session('history', []);
        $history[] = ['role' => 'user', 'content' => $prompt];

        // --- 1️⃣ Tìm theo nguyên cụm prompt ---
        $products = Product::whereRaw('LOWER(name) LIKE ?', ['%' . $lowerPrompt . '%'])
            ->orWhereRaw('LOWER(detail) LIKE ?', ['%' . $lowerPrompt . '%'])
            ->get();

        // --- 2️⃣ Nếu không tìm thấy, tìm theo từng từ khóa ---
        if ($products->isEmpty()) {
            $keywords = explode(' ', $lowerPrompt);
            $queryBuilder = Product::query();
            foreach($keywords as $word){
                $queryBuilder->orWhereRaw('LOWER(name) LIKE ?', ['%' . $word . '%'])
                             ->orWhereRaw('LOWER(detail) LIKE ?', ['%' . $word . '%']);
            }
            $products = $queryBuilder->limit(5)->get();
        }

        $productsData = $products->map(function($p){
            return [
                'name' => $p->name,
                'description' => $p->description,
                'price' => $p->price_sale,
                'image' => $p->thumbnail ? asset('assets/images/product/'.$p->thumbnail) : '',
                'detail_url' => route('site.product-detail', $p->slug),
                'buy_url' => route('cart.add', ['id' => $p->id])
            ];
        });

        if ($productsData->isEmpty()) {
            $answerText = "Xin lỗi, không tìm thấy sản phẩm.";
            $history[] = ['role' => 'ai', 'content' => $answerText];
            session(['history' => $history]);

            return response()->json([
                'products' => [],
                'answer' => $answerText
            ]);
        }

        // --- Nếu tìm thấy sản phẩm ---
        $answerText = "Mình đã tìm thấy " . $productsData->count() . " sản phẩm phù hợp.";
        $history[] = ['role'=>'ai','content'=>$answerText];

        // Lưu từng sản phẩm 1 message
        foreach($productsData as $p){
            $history[] = ['role'=>'ai','content'=>$p];
        }

        session(['history' => $history]);

        return response()->json([
            'answer' => $answerText,
            'products' => $productsData
        ]);
    }

    // Reset lịch sử chat
    public function reset()
    {
        session()->forget('history');
        return redirect()->route('chat.ai.form');
    }
}
