<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Product;

class ProductNew extends Component
{
    public $product_list; // truyền ra view

    public function __construct()
    {
        //
    }

    public function render(): View|Closure|string
    {
        $args = [
            ['status', '=', 1],
        ];

        $this->product_list = Product::where($args)
                    ->orderBy('created_at', 'desc')
                    ->take(4)
                    ->get();

        // truyền đúng biến ra view
        return view('components.product-new', ['product_list' => $this->product_list]);
    }
}
