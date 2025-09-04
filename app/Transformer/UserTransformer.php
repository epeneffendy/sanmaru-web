<?php

namespace App\Transformer;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
    	return array(
    		'user' => $this->user($user),
    		'student' => $user->student ? $this->student($user->student, $user) : null,
    		'parents' => $this->parents($user->parents)
    	);
    }

    private function user($data)
	{
    	return  array(
			'username' => $data->username,
			'email' => $data->email,
			'mobile_phone' => $data->mobile_phone,
			'type' => $data->type,
            'status' => $data->status,
		);
    }

    private function student($data, $user)
	{
    	return array(
			'nis' => $data->nis,
			'name' => $data->name,
			'email' => $data->email,
			'mobile_phone' => $data->mobile_phone,
			'address' => $data->address,
            'class_name' => @$data->class->name,
            'unit_name' => @$data->class->unit->name,
			'payment_agreement_name' => $data->payment ? $data->payment->name : null,
			'photo_profile' => $data->image_path,
			'school_year' => $data->school_year,
			'gender' => $user->ppdb ? $user->ppdb->gender : NULL,
			'origin_school' => $user->ppdb ? $user->ppdb->origin_school : NULL,
			'ttl' => $user->ppdb ? $user->ppdb->place_of_birth .' - '. $user->ppdb->date_of_birth : NULL,
			'religion' => $user->ppdb ? $user->ppdb->religion : NULL,
			'nik' => $user->ppdb ? $user->ppdb->nik : NULL,
			'number_of_siblings' => $user->ppdb ? $user->ppdb->jumlah_saudara_kandung + $user->ppdb->jumlah_saudara_tiri : NULL,
		);
    }

    private function parents($data)
	{
    	return $data->makeHidden(['id','deleted_at','created_at','updated_at','children_id']);
    }
}
