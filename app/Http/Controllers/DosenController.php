<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dosen;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use App\Imports\DosensImport;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DosenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $prodiId = auth()->user()->tataUsaha->prodi->id;

        $users = User::where('role_id', 2)
            ->whereHas('dosen', function ($query) use ($prodiId) {
                $query->where('prodi_id', $prodiId);
            })
            ->orderBy('id', 'DESC')
            ->get();
        $prodis = ProgramStudi::all();

        return view('dosen.index', [
            'users'     => $users,
            'prodis'    => $prodis
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dosen.create', [
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
            'email'     => 'required|unique:dosens',
            'no_induk'  => 'required|unique:dosens',
            'prodi_id'  => 'required'
        ], [
            'photo.mimes'           => 'Format photo yang diijinkan adalah png, jpg, jpeg',
            'name.required'         => 'Wajib diisi !',
            'email.required'        => 'Wajib diisi !',
            'email.unique'          => 'Email sudah terdaftar',
            'no_induk.required'     => 'Wajib diisi !',
            'no_induk.unique'       => 'No induk dosen sudah terdaftar',
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
            'role_id'       => 2,
        ]);

        Dosen::create([
            'user_id'       => $user->id,
            'name'          => $request->name,
            'email'         => $request->email,
            'no_induk'      => $request->no_induk,
            'prodi_id'      => $request->prodi_id,
            'photo'         => $photo,
        ]);

        return redirect('/dosen')->with('success', 'Data berhasil ditambahkan !');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('dosen.edit', [
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
        $dosen = $user->dosen;

        $validator = Validator::make($request->all(), [
            'photo'     => 'nullable|mimes:png,jpg,jpeg',
            'name'      => 'required',
            'email'     => 'required|unique:dosens,email,' . $dosen->id,
            'no_induk'  => 'required|unique:dosens,no_induk,' . $dosen->id,
            'prodi_id'  => 'required',
            'password'  => 'nullable|min:4',
            'photo.mimes' => 'Format photo yang diijinkan adalah png, jpg, jpeg',
            'name.required' => 'Wajib diisi !',
            'email.required' => 'Wajib diisi !',
            'email.unique' => 'Email sudah terdaftar',
            'no_induk.required' => 'Wajib diisi !',
            'no_induk.unique' => 'No induk dosen sudah terdaftar',
            'prodi_id.required' => 'Pilih Prodi !',
            'password.min' => 'Password minimal 4 karakter',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $photo = $dosen->photo;
        if ($request->hasFile('photo')) {
            if ($dosen->photo && Storage::disk('public')->exists($dosen->photo)) {
                Storage::disk('public')->delete($dosen->photo);
            }

            $file = $request->file('photo');
            $fileName = $file->getClientOriginalName();
            $file->storeAs('public/photo-profile', $fileName);
            $photo = 'photo-profile/' . $fileName;
        }

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
            $user->save();
        }

        $user->update([
            'username' => $request->no_induk,
        ]);

        $dosen->update([
            'name' => $request->name,
            'email' => $request->email,
            'no_induk' => $request->no_induk,
            'prodi_id' => $request->prodi_id,
            'photo' => $photo,
        ]);

        return redirect('/dosen')->with('success', 'Data berhasil diupdate !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dosen = User::with('dosen')->where('role_id', 2)->findOrFail($id);

        if ($dosen->photo && Storage::disk('public')->exists($dosen->photo)) {
            Storage::disk('public')->delete($dosen->photo);
        }
        $dosen->delete();

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
        Excel::import(new DosensImport, $file);
        return redirect('/dosen')->with('success', 'Data berhasil diimpor!');
    }
}
