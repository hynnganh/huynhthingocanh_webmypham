<?php


namespace App\View\Components;

use App\Models\Banner;
use Illuminate\View\Component;

class BannerList extends Component
{
    public function render(): View|Closure|string
{
    $banner_list = Banner::where('status', 1)
        ->where('position', 'slideshow') 
        ->orderBy('sort_order')
        ->get();

    return view('components.banner-list', compact('banner_list'));
}

}


