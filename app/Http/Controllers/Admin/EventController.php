<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\EventStoreRequest;
use App\Http\Controllers\Controller;
use App\Services\EventService;
use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    private $page = [
        "parent" => "master",
        "child" => "event"
    ];
    const PAGINATE_LIMIT = 20;

    public function index(Request $request)
    {
        if (!empty($request->input('name'))) {
            $name = $request->input('name');
            $events = Event::whereRaw("LOWER(title) like '%" . $name . "'")->orderBy('event_time', 'DESC')->get();
        } else {
            $events = Event::orderBy('event_time', 'DESC')->get();
        }

        $data = [
            'events' => $events,
            'nav' => $this->page
        ];

        return view('administrator/event/list', $data);
    }

    public function add()
    {
        $data = [
            'event' => false,
            'nav' => $this->page
        ];

        return view('administrator/event/add', $data);
    }

    public function insert(EventStoreRequest $request, EventService $eventService)
    {
        $input = $request->validated();
        $eventService->create($input, $request->user()->id);
        return redirect()->route('admin.event.index')->with('message', "Event '{$input['title']}' Berhasil ditambahkan");
    }

    public function edit($id, EventService $eventService)
    {
        $data = $eventService->generateEditableData($id, $this->page);
        return view('administrator/event/add', $data);
    }

    public function update(EventStoreRequest $request, $id, EventService $eventService)
    {
        $input = $request->validated();
        $eventService->update($id, $input, $request->user()->id);
        return redirect()->route('admin.event.index')->with('message', "Event '{$input['title']}' Berhasil diedit");
    }

    public function toggle(Request $request, $id, EventService $eventService)
    {
        $eventService->toggleStatus($id, $request->user()->id);
        return redirect()->route('admin.event.index')->with('message', "Status Event id '{$id}' Berhasil diubah");
    }

    public function delete($id, EventService $eventService)
    {
        $eventService->delete($id);
        return redirect()->route('admin.event.index')->with('message', "Event id '{$id}' Berhasil dihapus");
    }
}
