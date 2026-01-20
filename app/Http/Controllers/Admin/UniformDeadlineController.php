<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UniformDeadlineRequest;
use App\Models\Unit;
use App\Services\UniformDeadlineService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UniformDeadlineController extends Controller
{
    private $page = [
        'parent' => 'shop',
        'child' => 'uniform-payment'
    ];

    public function index(UniformDeadlineService $deadlineService)
    {
        $deadlines = $deadlineService->get();
        return view('administrator.uniform-deadline.list', [
            'nav' => $this->page,
            'units' => Unit::byUserRole()->get(),
            'deadlines' => $deadlines
        ]);
    }

    public function add(Request $request)
    {
        $params = [
            'units' => Unit::byUserRole()->get(),
            'nav' => $this->page
        ];

        return view('administrator/uniform-deadline/add', $params);
    }

    public function store(UniformDeadlineRequest $request, UniformDeadlineService $deadlineService)
    {
        try {
            $input = $request->validated();
            $validateDeadline = $deadlineService->validateDeadline($input);
            if ($validateDeadline){
                $deadlineService->create($input);
            }else{
                return redirect()->route('admin.uniform-deadline.index')->with('errors', collect(['Gagal ditambahkan, Data di tahun ajaran ' .$input['school_year']. ' sudah ada']));
            }

        } catch (Exception $e) {
            return redirect()->route('admin.uniform-deadline.index')->with('errors', collect(['Gagal ditambahkan']));
        }
        return redirect()->route('admin.uniform-deadline.index')->with('message', 'Berhasil ditambahkan');
    }

    public function edit($id, UniformDeadlineService $uniformDeadlineService)
    {

        $deadline = $uniformDeadlineService->findById($id);

        $params = [
            'deadline' => $deadline,
            'status' => 'edit',
            'nav' => $this->page,
            'units' => Unit::byUserRole()->get(),
        ];

        return view('administrator/uniform-deadline/add', $params);
    }

    public function update(UniformDeadlineRequest $request, $id, UniformDeadlineService $uniformDeadlineService)
    {
        $input = $request->validated();

        $uniformDeadlineService->update($id, $input);
        return redirect()->route('admin.uniform-deadline.index')->with('message', 'Berhasil diedit');
    }

    public function delete($id, UniformDeadlineService $uniformDeadlineService)
    {

        $data = $uniformDeadlineService->findById($id);
        $input['unit_id'] = $data->unit_id;
        $input['school_year'] = $data->school_year;
        $input['uniform_payment_deadline'] = $data->uniform_payment_deadline;
        $input['status'] = 0;

        $uniformDeadlineService->update($id, $input);
        return redirect()->route('admin.uniform-deadline.index')->with('message', 'Berhasil diedit');
    }
}
