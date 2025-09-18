<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Post;
use App\Models\Topic;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Support\Facades\File;

class PostController extends Controller
{
    public function index()
    {
        $list = Post::select('post.id', 'post.title','post.slug','post.detail', 'topic.name as topic_name', 'thumbnail', 'post.status')
            ->join('topic', 'post.topic_id', '=', 'topic.id') 
            ->orderBy('post.created_at', 'desc')
            ->paginate(5);

        return view('backend.post.index', compact('list'));
    }

    public function show($id)
    {
        $post = Post::findOrFail($id);
        return view('backend.post.show', compact('post'));
    }

    public function create()
    {
        $list_topic = Topic::select('id', 'name')->get();
        return view('backend.post.create', compact('list_topic'));
    }

    public function store(StorePostRequest $request)
    {
        $post = new Post();
        $post->title = $request->title;
        $post->slug = Str::of($request->title)->slug('-');
        $post->detail = $request->detail;
        $post->description = $request->description;
        $post->topic_id = $request->topic_id;

        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $extension = $file->getClientOriginalExtension();
            $filename = $post->slug . '.' . $extension;
            $file->move(public_path('assets/images/post'), $filename);
            $post->thumbnail = $filename;
        }

        $post->status = $request->status;
        $post->created_at = now();
        $post->created_by = Auth::id() ?? 1;
        $post->save();

        return redirect()->route('post.index')->with('success', 'Thêm bài viết thành công');
    }

    public function edit(string $id)
    {
        $post = Post::find($id);
        $list_topic = Topic::select('id', 'name')->get();
        return view('backend.post.edit', compact('list_topic', 'post'));
    }

    public function update(UpdatePostRequest $request, string $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return redirect()->route('post.index')->with('error', 'Không tìm thấy bài viết!');
        }

        $slug = Str::of($request->title)->slug('-');
        $post->title = $request->title;
        $post->slug = $slug;
        $post->description = $request->description;
        $post->detail = $request->detail;
        $post->topic_id = $request->topic_id;

        $image_path = public_path('assets/images/post/' . $post->thumbnail);
        if ($request->hasFile('thumbnail')) {
            if (File::exists($image_path)) {
                File::delete($image_path);
            }

            $file = $request->file('thumbnail');
            $extension = $file->getClientOriginalExtension();
            $filename = $slug . '.' . $extension;
            $file->move(public_path('assets/images/post'), $filename);
            $post->thumbnail = $filename;
        }

        $post->status = $request->status;
        $post->updated_by = Auth::id() ?? 1;
        $post->updated_at = now();
        $post->save();

        return redirect()->route('post.index')->with('success', 'Cập nhật bài viết thành công');
    }

    public function delete($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return redirect()->route('post.index')->with('error', 'Không tìm thấy bài viết!');
        }

        $post->delete(); // soft delete
        return redirect()->route('post.index')->with('success', 'Đã chuyển bài viết vào thùng rác');
    }

    public function trash()
    {
        $list = Post::onlyTrashed()
            ->select('post.id', 'post.title','post.slug','post.detail', 'topic.name as topic_name', 'thumbnail', 'post.status')
            ->join('topic', 'post.topic_id', '=', 'topic.id') 
            ->orderBy('post.created_at', 'desc')
            ->paginate(5);

        return view('backend.post.trash', compact('list'));
    }

    public function restore($id)
    {
        $post = Post::onlyTrashed()->find($id);
        if (!$post) {
            return redirect()->route('post.trash')->with('error', 'Không tìm thấy bài viết!');
        }

        $post->restore();
        return redirect()->route('post.trash')->with('success', 'Khôi phục bài viết thành công');
    }

    public function destroy(string $id)
    {
        $post = Post::onlyTrashed()->find($id);
        if (!$post) {
            return redirect()->route('post.trash')->with('error', 'Không tìm thấy bài viết!');
        }

        $image_path = public_path('assets/images/post/' . $post->thumbnail);
        if ($post->forceDelete()) {
            if (File::exists($image_path)) {
                File::delete($image_path);
            }
        }

        return redirect()->route('post.trash')->with('success', 'Xóa vĩnh viễn bài viết thành công');
    }

    public function status($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return redirect()->route('post.index')->with('error', 'Không tìm thấy bài viết!');
        }

        $post->status = ($post->status == 1) ? 0 : 1;
        $post->updated_by = Auth::id() ?? 1;
        $post->updated_at = now();
        $post->save();

        return redirect()->route('post.index')->with('success', 'Cập nhật trạng thái thành công');
    }
}
