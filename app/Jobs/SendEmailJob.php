<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Log;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $to;
    private $template;
    private $updateParams;
    private $cc;

    /**
     * Create a new job instance.
     * updateParams     array   ex: ['ProductOrder', [['id', 1]], ['payment_confirmed_mail_send' => true]]   
     * @return void
     */
    public function __construct($to, $template, array $updateParams = null, array $cc = null)
    {
        $this->to = $to;
        $this->template = $template;
        $this->updateParams = $updateParams;
        $this->cc = $cc;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            if ($this->cc && isset($this->cc) && !empty($this->cc) && !is_null($this->cc)) {
                Mail::to($this->to)->cc($this->cc)->send($this->template);
            } else {
                Mail::to($this->to)->send($this->template);
            }
            if ($this->updateParams && isset($this->updateParams[0]) && isset($this->updateParams[1]) && isset($this->updateParams[2])) {
                $model = 'App\Models\\'. $this->updateParams[0];
                $data = $model::where($this->updateParams[1])->first();
                if ($data) {
                    $data->update($this->updateParams[2]);
                }
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
