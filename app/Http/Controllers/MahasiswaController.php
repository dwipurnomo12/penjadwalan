<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('mahasiswa.index', [
            'mahasiswas'    => User::where('role_id', '3')
                ->where('prodi_id', auth()->user()->prodi->id)
                ->orderBy('id', 'DESC')
                ->get(),
            'prodis'    => ProgramStudi::all()
        ]);
    }

    public function filterData(Request $request)
    {
        $prodiId    = $request->input('prodi_id');
        $prodis     = ProgramStudi::all();

        $mahasiswas = User::where('role_id', '3')->when($prodiId, function ($query, $prodiId) {
            return $query->where('prodi_id', $prodiId);
        })->get();

        return view('mahasiswa.index', compact('mahasiswas', 'prodis'));
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
            'photo'     => 'mimes:png,jpg,jpeg',
            'name'      => 'required',
            'email'     => 'required|unique:users',
            'no_induk'  => 'required|unique:users',
            'prodi_id'  => 'required'
        ], [
            'photo.mimes'           => 'Format photo yang diijinkan adalah png, jpg, jpeg',
            'name.required'         => 'Wajib diisi !',
            'email.required'        => 'Wajib diisi !',
            'email.unique'          => 'Email sudah terdaftar',
            'no_induk.required'     => 'Wajib diisi !',
            'no_induk.unique'       => 'No induk mahasiswa sudah terdaftar',
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

        User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'username'      => $request->no_induk,
            'no_induk'      => $request->no_induk,
            'prodi_id'      => $request->prodi_id,
            'role_id'       => 3,
            'photo'         => $photo,
        ]);

        return redirect('/mahasiswa')->with('success', 'Data berhasil ditambahkan !');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $mahasiswa = User::where('role_id', 3)->findOrFail($id);
        return view('mahasiswa.edit', [
            'mahasiswa'     => $mahasiswa,
            'prodis'        => ProgramStudi::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $mahasiswa = User::where('role_id', 3)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'photo'     => 'mimes:png,jpg,jpeg',
            'name'      => 'required',
            'email'     => 'required|unique:users,email,' . $mahasiswa->id,
            'no_induk'  => 'required|unique:users,no_induk,' . $mahasiswa->id,
            'prodi_id'  => 'required',
            'password'  => 'nullable|min:4',
            'photo.mimes'           => 'Format photo yang diijinkan adalah png, jpg, jpeg',
            'name.required'         => 'Wajib diisi !',
            'email.required'        => 'Wajib diisi !',
            'email.unique'          => 'Email sudah terdaftar',
            'no_induk.required'     => 'Wajib diisi !',
            'no_induk.unique'       => 'No induk mahasiswa sudah terdaftar',
            'prodi_id.required'     => 'Pilih Prodi !',
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
            $mahasiswa->password = bcrypt($request->password);
        }

        $mahasiswa->update([
            'name'          => $request->name,
            'email'         => $request->email,
            'username'      => $request->no_induk,
            'no_induk'      => $request->no_induk,
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
}
