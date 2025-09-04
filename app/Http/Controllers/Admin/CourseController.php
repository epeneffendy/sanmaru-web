<?php

namespace App\Http\Controllers\Admin;

use App\Models\Unit;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Exports\CoursesExport;
use App\Imports\CoursesImport;
use App\Services\CourseService;

use function PHPSTORM_META\map;
use Illuminate\Support\MessageBag;
use App\Http\Controllers\Controller;
use App\Http\Requests\CourseStoreRequest;
use App\Http\Requests\ImportExcelRequest;
use App\Http\Requests\CourseUpdateRequest;

class CourseController extends Controller
{
    private $page = [
        "parent" => "master",
        "child" => "course"
    ];
    const PAGINATE_LIMIT = 20;

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, CourseService $courseService)
    {
        if ($request->name || $request->unit_id) {
            $courses = $courseService->filter($request->unit_id, $request->name)->with('unit')->paginate($this::PAGINATE_LIMIT);
        } else {
            $courses = $courseService->getIndex($this::PAGINATE_LIMIT);
        }
        $request->flash('');
        $data = [
            'courses' => $courses,
            'units' => Unit::all(),
            'nav' => $this->page
        ];

        return view('administrator.course.list', $data);
    }

    public function add()
    {
        $data = [
            'units' => Unit::all(),
            'nav' => $this->page
        ];

        return view('administrator.course.add', $data);
    }

    public function insert(CourseStoreRequest $request, CourseService $courseService)
    {
        $input = $request->validated();
        $courseService->create($input);
        return redirect()->route('admin.course.index')->with('message', 'Berhasil ditambahkan');
    }

    public function edit(Course $course)
    {
        $data = [
            'course' => $course,
            'units' => Unit::all(),
            'method' => 'edit',
            'nav' => $this->page
        ];
        return view('administrator.course.add', $data);
    }

    public function update(Course $course, CourseUpdateRequest $request, CourseService $courseService)
    {
        $input = $request->validated();
        $courseService->update($course->id, $input);
        return redirect()->route('admin.course.index')->with('message', 'Berhasil diedit');
    }

    public function toggle(Course $course, CourseService $courseService)
    {
        $courseService->toggleStatus($course->id);
        return redirect()->route('admin.course.edit', $course)->with('message', 'Berhasil diubah');
    }

    public function delete($id, CourseService $courseService)
    {
        $courseService->delete($id);
        return redirect()->route('admin.course.index')->with('message', 'Berhasil dihapus');
    }

    public function export(Request $request)
    {
        $coursesExport = new CoursesExport();
        $title = "Exports Data Course " . date('Y-m-d H:i:s') . ".xlsx";

        if ($request->has('template-only')) {
            $coursesExport->setTemplate(true);
            $title = "Template Import Course.xlsx";
        }

        return $coursesExport->download($title);
    }

    public function import(ImportExcelRequest $request)
    {
        $sessionFlash = [];
        $request->validated();

        try {
            $coursesImport = new CoursesImport();
            if ($request->input('type') === 'overwrite') {
                $coursesImport->setOverwrite(true);
            }

            $coursesImport->import($request->file('file'));
            $reports = $coursesImport->getReport();

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
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.course.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }

        return redirect()->route('admin.course.index')->with($sessionFlash);
    }
}
