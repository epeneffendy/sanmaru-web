<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Finance;
use Illuminate\Support\Str;

class FinanceUpdateRequest extends FormRequest
{
    protected $registeredUsers;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function prepareForValidation()
    {
        $this->merge([
            'code' => $this->pattern(),
            'user_ids' => $this->user_ids ?? [],
            'is_insider' => $this->is_insider == '1' ? true : false,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'required|in:registrasi,development,uniform,tuition,activity,other',
            'unit_name' => 'nullable|exists:units,name',
            //'user_id' => 'nullable|exists:users,id',
            'year' => 'nullable|date_format:Y|numeric',
            'code' => "required|string|unique:finances,code,{$this->route('finance')->id},id",
            'name' => 'required|string',
            'description' => 'string|nullable',
            'period_id' => 'nullable|exists:periods,id',
            'unit_id' => 'nullable|exists:units,id',
            'nominal_default' => 'required|numeric',
            'user_ids' => 'nullable|array',
            'user_ids.*' => ['exists:users,id', function ($attribute, $value, $fail) {
                if ($user = $this->registeredUsers()->get($value)) {
                    $fail($user['name'].' sudah terdaftar.');
                }
            }],
            'start_date' => 'nullable|date',
            'is_insider' => 'nullable|boolean',
        ];
    }

    public function attributes()
    {
        return [
            'code' => 'Kode',
            'type' => 'Tipe',
            'year' => 'Tahun',
            'name' => 'Nama'
        ];
    }

    public function pattern()
    {
        $code = $this->defaultPattern();

        if ($this->user_ids) {
            $code = $this->usersPattern();
            if (Str::startsWith($this->route('finance')->code, $code)) {
                return $this->route('finance')->code;
            }
            $latest = Finance::where('code', 'like', $code . '%')->orderByDesc('code')->first();
            $sequence = $latest ? (int) Str::after($latest->code, $code) : 0;
            $sequence += 1;
            $code .= (string) $sequence;
        }

        return $code;
    }

    public function usersPattern()
    {
        if ($this->user_ids) {
            return $this->defaultPattern() . '.users_';
        }

        return $this->defaultPattern();
    }

    public function defaultPattern()
    {
        return 'type_' . $this->type . 
            ($this->unit_id ? '.unit_' . $this->unit_id : NULL) . 
            ($this->year ? '.year_' . $this->year : NULL) . 
            ($this->period_id ? '.period_' . $this->period_id : NULL);
    }

    public function registeredUsers()
    {
        if ($this->registeredUsers) {
            return $this->registeredUsers;
        }

        $finances = Finance::where('code', 'like', $this->usersPattern() . '%')
            ->whereHas('users', function ($query) {
                return $query->whereIn('user_id', $this->user_ids);
            })->with([
                'users' => function ($query) {
                    $query->select('users.id');
                },
                'users.ppdb' => function ($query) {
                    $query->select('ppdb_users.id', 'ppdb_users.user_id', 'ppdb_users.name'); 
                },
                'users.student' => function ($query) {
                    $query->select('students.id', 'students.user_id', 'students.name');
                }
            ])
            ->where('id', '<>' ,$this->route('finance')->id)
            ->get();

        $collect = collect();

        foreach ($finances as $finance) {
            foreach ($finance->users as $user) {
                $collect->put($user->id, [
                    'id' => $user->id,
                    'name' => $user->ppdb ? $user->ppdb->name 
                                : ($user->student ? $user->student->name : $user->email),
                ]);
            }
        }

        return $this->registeredUsers = $collect;
    }
}
