<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Product;

class ProductSale extends Component
{
    public function __construct()
    {
        //
    }

    public function render(): View|Closure|string
    {
        $args = [
            ['status', '=', 1],
            ['price_sale', '>', 0],
        ];

        $product_list = Product::where($args)
            ->take(4)
            ->get();

        return view('components.product-sale', compact('product_list'));
    }
}
