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
        <div class="modal fade" id="importDataDosen" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Import Data Mahasiswa</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('dosen.import') }}" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            @csrf
                            <div class="form-group">
                                <label for="file">Pilih file Excel</label>
                                <input type="file" name="file" class="form-control" accept=".xlsx, .xls">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a href="{{ asset('storage/excel/data_dosen.xlsx') }}"
                                class="btn btn-sm btn-success float-start" download="format_excel_dosen.xlsx">
                                Unduh Format .xlsx
                            </a>

                            <button type="submit" class="btn btn-sm btn-primary">Import</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-header bg-primary text-white">
            <div class="row">
                <div class="col-6">
                    Data user
                </div>
                <div class="col-6">
                    <a href="/dosen/create" class="btn btn-warning float-right m-1"><i class="fa fa-plus"></i> Data</a>
                    <button type="button" class="btn btn-success float-right m-1" data-toggle="modal"
                        data-target="#importDataDosen">
                        Import <i class="fa fa-file-excel"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
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
                        @foreach ($users as $user)
                            <tr>
                                <td class="text-left">{{ $loop->iteration }}</td>
                                <td class="text-left">
                                    @if ($user->dosen->photo)
                                        <img class="img-profile rounded-circle"
                                            src="{{ asset('storage/' . $user->dosen->photo) }}" alt="Photo"
                                            style="max-width: 50px">
                                    @else
                                        <img class="img-profile rounded-circle" src="/assets/img/profil.png" alt="Photo"
                                            style="max-width: 50px">
                                    @endif
                                </td>
                                <td class="text-left">{{ $user->dosen->name }}</td>
                                <td class="text-left">{{ $user->dosen->no_induk }}</td>
                                <td class="text-left">{{ $user->dosen->prodi->prodi }}</td>
                                <td>
                                    <a href="/dosen/{{ $user->id }}/edit" class="btn btn-warning m-1"><i
                                            class="fa fa-pencil-square"></i></a>
                                    <form id="{{ $user->id }}" action="/dosen/{{ $user->id }}" method="POST"
                                        class="d-inline">
                                        @method('delete')
                                        @csrf
                                        <button type="submit" class="btn btn-danger m-1 swal-confirm"
                                            data-form="{{ $user->id }}"><i class="fa fa-trash"></i></a></button>
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
