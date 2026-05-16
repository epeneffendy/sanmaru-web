<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\FinanceSystemConfigurationRequest;
use App\Models\Finance;
use App\Services\FinanceSystemConfigurationService;

class FinanceSystemConfigurationController extends Controller
{
    private $page = [
        'parent' => 'finance-configuration',
        'child' => 'system-configuration'
    ];


    public function index(FinanceSystemConfigurationService $financeSystemConfigurationService)
    {
        $configurations = $financeSystemConfigurationService->get();
        return view('administrator.finance-system-configuration.list', [
            'nav' => $this->page,
            'configurations' => $configurations
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
}
