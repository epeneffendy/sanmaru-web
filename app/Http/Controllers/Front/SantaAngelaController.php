<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\WebviewService;


class SantaAngelaController extends Controller
{
    private $page = ['parent' => 'santa-angela', 'child' => ''];

    public function index(WebviewService $webviewService)
    {
        $nav = $webviewService->getNavigations($this->page);
        $data =[
            'nav'   => $nav
        ];
        return view('webview.santa-angela.index', $data);
    }

    public function regula(WebviewService $webviewService)
    {
        $nav = $webviewService->getNavigations($this->page);
        $data =[
            'nav'   => $nav
        ];
        return view('webview.santa-angela.regula', $data);
    }

    public function nasehat(WebviewService $webviewService)
    {
        $nav = $webviewService->getNavigations($this->page);
        $data =[
            'nav'   => $nav
        ];
        return view('webview.santa-angela.nasehat', $data);
    }

    public function warisan(WebviewService $webviewService)
    {
        $nav = $webviewService->getNavigations($this->page);
        $data =[
            'nav'   => $nav
        ];
        return view('webview.santa-angela.warisan', $data);
    }
}
