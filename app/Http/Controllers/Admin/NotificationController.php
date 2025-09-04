<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use App\Http\Requests\NotificationRequest;
use App\Services\PeriodService;
use App\Services\PPDBUserService;
use Illuminate\Support\Facades\Redirect;

class NotificationController extends Controller
{
    private $page = [
        "parent" => "ppdb",
        "child" => "notification"
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(NotificationService $notificationsService) {
        $data = [
            'notifications' => $notificationsService->filter(),
            'nav' => $this->page
        ];

        return view('administrator.notifications.list', $data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(NotificationService $notificationsService)
    {
        $data = $notificationsService->getDataFormCreate();
        $data['nav'] = $this->page;

        return view('administrator.notifications.add', $data);
}

    /**
     * Store a newly created resourcein storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NotificationRequest $request, NotificationService $notificationsService)
    {
        $notificationsService->create($request->validated());

        return Redirect::route('admin.notification.index')->with('success', 'Berhasil menyampaikan informasi yang sudah dibuat');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = [
            'status'    => 'show',
            'nav'       => $this->page
        ];

        return view('administrator.notifications.show', $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {

        return redirect()->route('admin.notification.index')->with('message', 'Notifications berhasil dihapus');
    }

    public function fetchPeriod(Request $request, PeriodService $periodService)
    {
        $periods = $periodService->filter($request->all(), null, ['unit']);

        return $periods;
    }

    public function fetchPpdbUser(Request $request, PPDBUserService $ppdbUserService)
    {
        $ppdbUsers = $ppdbUserService->filter($request->all(), null, [], ['id', 'name']);

        return $ppdbUsers;
    }
}
