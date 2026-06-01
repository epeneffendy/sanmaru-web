<?php
namespace App\Services;

use App\Models\User;
use App\Models\Finance;
use App\Models\Period;

class FinanceService {
    public function filter(array $params, int $paginate_limit = null, array $related = null)
    {
        $finances = Finance::orderBy('updated_at', 'DESC');
        if (array_key_exists('search', $params) && array_key_exists('scope', $params) && $params['search']) {
            switch ($params['scope']) {
                case 'student_username':
                    $finances->whereHas('users', function($query) use($params) {
                        $query->where('username', 'like', '%' . $params['search'] . '%');
                    });
                    break;
                case 'finance_name':
                    $finances->where('name', 'like', '%' . $params['search'] . '%');
                    break;
                case 'period_name':
                    $finances->whereHas('period', function($query) use($params) {
                        $query->where('name',  'like', '%' . $params['search'] . '%');
                    });
                    break;
                default:
                    break;
            }
        }
        if (array_key_exists('type', $params) && $params['type']) {
            $finances->where('type', $params['type']);
        }
        if (array_key_exists('unit', $params) && $params['unit']) {
            $finances->where('unit_id', $params['unit']);
        }
        if (array_key_exists('period', $params) && $params['period']) {
            $finances->where('year', $params['period']);
        }
        if ($related) {
            $finances->with($related);
        }
        if ($paginate_limit) {
            return $finances->paginate($paginate_limit);
        } else {
            return $finances->get();
        }
    }

    public function getAvailableYears()
    {
        return Finance::distinct()->whereNotNull('year')->orderBy('year', 'ASC')->get('year')->makeHidden(['user_ids', 'finance_user']);
    }

    public function getCollectedYears()
    {
        $periodService = new PeriodService();
        $years = $this->getAvailableYears();
        $tmp = $periodService->getAvailableYears();
        foreach ($tmp as $tmpYear) {
            if (!$years->contains('year', $tmpYear->year)) {
                $years->push($tmpYear);
            }
        }
        if ($years->count() < 1) {
            $years->push(['year' => now()->format('Y')]);
        }
        return $years;
    }

    public function getFinanceReport($params)
    {
        $finances = Finance::orderBy('updated_at', 'DESC');

        if (isset($params['unit']) && $params['unit'] != 'all') {
            $finances->where('unit_id', $params['unit']);
        }

        if (isset($params['period']) && $params['period'] != 'all') {
            $finances->where('period_id', $params['period']);
        }

        if (isset($params['year']) && $params['year'] != 'all') {
            $finances->where('year', $params['year']);
        }
        $data = $finances->get();

        $collection = [];
        foreach ($data as $ind => $finance) {

            $collection[$finance->period->id]['periode'] = isset($finance->period) ? $finance->period->name : '';
            $collection[$finance->period->id]['unit'] = isset($finance->unit) ? $finance->unit->name : '';
            $collection[$finance->period->id]['development'] = $finance->type == 'development' ? $finance->nominal_default : 0;
            $collection[$finance->period->id]['registration'] = $finance->type == 'registration' ? $finance->nominal_default : 0;
            $collection[$finance->period->id]['tuition'] = $finance->type == 'tuition' ? $finance->nominal_default : 0;
            $collection[$finance->period->id]['activity'] = $finance->type == 'activity' ? $finance->nominal_default : 0;
            $collection[$finance->period->id]['other'] = $finance->type == 'other' ? $finance->nominal_default : 0;


        }
        dd($collection);
        return $finances->get();
    }
}
