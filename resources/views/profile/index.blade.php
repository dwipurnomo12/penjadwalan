@extends('layouts.main')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Perbarui Profile</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Pengaturan</a></li>
            <li class="breadcrumb-item active" aria-current="page">Profile</li>
        </ol>
    </div>

    <form action="/profile/" method="POST" enctype="multipart/form-data">
        @method('put')
        @csrf
        @if (session()->has('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        <div class="row">
            <div class="col-md-8 mt-2">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        Profile Anda
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control" name="name" value="{{ $profile->name }}">
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="no_induk">No Induk</label>
                            <input type="number" class="form-control" name="no_induk" value="{{ $profile->no_induk }}">
                            @error('no_induk')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" class="form-control" name="email" value="{{ $profile->email }}">
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password" class="form-label">Password <span style="color: red"><i>
                                        (Kosongkan form jika tidak ingin mengubah password !)</i></span></label>
                            <input type="password" class="form-control" name="password" id="password"
                                value="{{ old('password') }}">
                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mt-2">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        Photo
                    </div>
                    <div class="card-body">
                        @if ($profile->photo)
                            <img src="{{ asset('storage/' . $profile->photo) }}" alt="Foto Profil" id="preview"
                                class="img-fluid rounded mb-5" width="100%" height="100%">
                        @else
                            <img src="/assets/img/profil.png" alt="Foto Profil" id="preview"
                                class="img-fluid rounded mb-5" width="100%" height="100%">
                            <p class="text-danger">Ini adalah foto Default, segera upload foto anda !</p>
                        @endif

                        <input type="file" class="form-control" name="photo" onchange="previewImage()">
                    </div>
                </div>
            </div>
            <div class="col">
                <button type="submit" class="btn btn-primary my-3 float-right">Perbarui Profile</button>
            </div>
        </div>
    </form>

    <script>
        function previewImage() {
            preview.src = URL.createObjectURL(event.target.files[0]);
        }
    </script>
@endsection
