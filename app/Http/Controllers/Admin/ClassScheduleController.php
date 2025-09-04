<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClassScheduleStoreRequest;
use App\Services\ClassScheduleService;


class ClassScheduleController extends Controller
{
    protected $page = [
        'parent' => 'master',
        'child' => 'class-schedule'
    ];

    public function index(ClassScheduleService $classScheduleService)
    {
        $data = $classScheduleService->generateIndexData($this->page);
        return view('administrator.class-schedule.list', $data);
    }

    public function add(ClassScheduleService $classScheduleService)
    {
        $data = $classScheduleService->generateAddingData($this->page);
        return view('administrator.class-schedule.add', $data);
    }

    public function insert(ClassScheduleStoreRequest $request, ClassScheduleService $classScheduleService)
    {
        $classSchedule = $classScheduleService->create($request->validated());
        return redirect(route('admin.class-schedule.index'))->withMessage('berhasil ditambahkan');
    }

    public function edit($id, ClassScheduleService $classScheduleService)
    {
        $data = $classScheduleService->generateEditableData($id, $this->page);
        return view('administrator.class-schedule.add', $data);
    }

    public function update($id, ClassScheduleStoreRequest $request, ClassScheduleService $classScheduleService)
    {
        $classSchedule = $classScheduleService->update($id, $request->validated());
        return redirect(route('admin.class-schedule.index'))->withMessage('berhasil diedit');
    }

    public function delete($id, ClassScheduleService $classScheduleService)
    {
        $classScheduleService->delete($id);
        return redirect(route('admin.class-schedule.index'))->withMessage('berhasil dihapus');
    }

    public function unitClass($unitId, ClassScheduleService $classScheduleService)
    {
        $data = $classScheduleService->unitClass($unitId);
        return response()->json($data, 200);
    }

    public function calendarData($classId, ClassScheduleService $classScheduleService)
    {
        $data = $classScheduleService->calendarData($classId);
        $view = view('administrator.class-schedule._schedule-table', $data)->render();
        return response()->json(['html' => $view], 200);
    }
}
