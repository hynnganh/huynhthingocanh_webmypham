<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Product;

class ProductNew extends Component
{
    public function __construct()
    {
        //
    }

    public function render(): View|Closure|string
    {
        $args = [
            ['status', '=', 1],
        ];

        $product_list = Product::where($args)
                    ->orderBy('created_at', 'desc')
                    ->take(4)
                    ->get();

        return view('components.product-new', compact('product_list'));
    }
}
