<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UserStoreRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Unit;

use Illuminate\Support\MessageBag;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use App\Services\UserService;

class UserController extends Controller
{
    private $page = [
        "parent" => "",
        "child" => "user"
    ];

    public function index(Request $request)
    {
        $data = new User();
        if (!empty($request->input('user'))) {
            $name = $request->input('user');
            $data = $data->whereRaw("LOWER(username) like '%". strtolower($name) ."%'")->orWhereRaw("LOWER(email) like '%". strtolower($name) ."%'");
        }
        if (!empty($request->input('type'))) {
            $data = $data->where('type', $request->input('type'));
        }

        $data = $data->select('id', 'username', 'status', 'email', 'type')->paginate();

        $params = [
            'data' => $data,
            'nav' => $this->page,
            'params' => $request->all()
        ];

        return view('administrator/user/list', $params);
    }

    public function add(Request $request)
    {
        $params = [
            'data' => '',
            'units' => Unit::select('name', 'id')->pluck('name', 'id')->all(),
            'nav' => $this->page
        ];

        return view('administrator/user/add', $params);
    }

    public function insert(UserStoreRequest $request)
    {
        $input = $request->validated();

        try {
            $input['password'] = Hash::make($input['password']);
            $input['mobile_phone'] = app('phoneNormalizerService')->normalize($input['mobile_phone']);
            User::create($input);
        } catch (\Exception $e){
            return redirect()
                ->route('admin.user.add')
                ->withErrors($e->getMessage())
                ->withInput();
        }

        return redirect()->route('admin.user.index')->with('message','Berhasil ditambahkan');
    }

    public function edit(Request $request,$id)
    {
        try {
            $user = User::findOrFail($id);
        } catch (\Exception $e) {
            abort(404);
        }

        $data = [
            'user' => $user,
            'usecase' => 'update',
            'units' => Unit::select('name', 'id')->pluck('name', 'id')->all(),
            'nav' => $this->page
        ];

        return view('administrator/user/add', $data);
    }

    public function update(UserStoreRequest $request, $id)
    {
        $input = $request->validated();

        try {
            if (is_null($input['password'])) {
                unset($input['password']);
            } else {
                $input['password'] = Hash::make($input['password']);
            }
            $input['mobile_phone'] = app('phoneNormalizerService')->normalize($input['mobile_phone']);

            User::where('id', $id)->firstOrFail()
                ->update($input);

        } catch (\Exception $e) {
            return redirect()
                ->route('admin.user.edit',$id)
                ->withErrors($e->getMessage())
                ->withInput();
        }
        return redirect()->route('admin.user.index')->with('message','Berhasil diedit');
    }

    public function delete(Request $request,$id)
    {
        try {
            User::where('id','=', $id)->delete();
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.user.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }

        return redirect()->route('admin.user.index')->with('message','Berhasil dihapus');
    }

    public function export(Request $request)
    {
        // BANDAID SOLUTION FOR https://aimsis.atlassian.net/browse/AIMSIS-10513
        // RESOLVE IN THE FUTURE IMMEDIATELY
        ini_set('memory_limit', '2048M');

        $usersExport = new UsersExport($request->all());
        $title = "Exports Data User ". date('Y-m-d H:i:s'). ".xlsx";

        if ($request->has('template-only')) {
            $usersExport->setTemplate(true);
            $title = "Template Import User.xlsx";
        }

        return $usersExport->download($title);
    }

    public function import(Request $request, UserService $userService)
    {
        $sessionFlash = [];
        $input = $request->all();
        $validator = Validator::make($input, [
            'file' => ['required', 'file', 'mimes:xls,xlsx'],
            'type' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('admin.user.index')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $usersImport = new UsersImport($userService);
            if ($request->input('type') === 'overwrite') {
                $usersImport->setOverwrite(true);
            }

            $usersImport->import($request->file('file'));
            $reports = $usersImport->getReport();

            $sessionFlash = [
                'message' => count($reports['success']) .' data berhasil diimport',
            ];

            if (isset($reports['failure']) && count($reports['failure'])) {
                $sessionFlash['errors'] = new MessageBag([
                    'errors' => [
                        count($reports['failure']). ' data gagal diimport<br/>'. implode('<br/>', $reports['failure'])
                    ]
                ]);
            }

        } catch (\Exception $e){
            return redirect()
                ->route('admin.user.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }

        return redirect()->route('admin.user.index')->with($sessionFlash);
    }
}
