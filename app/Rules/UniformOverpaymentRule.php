<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Helpers\PriceHelper;
use App\Models\PPDBUser;

class UniformOverpaymentRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $input = [];
    protected $attribute;
    protected $message = null;

    public function __construct($input)
    {
        $this->input = $input;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->attribute = $attribute;

        if ($attribute === 'payment_number' 
            && isset($this->input['register_number']) && $this->input['register_number']
            && isset($this->input['payment_method']) && $this->input['payment_method']) {
            $ppdbUser = PPDBUser::where('register_number', $this->input['register_number'])
                                ->first();

            if ($ppdbUser) {
                return $value === PriceHelper::virtualAccountNumber($ppdbUser, true, $this->input['payment_method']);
            }


        }

        if ($attribute === 'nominal_refund'
            && isset($this->input['payment_amount']) && $this->input['payment_amount']
            && isset($this->input['grand_total']) && $this->input['grand_total']) {
            
            $this->message = "{$this->attributes()['nominal_refund']} harus sama dengan {$this->attributes()['payment_amount']} dikurangi dengan {$this->attributes()['grand_total']}";

            return (intval($value) === (intval($this->input['payment_amount']) - intval($this->input['grand_total'])));
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if ($this->message) {
            return $this->message;
        }

        return "{$this->attributes()[$this->attribute]} tidak valid.";
    }


    public function attributes()
    {
        return [
            'nominal_refund' => 'Nominal pengembalian',
            'payment_number' => 'Virtual Account Number',
            'payment_amount' => 'Nominal yang dibayar',
            'grand_total' => 'Nominal harus bayar',
            'invoice_no' => 'Nomor pembayaran'
        ];
    }
}
