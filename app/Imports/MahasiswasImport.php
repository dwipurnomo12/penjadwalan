<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\ProgramStudi;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MahasiswasImport implements ToModel
{
    protected $headerRow = true;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if ($this->headerRow) {
            $this->headerRow = false;
            return null;
        }

        $prodi = ProgramStudi::firstOrCreate([
            'prodi'      => $row[5]
        ]);

        $user = User::create([
            'username' => $row[3],
            'role_id'  => 3
        ]);

        $mahasiswa = new Mahasiswa([
            'name'          => $row[1],
            'email'         => $row[2],
            'no_induk'      => $row[3],
            'thn_angkatan'  => intval($row[4]),
            'prodi_id'      => $prodi->id,
            'user_id'       => $user->id
        ]);

        $mahasiswa->user()->associate($user);
        $mahasiswa->save();

        return $mahasiswa;
    }
}
