<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TeacherStoreRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\User;

use Illuminate\Support\MessageBag;
use App\Exports\TeachersExport;
use App\Http\Requests\TeacherImportRequest;
use App\Imports\TeachersImport;
use App\Services\TeacherService;
use App\Services\UserService;
use Exception;

class TeacherController extends Controller
{
    private $page = [
        "parent" => "master",
        "child" => "teacher"
    ];

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if (!empty($request->input('name'))) {
            $name = $request->input('name');
            $teachers = Teacher::whereRaw("LOWER(name) like '%" . $name . "%")->with('user')->get();
        } else {
            $teachers = Teacher::with('user')->get();
        }

        $data = [
            'data' => $teachers,
            'nav' => $this->page
        ];

        return view('administrator/teacher/list', $data);
    }

    public function add(Request $request)
    {
        $data = [
            'nav' => $this->page
        ];

        return view('administrator/teacher/add', $data);
    }

    public function insert(TeacherStoreRequest $request, UserService $userService)
    {
        try{
            $userService->register(User::TEACHER, $request->validated(), null, true);
            return redirect()->route('admin.teacher.index')->with('message', 'Berhasil ditambahkan');
        } catch(Exception $e)
        {
            return redirect()->route('admin.teacher.index')->with('errors', collect(['Gagal ditambahkan']));
        }
    }

    public function edit(Request $request, $id)
    {
        try {
            $teacher = Teacher::findOrFail($id);
        } catch (\Exception $e) {
            abort(404);
        }

        $data = array(
            'userList' => User::roleGuru()->pluck('username', 'id'),
            'teacher' => $teacher,
            'nav' => $this->page,
            'method' => 'edit'
        );

        return view('administrator/teacher/add', $data);
    }

    public function update(TeacherStoreRequest $request, $id, TeacherService $teacherService)
    {
        $input = $request->validated();
        $teacherService->update($id, $input);
        return redirect()->route('admin.teacher.index')->with('message', 'Berhasil diedit');
    }

    public function delete(Request $request, $id)
    {
        try {
            Teacher::where('id', $id)->firstOrFail()->delete();
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.teacher.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }

        return redirect()->route('admin.teacher.index')->with('message', 'Berhasil dihapus');
    }

    public function export(Request $request)
    {
        $teachersExport = new TeachersExport();
        $title = "Exports Data Teacher " . date('Y-m-d H:i:s') . ".xlsx";

        if ($request->has('template-only')) {
            $teachersExport->setTemplate(true);
            $title = "Template Import Teacher.xlsx";
        }

        return $teachersExport->download($title);
    }

    public function import(
        TeacherImportRequest $request,
        UserService $userService,
        TeacherService $teacherService
    ) {
        $sessionFlash = [];
        $input = $request->validated();
        $teachersImport = new TeachersImport($userService, $teacherService);
        if ($input['type'] === 'overwrite') {
            $teachersImport->setOverwrite(true);
        }

        $teachersImport->import($input['file']);
        $reports = $teachersImport->getReport();

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
        return redirect()->route('admin.teacher.index')->with($sessionFlash);
    }
}
