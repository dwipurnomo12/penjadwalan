<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use App\Imports\MahasiswasImport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $prodiId = auth()->user()->tataUsaha->prodi->id;

        $users = User::where('role_id', 3)
            ->whereHas('mahasiswa', function ($query) use ($prodiId) {
                $query->where('prodi_id', $prodiId);
            })
            ->orderBy('id', 'DESC')
            ->get();
        $prodis = ProgramStudi::all();

        return view('mahasiswa.index', [
            'users'     => $users,
            'prodis'    => $prodis
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('mahasiswa.create', [
            'prodis'    => ProgramStudi::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photo'         => 'mimes:png,jpg,jpeg',
            'name'          => 'required',
            'email'         => 'required|unique:mahasiswas',
            'no_induk'      => 'required|unique:mahasiswas',
            'thn_angkatan'  => 'required',
            'prodi_id'      => 'required'
        ], [
            'photo.mimes'           => 'Format photo yang diijinkan adalah png, jpg, jpeg',
            'name.required'         => 'Wajib diisi !',
            'email.required'        => 'Wajib diisi !',
            'email.unique'          => 'Email sudah terdaftar',
            'no_induk.required'     => 'Wajib diisi !',
            'no_induk.unique'       => 'No induk mahasiswa sudah terdaftar',
            'thn_angkatan.required' => 'Wajib diisi !',
            'prodi_id'              => 'Pilih Prodi !'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $photo = null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = $file->getClientOriginalName();
            Storage::disk('public')->put('photo-profile/' . $fileName, file_get_contents($file));
            $photo = 'photo-profile/' . $fileName;
        }

        $user = User::create([
            'username'      => $request->no_induk,
            'role_id'       => 3,
        ]);

        Mahasiswa::create([
            'user_id'       => $user->id,
            'name'          => $request->name,
            'email'         => $request->email,
            'no_induk'      => $request->no_induk,
            'thn_angkatan'  => $request->thn_angkatan,
            'prodi_id'      => $request->prodi_id,
            'photo'         => $photo,
        ]);

        return redirect('/mahasiswa')->with('success', 'Data berhasil ditambahkan !');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('mahasiswa.edit', [
            'user'      => $user,
            'prodis'    => ProgramStudi::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $mahasiswa = $user->mahasiswa;

        $validator = Validator::make($request->all(), [
            'photo'                 => 'mimes:png,jpg,jpeg',
            'name'                  => 'required',
            'email'                 => 'required|unique:mahasiswas,email,' . $mahasiswa->id,
            'no_induk'              => 'required|unique:mahasiswas,no_induk,' . $mahasiswa->id,
            'prodi_id'              => 'required',
            'password'              => 'nullable|min:4',
            'photo.mimes'           => 'Format photo yang diijinkan adalah png, jpg, jpeg',
            'name.required'         => 'Wajib diisi !',
            'email.required'        => 'Wajib diisi !',
            'email.unique'          => 'Email sudah terdaftar',
            'no_induk.required'     => 'Wajib diisi !',
            'no_induk.unique'       => 'No induk mahasiswa sudah terdaftar',
            'prodi_id.required'     => 'Pilih Prodi !',
            'thn_angkatan.required' => 'Wajib diisi !',
            'password.min'          => 'Password minimal 6 karakter',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $photo = $mahasiswa->photo;
        if ($request->hasFile('photo')) {
            if ($mahasiswa->photo && Storage::disk('public')->exists($mahasiswa->photo)) {
                Storage::disk('public')->delete($mahasiswa->photo);
            }

            $file = $request->file('photo');
            $fileName = $file->getClientOriginalName();
            Storage::disk('public')->put('photo-profile/' . $fileName, file_get_contents($file));
            $photo = 'photo-profile/' . $fileName;
        }

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
            $user->save();
        }


        $user->update([
            'username' => $request->no_induk,
        ]);

        $mahasiswa->update([
            'name'          => $request->name,
            'email'         => $request->email,
            'no_induk'      => $request->no_induk,
            'tgn_angkatan'  => $request->tgn_angkatan,
            'prodi_id'      => $request->prodi_id,
            'photo'         => $photo,
        ]);

        if ($request->filled('password')) {
            $mahasiswa->save();
        }

        return redirect('/mahasiswa')->with('success', 'Data berhasil diupdate !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $mahasiswa = User::where('role_id', 3)->findOrFail($id);

        if ($mahasiswa->photo && Storage::disk('public')->exists($mahasiswa->photo)) {
            Storage::disk('public')->delete($mahasiswa->photo);
        }
        $mahasiswa->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file'  => 'required|file|mimes:xlsx,xls'
        ], [
            'file.required'     => 'Tidak boleh kosong !',
            'file.file'         => 'Harus ber-type file !',
            'file.mimes'        => 'Format yang di izinkan xlsx, xls'
        ]);

        $file = $request->file('file');
        Excel::import(new MahasiswasImport, $file);
        return redirect('/mahasiswa')->with('success', 'Data berhasil diimpor!');
    }
}
