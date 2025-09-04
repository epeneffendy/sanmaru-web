<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StudentStoreRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Classes;
use App\Models\Unit;
use App\Models\User;

use Illuminate\Support\MessageBag;
use App\Exports\StudentsExport;
use App\Http\Requests\StudentImportRequest;
use App\Imports\StudentsImport;
use App\Services\ImageService;
use App\Services\StudentService;
use App\Services\UserService;

class StudentController extends Controller
{
    private $page = [
        "parent" => "master",
        "child" => "student"
    ];

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, StudentService $studentService)
    {
        $scopes = [
            'name' => 'Nama Siswa',
            'nis' => 'Nomor Induk Siswa',
        ];

        $related = [
            'user',
            'class',
            'class.unit',
        ];
        $students = $studentService->filter($request->all(), 20, $related);
        $data = [
            'students' => $students,
            'nav' => $this->page,
            'params' => $request->only(['search', 'scope', 'unit', 'year']),
            'scopes' => $scopes,
            'units' => Unit::all(['id', 'name']),
            'years' => $studentService->getAvailableYears(),
        ];

        return view('administrator.student.list', $data);
    }

    public function show($id, StudentService $studentService)
    {
        $data = [
            'student' => $studentService->show($id),
            'nav' => $this->page
        ];
        return view('administrator/student/show', $data);
    }

    public function add()
    {
        $data = [
            // 'paymentList' => PaymentAgreement::pluck('name', 'id'),
            'classList' => Classes::withUnit()->with('unit')->get(),
            'statuses' => Student::getAvailableStatuses(),
            'nav' => $this->page,
        ];

        return view('administrator.student.add', $data);
    }

    public function insert(
        StudentStoreRequest $request,
        UserService $userService,
        StudentService $studentService,
        ImageService $imageService
    ) {
        $input = $request->validated();
        $student = $studentService->register($input);

        if (!$student) {
            $sessionFlash['errors'] = new MessageBag([
                'errors' => [
                    'gagal menambahkan !'
                ]
            ]);
            return redirect()->back()->with($sessionFlash);
        }

        if (isset($input['image']) && $input['image'] !== null) {
            $studentService->uploadImage($imageService, $student, $input['image']);
        }

        return redirect()->route('admin.student.index')->with('message', 'Berhasil ditambahkan');
    }

    public function edit($id, StudentService $studentService)
    {
        $data = $studentService->generateEditableData($id, $this->page);
        return view('administrator/student/add', $data);
    }

    public function update(
        StudentStoreRequest $request,
        $id,
        StudentService $studentService,
        ImageService $imageService
    ) {
        $input = $request->validated();
        $student = $studentService->update($id, $input);
        if (!$student) {
            $sessionFlash['errors'] = new MessageBag([
                'errors' => [
                    'gagal edit data!'
                ]
            ]);
            return redirect()->back()->with($sessionFlash);
        }
        if (isset($input['image']) && $input['image'] !== null) {
            $studentService->uploadImage($imageService, $student, $input['image']);
        }
        return redirect()->route('admin.student.index')->with('message', 'Berhasil diedit');
    }

    public function delete(Request $request, $id)
    {
        try {
            Student::where('id', '=', $id)->delete();
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.student.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }

        return redirect()->route('admin.student.index')->with('message', 'Berhasil dihapus');
    }

    public function export(Request $request)
    {
        $studentsExport = new StudentsExport($request->all());
        $title = "Exports Data Student " . date('Y-m-d H:i:s') . ".xlsx";

        if ($request->has('template-only')) {
            $studentsExport->setTemplate(true);
            $title = "Template Import Student.xlsx";
        }

        return $studentsExport->download($title);
    }

    public function import(
        StudentImportRequest $request,
        UserService $userService,
        StudentService $studentService
    ) {
        // BANDAID SOLUTION FOR https://aimsis.atlassian.net/browse/AIMSIS-10509
        // RESOLVE IN THE FUTURE IMMEDIATELY
        ini_set('max_execution_time', '60');
        $sessionFlash = [];
        $input = $request->validated();
        $studentsImport = new StudentsImport($userService, $studentService);
        if ($input['type'] === 'overwrite') {
            $studentsImport->setOverwrite(true);
        }

        $studentsImport->import($input['file']);
        $reports = $studentsImport->getReport();

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
        // BANDAID SOLUTION FOR https://aimsis.atlassian.net/browse/AIMSIS-10509
        // RESOLVE IN THE FUTURE IMMEDIATELY
        ini_set('max_execution_time', '30');

        return redirect()->route('admin.student.index')->with($sessionFlash);
    }
}
