<?php

namespace App\Services;

use App\Exceptions\UserException;
use App\Models\Parents;
use App\Models\PPDBUser;
use App\Models\User;

class ParentService
{
    public function createFather($params)
    {
        if (!isset($params['father_name'])) return;
        $father_data = $this->populateFatherData($params);
        return Parents::create($father_data);
    }

    public function createMother($params)
    {
        if (!isset($params['mother_name'])) return;
        $moms_data = $this->populateMotherData($params);
        return Parents::create($moms_data);
    }

    public function createWali($params)
    {
        if (!isset($params['wali_name'])) return;
        $wali_data = $this->populateWaliData($params);
        return Parents::create($wali_data);
    }

    public function show($type, $childId)
    {
        return Parents::where('type',$type)->where('children_id', $childId)->first();
    }

    public function updateMother($userId, $params)
    {
        $userId = User::where('id', $userId)->pluck('id')->first();
        throw_unless(isset($userId), new UserException('User is not found'));

        $mom = Parents::where('children_id', $userId)->where('type', 'mother')->first();

        $mom = isset($mom) ? $mom : new Parents();
        $params['user_id'] = $userId;
        $data = $this->populateMotherData($params);
        $mom->fill($data);

        return $mom->save();
    }

    public function updateFather($userId, $params)
    {
        $userId = User::where('id', $userId)->pluck('id')->first();
        throw_unless(isset($userId), new UserException('User is not found'));

        $dad = Parents::where('children_id', $userId)->where('type', 'father')->first();

        $dad = isset($dad) ? $dad : new Parents();
        $params['user_id'] = $userId;
        $data = $this->populateFatherData($params);
        $dad->fill($data);

        return $dad->save();
    }

    public function updateWali($userId, $params)
    {
        $userId = User::where('id', $userId)->pluck('id')->first();
        throw_unless(isset($userId), new UserException('User is not found'));

        $wali = Parents::where('children_id', $userId)->where('type', 'wali')->first();

        $wali = isset($wali) ? $wali : new Parents();
        $params['user_id'] = $userId;
        $wali = $this->populateWaliData($params);
        $wali->fill($data);

        return $wali->save();
    }

    private function populateFatherData($params)
    {
        return array(
            'name' => isset($params['father_name']) ? $params['father_name'] : '-',
            'place_of_birth' => $params['f_place_of_birth'],
            'date_of_birth' => date('Y-m-d', strtotime($params['f_date_of_birth'])),
            'address' => $params['f_address'],
            'city' => $params['f_city'],
            'region' => $params['f_region'],
            'country' => $params['f_country'],
            'religion' => $params['f_religion'],
            'job' => $params['f_job'],
            'salary' => $params['f_salary'],
            'education' => $params['f_education'],
            'type' => 'father',
            'phone' => $params['f_phone'] ? app('phoneNormalizerService')->normalize($params['f_phone']) : NULL,
            'children_id' => $params['user_id']
        );
    }

    private function populateMotherData($params)
    {
        return array(
            'name' => isset($params['mother_name']) ? $params['mother_name'] : '-',
            'place_of_birth' => $params['m_place_of_birth'],
            'date_of_birth' => date('Y-m-d', strtotime($params['m_date_of_birth'])),
            'address' => $params['m_address'],
            'city' => $params['m_city'],
            'region' => $params['m_region'],
            'country' => $params['m_country'],
            'religion' => $params['m_religion'],
            'job' => $params['m_job'],
            'salary' => $params['m_salary'],
            'education' => $params['m_education'],
            'type' => 'mother',
            'phone' => $params['m_phone'] ? app('phoneNormalizerService')->normalize($params['m_phone']) : NULL,
            'children_id' => $params['user_id']
        );
    }

    private function populateWaliData($params)
    {
        return array(
            'name' => isset($params['wali_name']) ? $params['wali_name'] : '-',
            'place_of_birth' => $params['w_place_of_birth'],
            'date_of_birth' => date('Y-m-d', strtotime($params['w_date_of_birth'])),
            'address' => $params['w_address'],
            'city' => $params['w_city'],
            'region' => $params['w_region'],
            'country' => $params['w_country'],
            'religion' => $params['w_religion'],
            'job' => $params['w_job'],
            'salary' => $params['w_salary'],
            'education' => $params['w_education'],
            'type' => 'wali',
            'phone' => $params['w_phobe'] ? app('phoneNormalizerService')->normalize($params['w_phone']) : NULL,
            'children_id' => $params['user_id']
        );
    }
}
