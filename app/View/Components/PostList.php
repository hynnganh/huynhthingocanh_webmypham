<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use App\Models\Post;

class PostList extends Component
{
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $posts = Post::where('status', 1)
                    ->orderBy('created_at', 'desc')
                    ->take(4)
                    ->get();

        return view('components.post-list', compact('posts'));
    }
}

