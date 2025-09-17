<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Menu;

class MainMenu extends Component
{
  
    public function __construct()
    {
        //
    }

 
    public function render():View|Closure|string
    {
        $agrs = [
            ['parent_id','=',0],
            ['position','=','mainmenu'],
            ['status' ,'=', 1],
        ];
        $menu_list = Menu::where($agrs)->orderBy('sort_order', 'asc')->get();

        return view('components.main-menu', compact('menu_list'));
    }
}