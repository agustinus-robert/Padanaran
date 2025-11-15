<?php

namespace Modules\HRMS\Http\Requests\Service\Attendance\CollectiveSubmission;

use App\Http\Requests\FormRequest;
use Modules\HRMS\Models\EmployeeSchedule;
use Modules\HRMS\Enums\DutyShiftTeacher;
use Modules\HRMS\Enums\WorkShiftEnum;
use Modules\HRMS\Enums\ObShiftEnum;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('store', EmployeeSchedule::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'empl_type'  => 'required',
            'dates'      => 'required',
            'schedule_month' => 'required',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'empl_type'  => 'Tipe harus dipilih',
            'dates'      => 'Isian tidak boleh kosong',
            'schedule_month' => 'Bulan tidak boleh kosong'
        ];
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        $data = json_decode($this->input('dates'), true);
        $resultArray = [];

        // foreach ($data as $keyBulkData => $valueBulkData) {
        //     $resultArray[$keyBulkData] = [];
        //     foreach ($valueBulkData as $typeKey => $shiftBulk) {
        //         //if ($typeKey == 2) {
        //         foreach ($shiftBulk as $keyTeachershift => $valTeacherShift) {
        //             $resultArray[$keyBulkData][$keyTeachershift] = [];

        //             foreach ($valTeacherShift as $keyTeacherEntryShift => $valTeacherEntryShift) {
        //                 try {
        //                     //if ($typeKey == 2) {
        //                         $shiftEnum = DutyShiftTeacher::from($valTeacherEntryShift);
        //                    // }

        //                     $time = $shiftEnum->defaultTime();
        //                     $inTime = $time['in'];
        //                     $outTime = $time['out'];



        //                     $resultArray[$keyBulkData][$keyTeachershift][$shiftEnum->value - 1] = [
        //                         $keyTeachershift . ' ' . $inTime[0] . ':00',
        //                         $keyTeachershift . ' ' . $outTime[0] . ':00'
        //                     ];
        //                 } catch (\Exception $e) {

        //                     $resultArray[$keyBulkData][$keyTeachershift][$keyTeacherEntryShift] = [
        //                         null,
        //                         null
        //                     ];
        //                 }
        //             }

        //            // if ($typeKey == 2) {
        //                 $allShifts = DutyShiftTeacher::cases();
        //            // }

        //             foreach ($allShifts as $shiftEnums) {
        //                 if (!isset($resultArray[$keyBulkData][$keyTeachershift][$shiftEnums->value - 1])) {
        //                     $resultArray[$keyBulkData][$keyTeachershift][$shiftEnums->value - 1] = [
        //                         null,
        //                         null
        //                     ];
        //                 }
        //             }

        //             ksort($resultArray[$keyBulkData][$keyTeachershift]);
        //         }
        //         // }
        //     }
        // }

        return [
            'empl_id' => $this->input('empl_id'),
            'data' => $data
        ];
    }
}
