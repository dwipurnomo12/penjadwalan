<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $profile = null;

        if ($user->role->role == 'dosen') {
            $profile = $user->dosen;
        } elseif ($user->role->role == 'mahasiswa') {
            $profile = $user->mahasiswa;
        } elseif ($user->role->role == 'tata usaha') {
            $profile = $user->tataUsaha;
        }

        return view('profile.index', [
            'profile' => $profile,
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $profile = null;

        if ($user->role->role == 'dosen') {
            $profile = $user->dosen;
        } elseif ($user->role->role == 'mahasiswa') {
            $profile = $user->mahasiswa;
        } elseif ($user->role->role == 'tata usaha') {
            $profile = $user->tataUsaha;
        }

        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required',
            'no_induk'  => 'required',
            'photo'     => 'image|mimes:jpeg,jpg,png|max:2048'
        ], [
            'name.required'     => 'Form tidak boleh kosong!',
            'email.required'    => 'Form tidak boleh kosong!',
            'no_induk.required' => 'Form tidak boleh kosong!',
            'photo.image'       => 'Unggah foto!',
            'photo.mimes'       => 'Format yang diizinkan jpeg, jpg, png',
            'photo.max'         => 'Ukuran maksimal gambar 2 MB'
        ]);

        if ($request->email != $profile->email) {
            $validator = Validator::make($request->all(), [
                'email'     => 'unique:users',
                'no_induk'  => 'unique:users'
            ], [
                'email.unique'        => 'email sudah digunakan !',
                'no_induk.unique'     => 'no_induk sudah digunakan !',
            ]);
        }

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $request->only('name', 'email', 'no_induk');
        $photo = null;

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('photo')) {
            if ($profile->photo) {
                Storage::delete($profile->photo);
            }

            $file = $request->file('photo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/photo-profile', $fileName);

            $photo = 'photo-profile/' . $fileName;
        }

        $user->update([
            'username'  => $request->no_induk,
            'password'  => $validated['password'] ?? $user->password
        ]);


        $profile->update([
            'name'      => $request->name,
            'email'     => $request->email,
            'no_induk'  => $request->no_induk,
            'photo'     => $photo,
        ]);
        return redirect()->back()->with('success', 'Berhasil memperbarui profil!');
    }
}
