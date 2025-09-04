<?php

namespace App\Http\Controllers\WebUnit;

use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public $webUnit;
    public $units;

    public function __construct()
    {
        $this->webUnit = request()->route('webunit');
        $this->units  = Helper::webUnits($this->webUnit);
    }

    public function view(string $view, array $params = [])
    {
        $prefix = explode('-', $this->webUnit)[0];
        $suffix = explode('-', $this->webUnit)[1];

        if (!view()->exists("webunit.{$prefix}.{$suffix}.{$view}")) {
            return "views {$view} tidak ditemukan - {$this->webUnit}";
        }

        $params['webUnit'] = $this->webUnit;
        $params['prefix'] = $prefix;

        if ($prefix === 'kbtk') {
            $params['aboutSubMenu'] = $this->units->pluck('name', 'webunit_level')->all();
        }
        
        return view("webunit.{$prefix}.{$suffix}.{$view}", $params);
    }
}