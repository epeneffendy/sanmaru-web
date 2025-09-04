<?php

namespace App\Services;

use App\Jobs\SendEmailJob;
use Illuminate\Support\Facades\Mail;
use Log;

class EmailService
{
    // public function sendMail($template = null, $to = "noreply@sanmarosu-jatim.sch.id", callable $successCallback = null, callable $failedCallback = null)
    public function sendMail($template = null, $to = "noreply@sanmarosu-jatim.sch.id", array $updateParams = [], array $cc = [])
    {
        if ($template && isset($template->mail_cc)) {
            $cc = array_merge($cc, $template->mail_cc);
        }

        SendEmailJob::dispatch($to, $template, $updateParams, $cc);
        // try {
        //     Mail::to($to)->send($template);
        //     if ($successCallback) {
        //         call_user_func($successCallback);
        //     }
        // } catch (\Exception $e) {
        //     Log::error($e->getMessage());
        //     if ($failedCallback) {
        //         call_user_func($failedCallback);
        //     }
        // }
    }
}
