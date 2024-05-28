@extends('layouts.main')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Mahasiswa</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/mahasiswa">Mahasiswa</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Data</li>
        </ol>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <div class="row">
                <div class="col-6">
                    Edit Data mahasiswa
                </div>
                <div class="col-6">
                    <a href="/mahasiswa/" class="btn btn-warning float-right">Kembali</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="/mahasiswa/{{ $user->id }}" method="POST" enctype="multipart/form-data">
                @method('put')
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="photo">Photo Profil</label><br>
                            @if ($user->mahasiswa->photo)
                                <img src="{{ $user->mahasiswa->photo ? asset('storage/' . $user->mahasiswa->photo) : '' }}"
                                    class="img-preview img-fluid mb-3 mt-2" id="preview"
                                    style="max-height: 250px; overflow:hidden; border: 1px solid black;">
                            @else
                                <p>Tidak foto yang di upload</p>
                                <img src="" class="img-preview img-fluid mb-3 mt-2" id="preview"
                                    style="max-height: 250px; overflow:hidden; border: 1px solid black;">
                            @endif
                            <input type="file" class="form-control" name="photo" onchange="previewImage()">
                            @error('photo')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="name">Nama mahasiswa <span style="color: red">*</span></label>
                            <input type="text" class="form-control" name="name"
                                value="{{ old('name', $user->mahasiswa->name) }}">
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="name">Email <span style="color: red">*</span></label>
                            <input type="email" class="form-control" name="email"
                                value="{{ old('email', $user->mahasiswa->email) }}">
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="no_induk">NIDN <span style="color: red">*</span></label>
                            <input type="number" class="form-control" name="no_induk"
                                value="{{ old('no_induk', $user->mahasiswa->no_induk) }}">
                            @error('no_induk')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="prodi_id">Program Studi <span style="color: red">*</span></label>
                            <select name="prodi_id" id="prodi_id" class="form-control">
                                <option value="">Pilih Program Studi</option>
                                @foreach ($prodis as $prodi)
                                    <option value="{{ $prodi->id }}" @if ($user->mahasiswa->prodi_id == $prodi->id) selected @endif>
                                        {{ $prodi->prodi }}
                                    </option>
                                @endforeach
                            </select>
                            @error('prodi_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="thn_angkatan">Tahun Angkatan <span style="color: red">*</span></label>
                            <input type="number" class="form-control" name="thn_angkatan"
                                value="{{ old('thn_angkatan', $user->mahasiswa->thn_angkatan) }}">
                            @error('thn_angkatan')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password">Reset Password </label>
                            <input type="password" class="form-control" name="password"
                                placeholder="Kosongi form jika tidak ingin reset password !">
                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary float-right">Update</button>
            </form>
        </div>
    </div>

    <script>
        function previewImage() {
            preview.src = URL.createObjectURL(event.target.files[0]);
        }
    </script>
@endsection
