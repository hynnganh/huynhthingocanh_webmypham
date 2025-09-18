<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Topic;
use App\Http\Requests\StoreTopicRequest;
use App\Http\Requests\UpdateTopicRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $list = Topic::select('topic.id', 'topic.name', 'topic.slug', 'topic.description', 'topic.status', 'topic.created_at')
            ->orderBy('topic.created_at', 'desc')
            ->paginate(5);

        return view('backend.topic.index', compact('list'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.topic.create');
    }

    public function store(StoreTopicRequest $request)
    {
        $topic = new Topic();
        $topic->name = $request->name;
        $topic->slug = Str::of($request->name)->slug('-');
        $topic->status = $request->status;
        $topic->description = $request->description;
        $topic->created_by = Auth::id() ?? 1;

        $topic->save();

        return redirect()->route('topic.index')->with('success', 'Thêm chủ đề thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $topic = Topic::findOrFail($id);
        return view('backend.topic.show', compact('topic'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $topic = Topic::find($id);
        return view('backend.topic.edit', compact('topic'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $topic = Topic::find($id);
        $topic->name = $request->name;
        $topic->slug = Str::slug($request->name, '-');
        $topic->description = $request->description;
        $topic->status = $request->status;
        $topic->updated_by = Auth::id() ?? 1;
        $topic->updated_at = now();
        $topic->save();

        return redirect()->route('topic.index')->with('success', 'Cập nhật chủ đề thành công');
    }

    public function trash()
    {
        $list = Topic::select('topic.id', 'topic.name', 'topic.slug', 'topic.description', 'topic.status', 'topic.created_at')
            ->orderBy('topic.created_at', 'desc')
            ->onlyTrashed()
            ->paginate(5);

        return view('backend.topic.trash', compact('list'));
    }

    public function delete(string $id)
    {
        $topic = Topic::find($id);
        $topic->delete(); // soft delete
        return redirect()->route('topic.index')->with('success', 'Đã chuyển chủ đề vào thùng rác');
    }

    public function restore($id)
    {
        $topic = Topic::onlyTrashed()->find($id);
        $topic->restore(); // khôi phục
        return redirect()->route('topic.trash')->with('success', 'Khôi phục chủ đề thành công');
    }

    public function destroy(string $id)
    {
        $topic = Topic::onlyTrashed()->find($id);
        $topic->forceDelete();
        return redirect()->route('topic.trash')->with('success', 'Xóa vĩnh viễn chủ đề thành công');
    }

    public function status($id)
    {
        $topic = Topic::find($id);
        $topic->status = ($topic->status == 1) ? 0 : 1;
        $topic->updated_by = Auth::id() ?? 1;
        $topic->updated_at = now();
        $topic->save(); // lưu cập nhật

        return redirect()->route('topic.index')->with('success', 'Cập nhật trạng thái chủ đề thành công');
    }
}
