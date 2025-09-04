<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\WebviewService;
use App\Models\Campus;
use App\Models\CampusUnit;

class CampusesController extends Controller
{
    private $page = ['parent' => 'campuses', 'child' => ''];

    public function index(WebviewService $webviewService)
    {
        $nav = $webviewService->getNavigations($this->page);
        $campuses = Campus::with('campusUnits', 'campusUnits.unit')->whereHas('campusUnits')->get();
        $data = [
            'nav' => $nav,
            'campuses' => $campuses
        ];
        return view('webview.campuses.index', $data);
    }

    public function showCampusUnit($campusUnitId)
    {
        $campusUnit = CampusUnit::with('unit')->where('id', $campusUnitId)->firstOrFail();
        return view('webview.campuses._modal-content', ['campusUnit' => $campusUnit]);
    }
}
