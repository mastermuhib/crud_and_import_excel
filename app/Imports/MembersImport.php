<?php

namespace App\Imports;

use App\Model\MemberModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // Import the concern

class MembersImport implements ToModel, WithHeadingRow // Use the concern
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new MemberModel([
            'nkk' => $row['nkk'],
            'nik' => $row['nik'],
            'name' => $row['name'],
            'birthplace' => $row['tempat_lahir'],
            'birthday' => $row['tanggal_lahir'],
            'status' => $row['status'],
            'gender' => $row['jenis_kelamin'],
            'address' => $row['alamat'],
            'rt' => $row['rt'],
            'rw' => $row['rw'],                                                      
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
}
 