<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\CampusUnit;
use App\Models\VoiceOfSanmar;
use Illuminate\Http\Request;
use App\Models\Testimonial;
use App\Models\Gallery;
use App\Models\Blog;
use App\Models\Unit;
use App\Models\Headline;
use App\Models\Popup;
use App\Services\WebviewService;

class HomeController extends Controller
{
    private $page = ['parent' => 'home', 'child'=>''];

    public function index(WebviewService $webviewService)
    {   

        $nav = $webviewService->getNavigations($this->page);
        $data = [
            'nav'   => $nav,
            'blogs' => Blog::whereNull('unit_id')->published()->orderBy('created_at', 'desc')
                        ->take(9)->get(['title','slug','featured_image','short_desc','created_at']),
            'testimonials' => Testimonial::whereNull('unit_id')->published()->orderBy('created_at', 'desc')
                        ->take(9)->get(['subject','photo_path','content']),
            'galleries' => Gallery::whereNull('unit_id')->published()
                        ->take(18)->get(['title','content_url']),
            'voices' => VoiceOfSanmar::orderBy('updated_at', 'DESC')->take(6)
                        ->get(['title','content_url']),
            'units' => CampusUnit::with('unit')->orderBy('unit_id')->get(),
            'headlines' => Headline::where('is_unit',false)->published()->latest()->get(),
            'popups' => Popup::whereNull('unit_id')->published()->orderBy('created_at', 'desc')->get(),
        ];

        return view('webview.home.home', $data);
    }
}
