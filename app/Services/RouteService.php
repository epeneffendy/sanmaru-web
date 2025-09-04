<?php
namespace App\Services;

class RouteService
{
    public function isProduction()
    {
        return env('APP_ENV') === 'production';
    }

    public function isStaging()
    {
        return env('APP_ENV') === 'staging';
    }

    public function getPpdbSubdomain()
    {
        $domain = request()->getHost();
        if (self::isProduction()) {
            $domain = 'ppdb.sanmarosu-jatim.sch.id';
        }
        return $domain;
    }

    public function getBackendSubdomain()
    {
        $domain = request()->getHost();
// 	dd($domain);
        if (self::isProduction()) {
            //$domain = 'api.sanmarosu-jatim.sch.id';
	    $domain = 'sanmaru.sanmarosu-jatim.sch.id';
        }
        return $domain;
    }

    public function getWebSubdomain()
    {
        $domain = request()->getHost();
        if (self::isProduction()) {
            $domain = 'sanmarosu-jatim.sch.id';
        }
        return $domain;
    }

    public function getWebUnitSubdomain()
    {
        $domain = request()->getHost();
        if (self::isProduction()) {
            $domain = '{webunit}.sanmarosu-jatim.sch.id';
        }
        return $domain;
    }

    public function getKantinSubdomain()
    {
        $domain = request()->getHost();
        if (self::isProduction()) {
            $domain = 'kantin.sanmarosu-jatim.sch.id';
        }
        return $domain;
    }

    public function getPaymentsSubdomain()
    {
        $domain = request()->getHost();
        // if (self::isProduction()) {
        //     $domain = 'payment.sanmarosu-jatim.sch.id';
        // }
        return $domain;
    }
}
?>
