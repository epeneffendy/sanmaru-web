<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Services\WebviewService;

class AdmissionController extends Controller
{
    private $page = ['parent' => 'admission', 'child' => ''];

    public function beasiswa(WebviewService $webviewService){
        $nav = $webviewService->getNavigations($this->page);
        $data = [
            'nav' => $nav
        ];

        return view('webview.admission.beasiswa', $data);
    }

    public function faq(WebviewService $webviewService){
        $nav = $webviewService->getNavigations($this->page);
        $data = [
            'nav' => $nav,
            'faqs' => Faq::published()->where('category','web-school')->get()
        ];

        return view('webview.admission.faq', $data);
    }
}
