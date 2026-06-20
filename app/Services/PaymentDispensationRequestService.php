<?php

namespace App\Services;

use App\Helpers\PriceHelper;
use App\Lib\DbTrx;
use App\Models\PaymentDispensationDetails;
use App\Models\PaymentDispensationRequest;
use App\Models\PPDBUser;
use App\Models\StudentBills;
use Carbon\Carbon;

class PaymentDispensationRequestService {

    public function get()
    {
        $data = PaymentDispensationRequest::get();
        return $data;
    }


}
