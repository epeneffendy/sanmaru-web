<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CustomFormExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomFormRequest;
use App\Services\NotificationService;
use App\Http\Requests\NotificationRequest;
use App\Models\CustomForm;
use App\Models\Period;
use App\Models\Unit;
use App\Services\CustomFormService;
use App\Services\PeriodService;
use App\Services\PPDBUserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class CustomFormController extends Controller
{
    private $page = [
        "parent" => "ppdb",
        "child" => "custom-form"
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, CustomFormService $customFormService)
    {
        $data = [
            'nav' => $this->page,
            'units' => Unit::byUserRole()->get(),
            'periods' => Period::byUserRole()->get(),
            'customForms' => $customFormService->filter($request->all()),
            'params' => $request->all(),
        ];

        return view('administrator.custom_form.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'nav' => $this->page,
            'units' => Unit::byUserRole()->get()->pluck('name', 'id'),
            'periods' => Period::byUserRole()->get()->pluck('name', 'id'),
            'customForm' => new CustomForm(),
        ];

        return view('administrator.custom_form.add', $data);
    }

    /**
     * Store a newly created resourcein storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomFormRequest $request, CustomFormService $customFormService)
    {
        DB::beginTransaction();

        try {
            $customForm = $customFormService->create($request->validated());
            $customFormService->syncPeriod($customForm, $request->validated());
            $customFormService->syncCustomColumn($customForm, $request->all());
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return Redirect::route('admin.custom_form.create')->with('message', $th->getMessage())->withInput($request->validated());
        }

        return Redirect::route('admin.custom_form.index')->with('success', 'Berhasil menyampaikan informasi yang sudah dibuat');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id, CustomFormService $customFormService)
    {
        $customForm = $customFormService->getById($id);

        $data = [
            'nav' => $this->page,
            'units' => Unit::byUserRole()->get()->pluck('name', 'id'),
            'periods' => Period::byUserRole()->get()->pluck('name', 'id'),
            'customForm' => $customForm,
        ];

        return view('administrator.custom_form.add', $data);
    }

    /**
     * Update a newly created resourcein storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(CustomFormRequest $request, $id, CustomFormService $customFormService)
    {
        DB::beginTransaction();

        try {
            $customForm = $customFormService->update($id, $request->validated());
            $customFormService->syncPeriod($customForm, $request->validated());
            $customFormService->syncCustomColumn($customForm, $request->all());
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return Redirect::route('admin.custom_form.edit', $id)->with('message', $th->getMessage())->withInput($request->validated());
        }

        return Redirect::route('admin.custom_form.index')->with('success', 'Berhasil menyampaikan informasi yang sudah dibuat');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $customForm = CustomForm::with('columnInputs', 'columnInputs.ppdb_user', 'periods', 'unit')->find($id);

        $data = [
            'status' => 'show',
            'customForm' => $customForm,
            'nav' => $this->page,
            'params' => $request->only(['period'])
        ];

        return view('administrator.custom_form.show', $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customForm = CustomForm::find($id);
        $customForm->delete();

        return redirect()->route('admin.custom_form.index')->with('message', 'Custom form berhasil dihapus');
    }

    public function getPeriods($unit)
    {
        Unit::byUserRole()->where('id', $unit)->firstOrFail();
        $periods = Period::where('unit_id', $unit)->select('id', 'name')->get();

        return response()->json($periods);
    }

    public function export(Request $request)
    {
        $customForm = CustomForm::where('id', $request->id)->firstOrFail();
        $customFormExport = new CustomFormExport($request->id, $request->only(['period']));

        $title = "Exports Custom Form " . strtoupper($customForm->name) .' - '. date('Y-m-d H:i:s') . ".xlsx";

        return $customFormExport->download($title);
    }
}
