<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('category')->get();

        return view('events.index', compact('events'));
    }

    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(StorePostRequest $request)
    {
        $data = $request->validated();

        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);
        $data['user_id'] = Auth::id();

        $data['status'] = 'published';
        $data['published_at'] = now();

        Event::create($data);

        return redirect()
            ->route('events.index')
            ->with('success', 'Tạo sự kiện thành công!');
    }

    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    public function update(UpdatePostRequest $request, Event $event)
    {
        $event->update($request->validated());

        return redirect()->route('events.index', $event)->with('success', 'Cập nhật sự kiện thành công!');
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('events.index')->with('success', 'Đã xóa sự kiện');
    }
}
