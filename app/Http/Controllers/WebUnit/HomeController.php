<?php
namespace App\Http\Controllers\WebUnit;

use App\Models\Blog;
use App\Models\Popup;
use App\Models\Gallery;
use App\Models\Headline;
use App\Models\Testimonial;

class HomeController extends BaseController
{
    public function index()
    {
        $unitIds = $this->units->pluck('id')->all();
        $unitSlug = $this->units->pluck('webunit_slug')->all();

        if ($unitSlug[0] === "smp-sby") {
            $blogs = Blog::published()->select(['title','featured_image','created_at','updated_at', 'slug'])
                        ->latest()->whereIn('unit_id', $unitIds)->take(9)->get();
        } else
        {
            $blogs = Blog::published()->select(['title','featured_image','created_at','updated_at', 'slug'])
                        ->latest()->whereIn('unit_id', $unitIds)->take(3)->get();
        }

        $testimonials = Testimonial::with('unit')->published()->latest()
                            ->whereHas('unit', function ($query) use ($unitIds) {
                                $query->whereIn('id', $unitIds);
                            })->get();

        $galleries = Gallery::published()->latest()->whereIn('unit_id', $unitIds)->take(6)->get();

        $headlines = Headline::with('unit')->published()->latest()
                        ->whereHas('unit', function ($query) use ($unitIds) {
                            $query->whereIn('id', $unitIds);
                        })->get();
        
        $popups = Popup::published()->whereIn('unit_id', $unitIds)->orderBy('created_at', 'desc')->get();

        $data = [
            'blogs' => $blogs,
            'testimonials' => $testimonials,
            'galleries' => $galleries,
            'headlines' => $headlines,
            'popups' => $popups,
        ];

        return $this->view('index', $data);
    }
}
