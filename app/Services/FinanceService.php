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
}
