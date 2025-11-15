<?php

namespace Modules\HRMS\Imports;

use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Modules\HRMS\Models\Employee;

class ImportEmployee implements OnEachRow, WithHeadingRow, WithValidation, WithStartRow
{
    use Importable;

    /**
     * Specify the row that contains the headings.
     */
    public function headingRow(): int
    {
        return 3; // Row 3 is the heading row
    }

    /**
     * Specify the starting row for data.
     */
    public function startRow(): int
    {
        // Start from row 4
        return 4;
    }

    public function onRow(Row $row)
    {
        // Retrieve the row data as an array
        $rowData = $row->toArray();

        // Skip the row if all values are empty
        if (empty(array_filter($rowData))) {
            Log::info("Skipping empty row at index {$row->getIndex()}.");
            return;
        }

        // Perform data validation and update the employee
        try {
            Employee::updateEmployeeViaImport($rowData);
        } catch (\Exception $e) {
            Log::error("Error processing row {$row->getIndex()}: " . $e->getMessage());
        }
    }

    public function rules(): array
    {
        return [
            'id_karyawan' => function ($attribute, $value, $onFailure) {
                if (!empty($value) && !is_numeric($value)) {
                    $onFailure("Isian $attribute harus berupa angka");
                }
            },
            'nama' => function ($attribute, $value, $onFailure) {
                if (!empty($value) && empty(trim($value))) {
                    $onFailure("Isian $attribute tidak boleh kosong");
                }
            },
            'jenis_kelamin' => function ($attribute, $value, $onFailure) {
                if (!empty($value) && !in_array($value, ['Pria', 'Wanita'])) {
                    $onFailure("Isian $attribute harus salah satu dari: Pria, Wanita");
                }
            },
            'alamat' => function ($attribute, $value, $onFailure) {
                if (!empty(trim($value)) && empty($value)) {
                    $onFailure("Isian $attribute tidak boleh kosong");
                }
            },
            'nik' => function ($attribute, $value, $onFailure) {
                if (!empty($value) && !preg_match("/^[0-9]{16}$/", $value)) {
                    $onFailure("Isian $attribute harus berupa 16 digit angka");
                }
            },
            'status' => function ($attribute, $value, $onFailure) {
                if (!empty($value) && !in_array($value, ['Menikah', 'Belum menikah', 'Janda', 'Duda', ''])) {
                    $onFailure("Isian $attribute tidak valid. Pilihan yang valid: Menikah, Belum menikah, Janda, Duda");
                }
            },
            'jumlah_tanggungan' => function ($attribute, $value, $onFailure) {
                if (!empty($value) && (!is_numeric($value) || $value < 0)) {
                    $onFailure("Isian $attribute harus berupa angka dan tidak boleh negatif");
                }
            },
            'agama' => function ($attribute, $value, $onFailure) {
                if (!empty($value) && !in_array($value, ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Konghucu', 'Lainnya'])) {
                    $onFailure("Isian $attribute harus salah satu dari: Islam, Kristen, Katolik, Hindu, Budha, Konghucu, Lainnya");
                }
            },
            'no_hp' => function ($attribute, $value, $onFailure) {
                if (!empty($value) && !preg_match("/^[0-9]{9,13}$/", $value)) {
                    $onFailure("Isian $attribute tidak valid. Harus berupa angka dengan panjang 9-13 digit");
                }
            },
            'email' => function ($attribute, $value, $onFailure) {
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $onFailure("Isian $attribute harus berupa alamat email yang valid");
                }
            },
        ];
    }
}
