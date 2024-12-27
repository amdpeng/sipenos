<?php

namespace App\Imports;

use App\Models\Teacher;
use Maatwebsite\Excel\Concerns\ToModel;

class TeacherImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Teacher([
            'nip_nuptk' => $row[0],
            'teacher_name' => $row[1],
            'gender' => $row[2],
            'religion' => $row[3],
            'date_of_birth' => $row[4],
            'jenis_ptk' => $row[5],
            'type' => $row[6],
            'whatsapp_number' => $row[7],
        ]);
    }
}
