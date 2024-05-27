<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\ProgramStudi;
use App\Models\Role;
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
            'name'      => 'Ludfia Budiono',
            'email'     => 'ludfia@gmail.com',
            'username'  => '90287727',
            'no_induk'  => '90287727',
            'password'  => bcrypt('1234'),
            'role_id'   => 1,
            'prodi_id'  => 1,
        ]);
        User::create([
            'name'      => 'Hamid Muhammad Jumasa',
            'email'     => 'hamid@gmail.com',
            'username'  => '1312425',
            'no_induk'  => '1312425',
            'password'  => bcrypt('1234'),
            'role_id'   => 2,
            'prodi_id'  => 1,
        ]);
        User::create([
            'name'      => 'Muhammad Khoirus Syifa',
            'email'     => 'syifa@gmail.com',
            'username'  => '202520034',
            'no_induk'  => '202520034',
            'password'  => bcrypt('1234'),
            'role_id'   => 3,
            'prodi_id'  => 1,
        ]);
    }
}
