<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\ProgramStudi;
use App\Models\Role;
use App\Models\TataUsaha;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::create([
            'role'  => 'tata usaha'
        ]);
        Role::create([
            'role'  => 'dosen'
        ]);
        Role::create([
            'role'  => 'mahasiswa'
        ]);

        ProgramStudi::create([
            'prodi' => 'Teknologi Informasi'
        ]);
        ProgramStudi::create([
            'prodi' => 'Teknik Sipil'
        ]);

        User::create([
            'username'  => '90287727',
            'password'  => bcrypt('1234'),
            'role_id'   => 1
        ]);

        TataUsaha::create([
            'user_id'   => 1,
            'name'      => 'Ludfia Budiono',
            'email'     => 'ludfia@gmail.com',
            'no_induk'  => '90287727',
            'prodi_id'  => 1,
        ]);

        User::create([
            'username'  => '1312425',
            'password'  => bcrypt('1234'),
            'role_id'   => 2
        ]);
        Dosen::create([
            'user_id'   => 2,
            'name'      => 'Hamid Muhammad Jumasa',
            'email'     => 'hamid@gmail.com',
            'no_induk'  => '1312425',
            'prodi_id'  => 1,
        ]);

        User::create([
            'username'  => '202520034',
            'password'  => bcrypt('1234'),
            'role_id'   => 3
        ]);

        Mahasiswa::create([
            'user_id'       => 3,
            'name'          => 'Muhammad Khoirus Syifa',
            'email'         => 'syifa@gmail.com',
            'no_induk'      => '202520034',
            'thn_angkatan'  => 2020,
            'prodi_id'      => 1,
        ]);



        User::create([
            'username'  => '90287721',
            'password'  => bcrypt('1234'),
            'role_id'   => 1
        ]);
        TataUsaha::create([
            'user_id'   => 4,
            'name'      => 'Budiono Siregar',
            'email'     => 'budiono@gmail.com',
            'no_induk'  => '90287721',
            'prodi_id'  => 2,
        ]);

        User::create([
            'username'  => '1312421',
            'password'  => bcrypt('1234'),
            'role_id'   => 2
        ]);
        Dosen::create([
            'user_id'   => 5,
            'name'      => 'Mujiyono',
            'email'     => 'mujiyono@gmail.com',
            'no_induk'  => '1312421',
            'prodi_id'  => 2,
        ]);

        User::create([
            'username'  => '202520010',
            'password'  => bcrypt('1234'),
            'role_id'   => 3
        ]);

        Mahasiswa::create([
            'user_id'       => 6,
            'name'          => 'Robert Davis Chaniago',
            'email'         => 'robert@gmail.com',
            'no_induk'      => '202520010',
            'thn_angkatan'  => 2020,
            'prodi_id'      => 2,
        ]);
    }
}
