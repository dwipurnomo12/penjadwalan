<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DosenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dosen.index', [
            'dosens'    => User::where('role_id', '2')
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

        $dosens = User::where('role_id', '2')->when($prodiId, function ($query, $prodiId) {
            return $query->where('prodi_id', $prodiId);
        })->get();

        return view('dosen.index', compact('dosens', 'prodis'));
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
            'email'     => 'required|unique:users',
            'no_induk'  => 'required|unique:users',
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

        User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'username'      => $request->no_induk,
            'no_induk'      => $request->no_induk,
            'prodi_id'      => $request->prodi_id,
            'role_id'       => 2,
            'photo'         => $photo,
        ]);

        return redirect('/dosen')->with('success', 'Data berhasil ditambahkan !');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $dosen = User::where('role_id', 2)->findOrFail($id);
        return view('dosen.edit', [
            'dosen'     => $dosen,
            'prodis'    => ProgramStudi::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $dosen = User::where('role_id', 2)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'photo'     => 'mimes:png,jpg,jpeg',
            'name'      => 'required',
            'email'     => 'required|unique:users,email,' . $dosen->id,
            'no_induk'  => 'required|unique:users,no_induk,' . $dosen->id,
            'prodi_id'  => 'required',
            'password'  => 'nullable|min:4',
            'photo.mimes'           => 'Format photo yang diijinkan adalah png, jpg, jpeg',
            'name.required'         => 'Wajib diisi !',
            'email.required'        => 'Wajib diisi !',
            'email.unique'          => 'Email sudah terdaftar',
            'no_induk.required'     => 'Wajib diisi !',
            'no_induk.unique'       => 'No induk dosen sudah terdaftar',
            'prodi_id.required'     => 'Pilih Prodi !',
            'password.min'          => 'Password minimal 6 karakter',
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
            Storage::disk('public')->put('photo-profile/' . $fileName, file_get_contents($file));
            $photo = 'photo-profile/' . $fileName;
        }

        if ($request->filled('password')) {
            $dosen->password = bcrypt($request->password);
        }

        $dosen->update([
            'name'          => $request->name,
            'email'         => $request->email,
            'username'      => $request->no_induk,
            'no_induk'      => $request->no_induk,
            'prodi_id'      => $request->prodi_id,
            'photo'         => $photo,
        ]);

        if ($request->filled('password')) {
            $dosen->save();
        }

        return redirect('/dosen')->with('success', 'Data berhasil diupdate !');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dosen = User::where('role_id', 2)->findOrFail($id);

        if ($dosen->photo && Storage::disk('public')->exists($dosen->photo)) {
            Storage::disk('public')->delete($dosen->photo);
        }
        $dosen->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}