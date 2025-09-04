<?php

namespace App\Observers;

use App\Models\PPDBUser;

class PPDBUserObserver
{
    public function saving(PPDBUser $ppdbUser)
    {
        // if ($ppdbUser->IsStatusComplete)
        // {
        //     $ppdbUser->status = PPDBUser::STATUS_COMPLETE;
        // }
    }
}
