@extends('layouts.app')
@section('title', 'Manage Class')

@push('css')
    {{-- Custom CSS for This Page --}}
    <link rel="stylesheet" href="{{ asset('vendor/DataTables/datatables.min.css') }}">
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            @if ($errors->any())
                <div class="alert alert-danger border-left-danger" role="alert">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </div>
            @endif

            @if (Session::has('success'))
                <div class="alert alert-success border-left-success" role="alert">
                    {{ Session::get('success') }}
                </div>
            @endif

            @if (Session::has('error'))
                <div class="alert alert-danger border-left-danger" role="alert">
                    {{ Session::get('error') }}
                </div>
            @endif
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            <h4 class="text-primary card-title">Kelola Kelas</h4>
                        </div>
                        <button data-target="#addClassModal" data-toggle="modal" class="btn btn-success mr-1"><i
                                class="fa-regular fa-plus"></i></button>
                        <button data-target="#importClassModal" data-toggle="modal" class="btn btn-warning mr-1"><i
                                class="fa-regular fa-file-import"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered w-100 nowrap" id="table-1">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Nama</th>
                                <th>Slug</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addClassModal" tabindex="-1" role="dialog" aria-labelledby="addClassModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addClassModalLabel">Tambah Kelas</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('admin.manage.class.add') }}" method="POST" class="form-with-loading">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nama Kelas</label>
                            <input type="text" class="form-control" name="name" id="name"
                                placeholder="Masukkan nama kelas" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-link" type="button" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success btn-loading">
                            <span class="btn-text">Tambah</span>
                            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editClassModal" tabindex="-1" role="dialog" aria-labelledby="editClassModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editClassModalLabel">Edit Kelas</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="editClassForm" action="" method="POST" class="form-with-loading">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nama Kelas</label>
                            <input id="edit-name" type="text" class="form-control" name="name" id="name"
                                placeholder="Masukkan nama kelas" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-link" type="button" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success btn-loading">
                            <span class="btn-text">Ubah</span>
                            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="importClassModal" tabindex="-1" role="dialog" aria-labelledby="importClassModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importClassModalLabel">Import Data Kelas</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('admin.manage.class.import') }}" enctype="multipart/form-data" method="POST"
                    class="form-with-loading">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="name">File CSV | Excel</label>
                            <input type="file" name="file" id="file" class="form-control"
                                accept=".csv, .xlsx, .xls" required>
                        </div>
                        <div class="form-group">
                            <label for="table">Contoh Table</label>
                            <span class="text-danger">*Tidak di beri header</span>
                            <a href="{{ asset('assets/example-import/Data Kelas (Contoh).xlsx') }}"
                                class="btn btn-danger float-right mb-2"><i class="fa-regular fa-download"></i></a>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td class="text-center">1</td>
                                        <td>10 AKL 1</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">...</td>
                                        <td>...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-link" type="button" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success btn-loading">
                            <span class="btn-text">Import</span>
                            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    {{-- Custom JS for This Page --}}
    <script src="{{ asset('vendor/DataTables/datatables.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#table-1').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.class.data') }}",
                    data: function(e) {
                        return e;
                    }
                },
                order: [
                    [0, 'desc']
                ],
                columns: [{
                        data: null,
                        className: 'text-center',
                        orderable: false,
                        render: function(data, type, row, meta) {
                            let pageInfo = $('#table-1').DataTable().page.info();
                            return meta.row + 1 + pageInfo.start;
                        }
                    },
                    {
                        data: 'name',
                        orderable: true,
                    },
                    {
                        data: 'slug',
                        orderable: false,
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row, meta) {
                            const deleteUrl = `{{ route('admin.manage.class.delete', ':slug') }}`;

                            let editBtn =
                                `<a onclick="edit('${row.id}')" class="btn btn-primary mr-1"><i class="fa-regular fa-edit"></i></a>`;
                            let deleteBtn =
                                `<a href="${deleteUrl.replace(':slug', row.slug)}" class="btn btn-danger" data-confirm-delete="true"><i class="fa-regular fa-trash"></i></a>`;
                            return `${editBtn}${deleteBtn}`;
                        }
                    }
                ],
            });
        });
    </script>
    <script>
        const edit = (id) => {
            $.getJSON(`${window.location.origin}/admin/manage/class/${id}/data`, (data) => {
                const updateUrl = `{{ route('admin.manage.class.update', ':id') }}`

                $('#editClassForm').attr('action', updateUrl.replace(':id', id))
                $('#edit-name').val(data.name);

                const myModal = new bootstrap.Modal(document.getElementById('editClassModal'));
                myModal.show();
            })
        }
    </script>
@endpush
