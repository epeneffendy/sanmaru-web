<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;

class UserActivityController extends Controller
{
    private $page = [
    	"parent" => "konten",
    	"child" => "user-activity"
    ];

    public function index(Request $request)
    {
    	$contents = ['About', 'AboutCategory' , 'Blog', 'BlogCategory', 'CampusUnit', 'Campus', 
    		'Faq', 'Gallery', 'Headline', 'Scholarship', 'SchoolLife', 'SchoolLifeCategory',
    		'Testimonial', 'VoiceOfSanmar', 'User', 'Popup'];

    	$logs = ActivityLog::select('activity_logs.*', 'users.username')
    					->leftJoin('users', 'users.id', 'activity_logs.user_id')
    					->whereIn('model_type', $contents);

    	if ($request->input('username')) {
    		$logs = $logs->where('users.username', 'like', '%'.$request->input('username').'%');
		}

    	if ($request->input('model_type')) {
			$logs = $logs->where('model_type', $request->input('model_type'));
		}

		if ($request->input('model_id')) {
			$logs = $logs->where('model_id', $request->input('model_id'));
		}

    	$logs = $logs->orderBy('id', 'desc')->paginate(50);

    	$data = [
			'nav' => $this->page,
			'modelTypes' => $contents,
			'params' => $request->all(),
    		'data' => $logs
    	];

    	return view('administrator.user-activity.list', $data);
    }
}
