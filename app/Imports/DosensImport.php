<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Dosen;
use App\Models\ProgramStudi;
use Maatwebsite\Excel\Concerns\ToModel;

class DosensImport implements ToModel
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
            'prodi'      => $row[4]
        ]);

        $user = User::create([
            'username' => $row[3],
            'role_id'  => 2
        ]);

        $dosen = new Dosen([
            'name'          => $row[1],
            'email'         => $row[2],
            'no_induk'      => $row[3],
            'prodi_id'      => $prodi->id,
            'user_id'       => $user->id
        ]);

        $dosen->user()->associate($user);
        $dosen->save();

        return $dosen;
    }
}
