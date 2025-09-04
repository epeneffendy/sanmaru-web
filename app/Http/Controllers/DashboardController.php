<?php

namespace App\Http\Controllers;

use App\Models\Parents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function welcome()
    {
        $data = array(
            'nav' => ['parent' => 'home', 'child'=>'Home'],
        );

        return view('administrator.student-welcome', $data);
    }
}
