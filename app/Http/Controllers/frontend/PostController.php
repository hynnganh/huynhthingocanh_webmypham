<?php
namespace App\Http\Controllers\frontend;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function show($id)
{
    $post = Post::findOrFail($id); 
    $relatedPosts = Post::where('topic_id', $post->topic_id)
                        ->where('id', '!=', $post->id)
                        ->where('thumbnail', '!=', $post->thumbnail)
                        ->where('status', '!=', 0)
                        ->limit(5)
                        ->get();

    return view('frontend.post.show', compact('post', 'relatedPosts'));
}

public function index()
{
    $posts = Post::latest()
                ->where('status', '!=', 0)
                ->paginate(6);
    return view('frontend.post.index', compact('posts')); 
}

}
