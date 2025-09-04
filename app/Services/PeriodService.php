<?php
namespace App\Services;

use App\Models\Period;

class PeriodService {
    public function filter(array $params, int $paginate_limit = null, array $related = null)
    {
        $periods = Period::query();

        if (array_key_exists('name', $params) && $params['name']) {
            $periods->where('name', 'like', '%' . $params['name'] . '%');
        }
        if (array_key_exists('unit', $params) && $params['unit']) {
            $periods->where('unit_id', $params['unit']);
        }
        if (array_key_exists('year', $params) && $params['year']) {
            $periods->where('school_year', $params['year']);
        }
        if ($related) {
            $periods->with($related);
        }
        if ($paginate_limit) {
            return $periods->paginate($paginate_limit);
        } else {
            return $periods->get();
        }
    }

    public function getAvailableYears($unit_id = null)
    {
        $periods = Period::query();
        if ($unit_id) {
            $periods->where('unit_id', $unit_id);
        }
        return $periods->distinct()->whereNotNull('school_year')->get('school_year as year');
    }

    public function getSchoolYearOptions()
    {
        $suggestedSchoolYear = now()->month > 6 ? (now()->year + 1) : now()->year;
        $tmp = $this->getAvailableYears();

        // initiate collection with previous year, current year, and next year
        $schoolYearOptions = collect([['year' => $suggestedSchoolYear - 1]]);
        $schoolYearOptions->push(['year' => $suggestedSchoolYear]);
        $schoolYearOptions->push(['year' => $suggestedSchoolYear + 1]);

        foreach ($tmp as $tmpYear) {
            if (!$schoolYearOptions->contains('year', $tmpYear->year)) {
                $schoolYearOptions->push($tmpYear);
            }
        }
        return $schoolYearOptions;
    }
}
