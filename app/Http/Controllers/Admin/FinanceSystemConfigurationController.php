<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\FinanceSystemConfigurationRequest;
use App\Models\Finance;
use App\Services\FinanceSystemConfigurationService;
use App\Services\FinancePeriodeService;

class FinanceSystemConfigurationController extends Controller
{
    private $page = [
        'parent' => 'finance-configuration',
        'child' => 'system-configuration'
    ];


    public function index(FinanceSystemConfigurationService $financeSystemConfigurationService, FinancePeriodeService $financePeriodeService)
    {
        $periode = $financePeriodeService->get();
        $configurations = $financeSystemConfigurationService->get();
        return view('administrator.finance-system-configuration.list', [
            'nav' => $this->page,
            'configurations' => $configurations,
            'periode'=>$periode
        ]);
    }

    public function add(Request $request)
    {
        $params = [
            'nav' => $this->page
        ];

        return view('administrator/finance-system-configuration/add', $params);
    }

    public function store(FinanceSystemConfigurationRequest $request, FinanceSystemConfigurationService $financeSystemConfigurationService)
    {
        try {
            $input = $request->validated();
            $financeSystemConfigurationService->create($input);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->route('admin.system-configuration.index')->with('errors', collect(['Gagal ditambahkan']));
        }
        return redirect()->route('admin.system-configuration.index')->with('message', 'Berhasil ditambahkan');
    }

    public function edit($id, FinanceSystemConfigurationService $financeSystemConfigurationService)
    {

        $configuration = $financeSystemConfigurationService->findById($id);

        $params = [
            'configuration' => $configuration,
            'status' => 'edit',
            'nav' => $this->page,
        ];

        return view('administrator/system-configuration/add', $params);
    }

    public function update(FinanceSystemConfigurationRequest $request, $id, FinanceSystemConfigurationService $financeSystemConfigurationService)
    {
        $input = $request->validated();

        $uniformDeadlineService->update($id, $input);
        return redirect()->route('admin.system-configuration.index')->with('message', 'Berhasil diedit');
    }

    public function financePeriode(Request $request)
    {
        try {
            $periodes = $request->input('periode', []);
            foreach ($periodes as $item) {
                $type = $item['type'] ?? '';
                $startDate = $item['date_start'] ?? null;
                $endDate = $item['date_end'] ?? null;
                $status = $item['status'] ?? 'inactive';
                if ($type) {
                    \App\Models\FinancePeriode::where('type', $type)->update([
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'status' => $status
                    ]);
                }
            }
            return response()->json(['success' => true, 'message' => 'Berhasil diupdate']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal diupdate']);
        }
    }
}
