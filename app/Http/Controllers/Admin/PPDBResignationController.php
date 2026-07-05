<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PPDBResignationStoreRequest;
use App\Models\PPDBResignation;
use App\Models\PPDBUser;
use App\Models\Unit;
use App\Models\User;
use App\Models\StudentBills;
use App\Services\PPDBResignationService;
use DB;

class PPDBResignationController extends Controller
{
    private $page = [
        'parent' => 'ppdb',
        'child' => 'ppdb-resignation'
    ];

    public function index(Request $request, PPDBResignationService $ppdbResignationService){
        $data = $ppdbResignationService->generateIndexData($request, $this->page);

        return view('administrator.ppdb-resignation.list', $data);
    }

    public function add(Request $request, PPDBResignationService $ppdbResignationService){
        $start_year = date('Y') - 1;
        $school_year = [];
        for($start_year; $start_year <= date('Y'); $start_year++){
            $school_year[] = $start_year;
        }

        $data = [
            'nav' => $this->page,
            'units' => Unit::byUserRole()->get(),
            'school_year' => $school_year,
        ];

        return view('administrator.ppdb-resignation.add', $data);
    }

    public function store(PPDBResignationStoreRequest $request, PPDBResignationService $ppdbResignationService){
        DB::beginTransaction();
        try {
            $data = $request->validated();

            if ($request->filled('id')) {
                $store = $ppdbResignationService->updateResignation($request->id, $request->all(), $data);
                if ($store['success'] == true) {
                    DB::commit();
                    return redirect()->route('admin.ppdb-resignation.index')->with(['message' => 'Pengajuan Pengunduran Diri Berhasil Diubah', 'success' => true]);
                } else {
                    DB::rollBack();
                    return redirect()->route('admin.ppdb-resignation.index')->with(['message' => $store['message'], 'success' => false])->withErrors(new \Illuminate\Support\MessageBag());
                }
            } else {
                $store = $ppdbResignationService->store($request->all(), $data);
                if ($store['success'] == true) {
                    DB::commit();
                    return redirect()->route('admin.ppdb-resignation.index')->with(['message' => 'Pengajuan Pengunduran Diri Berhasil Disimpan', 'success' => true]);
                } else {
                    DB::rollBack();
                    return redirect()->route('admin.ppdb-resignation.index')->with(['message' => $store['message'], 'success' => false])->withErrors(new \Illuminate\Support\MessageBag());
                }
            }

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('errors', collect([$th->getMessage()]))->withInput();
        }
    }

    public function fetchStudent(Request $request)
    {
        $users = PPDBUser::with('user')
            ->where('school_year', $request->school_year)
            ->where('unit_id', $request->unit_id)
            ->whereHas('user', function($query) {
                $query->where('type', 'ppdb');
            })
            ->get();
        $collections = collect();
        foreach ($users as $user) {
            $collections->put($user->id, '[' . $user->register_number . '] ' . $user->name);
        }

        return $collections;
    }

    public function edit(Request $request, $id){
        $start_year = date('Y') - 1;
        $school_year = [];
        for($start_year; $start_year <= date('Y'); $start_year++){
            $school_year[] = $start_year;
        }

        $ppdb = PPDBResignation::findOrFail($id);

        $data = [
            'nav' => $this->page,
            'units' => Unit::byUserRole()->get(),
            'school_year' => $school_year,
            'status' => 'edit',
            'ppdb' => $ppdb,
            'arr_student' => $ppdb->ppdb_user_id
        ];

        return view('administrator.ppdb-resignation.add', $data);
    }

    public function approve(Request $request, $id){
        DB::beginTransaction();
        try {
            $ppdb = PPDBResignation::findOrFail($id);
            $ppdb->status = 'approved';
            $ppdb->user_id = auth()->id();
            $ppdb->save();

            $ppdbUser = PPDBUser::findOrFail($ppdb->ppdb_user_id);
            $ppdbUser->status = PPDBUser::STATUS_CANCELED;
            $ppdbUser->save();

            $user = User::findOrFail($ppdbUser->user_id);
            $user->status = 'inactive';
            $user->save();

            $studentBills = StudentBills::where('ppdb_user_id', $ppdb->ppdb_user_id)->get();
            if ($studentBills->isNotEmpty()) {
                foreach ($studentBills as $bill) {
                    $bill->payment_method = StudentBills::PAYMENT_METHOD_CLOSED;
                    $bill->save();
                }
            }


            DB::commit();
            return redirect()->back()->with(['message' => 'Status berhasil diubah menjadi Approved', 'success' => true]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('errors', collect([$th->getMessage()]));
        }
    }
}