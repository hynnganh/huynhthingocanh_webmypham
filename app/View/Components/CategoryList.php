<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Category;
class CategoryList extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $category_list = Category::select('id', 'name','slug')->where('status', '=', 1)->orderBy('sort_order')->get();
        return view('components.category-list', compact('category_list'));
    }
}
