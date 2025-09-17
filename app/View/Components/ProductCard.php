<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProductCard extends Component
{
    public $item = null;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($productrow)
    {
        $this->item = $productrow;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $product = $this->item;
        return view('components.product-card', compact('product'));
    }
}