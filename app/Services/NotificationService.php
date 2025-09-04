<?php
namespace App\Services;

use Carbon\Carbon;
use App\Models\Unit;
use App\Models\User;
use App\Models\Period;
use App\Models\PPDBUser;
use App\Models\Notification;
use App\Mail\NotificationEmail;
use App\Notifications\PPDBNotification;

class NotificationService
{
    public function filter($params =[], $limit = 15)
    {
        $query = Notification::with('notifiable', 'sender')
                    ->orderBy('created_at', 'desc');

        if (isset($params['notifiable_type']) && $params['notifiable_type']) {
            $query->where('notifiable_type', $params['notifiable_type']);
        }

        if (isset($params['notifiable_id']) && $params['notifiable_id']) {
            $query->where('notifiable_id', $params['notifiable_id']);
        }

        return $query->paginate($limit)->appends($params);
    }

    public function create(array $value){
        if (!isset($value['ppdb_user_id'])) {
            $ppdbUsers = app()->make(PPDBUserService::class)->filter($value, null, ['user', 'unit'], ['id', 'name']);
        } else {
            $ppdbUsers = PPDBUser::with('user', 'unit')->whereIn('id', $value['ppdb_user_id'])->get();
        }

        foreach ($ppdbUsers as $ppdbUser) {
            $ppdbUser->notify(new PPDBNotification($value));

            if (array_key_exists('send_email', $value) && $value['send_email']) {
                $this->sendEmail($ppdbUser->user, $ppdbUser->unreadNotifications->first());
            }
        }
    }

    public function delete($id)
    {
        // delete code
    }

    /**
     * Prepare data is needed in form created notification
     *
     * @return array
     * **/
    public function getDataFormCreate(): array
    {
        $data = $this->prepareForm();
        return $data;
    }

    /**
     * Prepare required data is needed in form notification
     *
     * @return array
     * **/
    private function prepareForm(): array
    {
        return [
            'users' => PPDBUser::all(['id', 'name'])->pluck('name', 'id'),
            'units' => Unit::all(['id', 'name']),
            'periods' => Period::with('unit')->orderBy('unit_id')->get(),
            'years' => $this->getAvailableYears(),
        ];
    }

    public function getAvailableYears()
    {
        return PPDBUser::distinct()->whereNotNull('school_year')->get('school_year as year');
    }

    public function sendEmail(User $user, $notification)
    {
        $emailService = new EmailService();
        $template = (new NotificationEmail($user, $notification));
        $emailService->sendMail($template, $user->email);

        $notification->sended_email = Carbon::now();
        $notification->save();
    }
}
