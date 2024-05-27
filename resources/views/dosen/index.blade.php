@extends('layouts.main')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dosen</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Data User</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dosen</li>
        </ol>
    </div>

    @if (session()->has('success'))
        <div class="card bg-success text-white my-2">
            <div class="card-body">
                {{ session('success') }}
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-header bg-primary text-white">
            <div class="row">
                <div class="col-6">
                    Data Dosen
                </div>
                <div class="col-6">
                    <a href="/dosen/create" class="btn btn-warning float-right">Tambah Data</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="form-group">
                <form action="/dosen/filter-data" method="GET">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="text">Filter Berdasarkan Prodi</label>
                                <div class="input-group">
                                    <select class="form-control" aria-label="Default select example" name="prodi_id">
                                        <option value="">Pilih Prodi</option>
                                        @foreach ($prodis as $prodi)
                                            <option value="{{ $prodi->id }}"
                                                {{ request('prodi_id') == $prodi->id ? 'selected' : '' }}>
                                                {{ $prodi->prodi }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="ml-2 mt-1">
                                        <button type="submit" class="btn btn-sm btn-primary"><i
                                                class="fa-solid fa-magnifying-glass"></i> Filter</button>

                                        <a href="/dosen/" class="btn btn-sm btn-danger ml-1" id="refresh_btn"><i
                                                class="fa fa-solid fa-rotate-right"></i>
                                            Refresh</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table id="table_id" class="table table-bordered table-hover table-striped table-condensed">
                        <thead>
                            <tr>
                                <th class="text-left">No</th>
                                <th class="text-left">Photo</th>
                                <th class="text-left">Nama</th>
                                <th class="text-left">NIDN</th>
                                <th class="text-left">Program Studi</th>
                                <th class="text-left">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dosens as $dosen)
                                <tr>
                                    <td class="text-left">{{ $loop->iteration }}</td>
                                    <td class="text-left">
                                        @if ($dosen->photo)
                                            <img class="img-profile rounded-circle"
                                                src="{{ asset('storage/' . $dosen->photo) }}" alt="Photo"
                                                style="max-width: 50px">
                                        @else
                                            <img class="img-profile rounded-circle" src="/assets/img/profil.png"
                                                alt="Photo" style="max-width: 50px">
                                        @endif
                                    </td>
                                    <td class="text-left">{{ $dosen->name }}</td>
                                    <td class="text-left">{{ $dosen->no_induk }}</td>
                                    <td class="text-left">{{ $dosen->prodi->prodi }}</td>
                                    <td>
                                        <a href="/dosen/{{ $dosen->id }}/edit" class="btn btn-warning m-1"><i
                                                class="fa fa-pencil-square"></i></a>
                                        <form id="{{ $dosen->id }}" action="/dosen/{{ $dosen->id }}" method="POST"
                                            class="d-inline">
                                            @method('delete')
                                            @csrf
                                            <button type="submit" class="btn btn-danger m-1 swal-confirm"
                                                data-form="{{ $dosen->id }}"><i class="fa fa-trash"></i></a></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Datatables Jquery -->
        <script>
            $(document).ready(function() {
                $('#table_id').DataTable();
            });
        </script>
    @endsection
