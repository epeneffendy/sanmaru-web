<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\VendorStoreRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;


use Illuminate\Support\MessageBag;
use App\Exports\VendorsExport;
use App\Http\Requests\ImportExcelRequest;
use App\Imports\VendorsImport;
use App\Models\User;
use App\Services\UserService;
use App\Services\VendorService;
use Exception;

class VendorController extends Controller
{
    private $page = [
        "parent" => "shop",
        "child" => "vendor"
    ];

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if (!empty($request->input('name'))) {
            $name = $request->input('name');
            $data = Vendor::whereRaw("LOWER(name) like '%" . $name . "%")->get();
        } else {
            $data = Vendor::get();
        }

        $params = [
            'data' => $data,
            'nav' => $this->page
        ];

        return view('administrator/vendor/list', $params);
    }

    public function add(Request $request)
    {
        $params = [
            'vendor' => '',
            'nav' => $this->page
        ];

        return view('administrator/vendor/add', $params);
    }

    public function insert(VendorStoreRequest $request, UserService $userService)
    {
        try {
            $input = $request->validated();
            $userService->register(User::VENDOR, $input, null, true);
        } catch (Exception $e) {
            return redirect()->route('admin.vendor.index')->with('errors', collect(['Gagal ditambahkan']));
        }
        return redirect()->route('admin.vendor.index')->with('message', 'Berhasil ditambahkan');
    }

    public function edit($id, VendorService $vendorService)
    {
        $vendor = $vendorService->show($id);
        $params = [
            'vendor' => $vendor,
            'status' => 'edit',
            'nav' => $this->page
        ];

        return view('administrator/vendor/add', $params);
    }

    public function update(VendorStoreRequest $request, $id, VendorService $vendorService)
    {
        $input = $request->validated();
        $vendorService->update($id, $input);
        return redirect()->route('admin.vendor.index')->with('message', 'Berhasil diedit');
    }

    public function delete(Request $request, $id)
    {
        try {
            Vendor::where('id', $id)->firstOrFail()
                ->delete();
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.vendor.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }

        return redirect()->route('admin.vendor.index')->with('message', 'Berhasil dihapus');
    }

    public function export(Request $request)
    {
        $vendorsExport = new VendorsExport();
        $title = "Exports Data Vendor " . date('Y-m-d H:i:s') . ".xlsx";

        if ($request->has('template-only')) {
            $vendorsExport->setTemplate(true);
            $title = "Template Import Vendor.xlsx";
        }

        return $vendorsExport->download($title);
    }

    public function import(ImportExcelRequest $request, UserService $userService, VendorService $vendorService)
    {
        $sessionFlash = [];
        $input = $request->validated();

        $vendorsImport = new VendorsImport($userService, $vendorService);
        if ($input['type'] === 'overwrite') {
            $vendorsImport->setOverwrite(true);
        }

        $vendorsImport->import($input['file']);
        $reports = $vendorsImport->getReport();

        $sessionFlash = [
            'message' => count($reports['success']) . ' data berhasil diimport',
        ];

        if (isset($reports['failure']) && count($reports['failure'])) {
            $sessionFlash['errors'] = new MessageBag([
                'errors' => [
                    count($reports['failure']) . ' data gagal diimport<br/>' . implode('<br/>', $reports['failure'])
                ]
            ]);
        }

        return redirect()->route('admin.vendor.index')->with($sessionFlash);
    }
}
