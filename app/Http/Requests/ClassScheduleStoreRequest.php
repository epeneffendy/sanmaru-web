<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\ClassSchedule;
use Carbon\Carbon;

class ClassScheduleStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'unit_id' => 'required|exists:units,id',
            'class_id' => 'required|exists:classes,id',
            'course_id' => 'required|exists:courses,id',
            'day' => 'required|in:monday,tuesday,wednesday,thrusday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'is_valid_schedule' => 'required|accepted'
        ];
    }

    public function attributes()
    {
        return [
            'unit_id' => 'unit',
            'class_id' => 'kelas',
            'day' => 'hari',
            'start_time' => 'jam mulai',
            'end_time' => 'jam selesai',
            'is_valid_schedule' => 'jadwal'
        ];
    }

    public function messages()
    {
        return [
            'is_valid_schedule.accepted' => 'jam mulai / jam selesai sudah digunakan'
        ];
    }

    protected function getValidatorInstance()
    {
        $data = $this->all();
        $data['is_valid_schedule'] = true;

        if (isset($data['class_id']) && isset($data['start_time']) && isset($data['end_time'])
            && isset($data['day'])) {

            $data['start_time'] = Carbon::parse($data['start_time'])->format('H:i');
            $data['end_time'] = Carbon::parse($data['end_time'])->format('H:i');

            $classSchedule = ClassSchedule::where('class_id', $data['class_id']);

            if ($this->method() !== "POST" && $data['id']) {
                $classSchedule = $classSchedule->where('id', '<>', $data['id']);
            }

            $classSchedule = $classSchedule->where('day', $data['day'])
            ->where('start_time', '<', $data['end_time'])
            ->where('end_time', '>', $data['start_time'])
            ->first();

            if ($classSchedule) {
                $data['is_valid_schedule'] = false;
            }

        }
        
        $this->getInputSource()->replace($data);

        return parent::getValidatorInstance();
    }
}
