<?php

namespace App\Services;

use App\Exceptions\UserException;
use App\Models\CampusUnit;
use App\Models\StudentAdditionalData;
use App\Models\Student;
use App\Models\Classes;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentService
{
    public function filter(array $params, int $paginate_limit = null, array $related = null)
    {
        $students = Student::query();

        if (array_key_exists('search', $params) && array_key_exists('scope', $params) && $params['search']) {
            switch ($params['scope']) {
                case 'name':
                    $students->where('name', 'like', '%' . $params['search'] . '%');
                    break;
                case 'nis':
                    $students->where('nis', 'like', '%' . $params['search'] . '%');
                    break;
                default:
                    break;
            }
        }

        if (array_key_exists('unit', $params) && $params['unit']) {
            $students->whereHas('class', function ($query) use ($params) {
                $query->where('unit_id', $params['unit']);
            });
        }
        if (array_key_exists('year', $params) && $params['year']) {
            $students->where('school_year', $params['year']);
        }
        if ($related) {
            $students->with($related);
        }
        if ($paginate_limit) {
            return $students->paginate($paginate_limit);
        } else {
            return $students->get();
        }
    }

    /**
     * Get all years available at students school_year attribute
     * @return Collection
     */
    public function getAvailableYears(): Collection
    {
        return Student::distinct()->whereNotNull('school_year')->select('school_year as year')->orderBy('school_year')->get();
    }

    public function show($id)
    {
        return Student::where('id', $id)
            ->with('class', 'class.unit', 'parents')->firstOrFail();
    }

    public function register($params)
    {
        DB::beginTransaction();
        try {
            $userService = new UserService();
            $user = $userService->register(User::STUDENT, $params);
            $student = $user->student;

            $studentAdditionalData = StudentAdditionalData::firstOrNew(['student_id' => $student->id]);
            $studentAdditionalData->fill($params);
            $studentAdditionalData->save();

            $parentService = new ParentService;
            $params['user_id'] = $student->user_id;
            if ($params['tinggal_dengan'] === 'wali') {
                $parentService->updateWali($student->user_id, $params);
            } else {
                $parentService->updateFather($student->user_id, $params);
                $parentService->updateMother($student->user_id, $params);
            }

            $ppdbService = new PPDBUserService();
            $ppdbService->uploadImages($studentAdditionalData, $params);

            DB::commit();
            return $student;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    public function update($id, $params)
    {
        // Handle dob sent as empty string
        $params['date_of_birth'] = $params['date_of_birth'] == '' ? null : $params['date_of_birth'];
        DB::beginTransaction();
        try {
            $student = Student::findOrFail($id);
            $student->fill($params);
            $student->save();
            $user = $student->user;
            $user->update($this->userParam($params));

            $studentAdditionalData = StudentAdditionalData::firstOrNew(['student_id' => $student->id]);
            $studentAdditionalData->fill($params);
            $studentAdditionalData->save();

            $parentService = new ParentService;
            $params['user_id'] = $student->user_id;
            if ($params['tinggal_dengan'] === 'wali') {
                $parentService->updateWali($student->user_id, $params);
            } else {
                $parentService->updateFather($student->user_id, $params);
                $parentService->updateMother($student->user_id, $params);
            }

            $ppdbService = new PPDBUserService();
            $ppdbService->uploadImages($studentAdditionalData, $params);

            DB::commit();
            return $student;
        } catch (Exception $e) {
            echo $e->getMessage();
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    public function generateEditableData($id, $nav)
    {
        $student = Student::where('id', $id)->with('additionalData', 'parents')->firstOrFail();

        return array(
            // 'paymentList' => PaymentAgreement::pluck('name', 'id'),
            'classList' => Classes::withUnit()->with('unit')->get(),
            'student' => $student,
            'statuses' => Student::getAvailableStatuses(),
            'nav' => $nav,
            'method' => 'edit'
        );
    }

    public function updateByNis($params)
    {
        try {
            $student = DB::table('students')
                ->select('students.*')
                ->join('classes', 'classes.id', '=', 'students.class_id')
                ->where(['nis' => $params['nis'], 'classes.unit_id' => $params['unit_id']])
                ->whereNull('students.deleted_at')
                ->first();
            if (isset($student)){
                $student = Student::where('id', $student->id)->firstOrFail();
                $student->update($params);
                $studentAdditionalData = StudentAdditionalData::firstOrNew(['student_id' => $student->id]);
                $studentAdditionalData->fill($params);
                $studentAdditionalData->save();
                $user = $student->user;
                $user->update($this->userParam($params));
                DB::commit();
            }else{
                DB::rollBack();
                $exception = new UserException('User registration is failed');
                $exception->additionalInfo = 'data sudah ada.';
                throw $exception;
            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            $exception = new UserException('User registration is failed');
            if (isset($e->errorInfo)) {
                $errorInfo = $e->errorInfo;
                if (isset($errorInfo[1]) && $errorInfo[1] == 1062) {
                    $exception->additionalInfo = 'data tidak ditemukan.';
                }
            }

            if (isset($e->additionalInfo)) {
                $exception->additionalInfo = 'data tidak ditemukan.';
            }
            throw $exception;
        }
    }

    public function uploadImage($imageService, $student, $image)
    {
        if ($student->image_path !== null) {
            $path = base_path() . ImageService::PATH_PRIVATE . $student->image_path;
            unlink($path);
        }

        $path = $imageService->upload(ImageService::PATH_STUDENT, $student->id, $image);
        $student->image_path = $path;
        return $student->save();
    }

    private function userParam($params)
    {
        return array(
            'email' => $params['email'],
            'mobile_phone' => app('phoneNormalizerService')->normalize($params['mobile_phone']),
        );
    }

    public function setInactive($id){
        $student = Student::where('id', $id)->firstOrFail();
        $student->status = Student::STATUS_INACTIVE;
        $student->save();
        return $student;
    }
}
