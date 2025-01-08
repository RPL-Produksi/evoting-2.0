@extends('layouts.app')
@section('title', 'Manage Users')

@push('css')
    {{-- Custom CSS for This Page --}}
    <link rel="stylesheet" href="{{ asset('vendor/DataTables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/select2/dist/css/select2-bootstrap-5-theme.css') }}" />
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
                            <h4 class="text-primary card-title">Kelola Users</h3>
                        </div>
                        <button data-target="#addUserModal" data-toggle="modal" class="btn btn-success mr-1"><i
                                class="fa-regular fa-plus"></i></button>
                        <button data-target="#exportUserModal" data-toggle="modal" class="btn btn-danger mr-1"><i
                                class="fa-regular fa-file-export"></i></button>
                        <button data-target="#importUserModal" data-toggle="modal" class="btn btn-warning mr-1"><i
                                class="fa-regular fa-file-import"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered w-100 nowrap" id="table-1">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Nama Lengkap</th>
                                <th>Username</th>
                                <th>Password</th>
                                <th>Kelas</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($user as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $item->fullname }}</td>
                                    <td>{{ $item->username }}</td>
                                    <td>{{ $item->unencrypted_password }}</td>
                                    <td>{{ $item->kelas->name ?? 'N/A' }}</td>
                                    <td class="text-capitalize">{{ $item->role }}</td>
                                    <td>
                                        <button onclick="edit({{ $item->id }})" class="btn btn-primary"><i
                                                class="fa-regular fa-edit"></i></button>
                                        <a href="{{ route('admin.manage.user.delete', $item->username) }}"
                                            class="btn btn-danger" data-confirm-delete="true"><i
                                                class="fa-regular fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Tambah User</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('admin.manage.user.add') }}" method="POST" class="form-with-loading">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nama Lengkap</label>
                            <input type="text" class="form-control" name="fullname" id="fullname"
                                placeholder="Masukkan nama lengkap" required>
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <div class="row align-items-center">
                                <div class="col-10">
                                    <input id="create-username" type="text" class="form-control" name="username"
                                        id="username" placeholder="Masukkan username" required>
                                </div>
                                <div class="col-2">
                                    <button onclick="$('#create-username').val(randomText(8))" type="button"
                                        class="btn btn-primary"><i class="fa-regular fa-shuffle"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="row align-items-center">
                                <div class="col-10">
                                    <input id="create-password" type="text" class="form-control" name="password"
                                        id="password" placeholder="Masukkan password" required>
                                </div>
                                <div class="col-2">
                                    <button onclick="$('#create-password').val(randomText(8))" type="button"
                                        class="btn btn-primary"><i class="fa-regular fa-shuffle"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select name="role" id="add-role-select" class="form-control" required>
                                <option value="">Pilih Role</option>
                                <option value="siswa">Siswa</option>
                                <option value="guru">Guru</option>
                                <option value="caraka">Caraka</option>
                            </select>
                        </div>
                        <div class="form-group d-none" id="add-user-group">
                            <label for="kelas">Kelas</label>
                            <select name="kelas_id" id="add-kelas-select" class="form-control">
                                <option value="">Pilih Kelas</option>
                                @foreach ($kelas as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
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

    <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="editUserForm" action="" method="POST" class="form-with-loading">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="fullname">Nama Lengkap</label>
                            <input type="text" class="form-control" name="fullname" id="edit-fullname"
                                placeholder="Masukkan nama lengkap" required>
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <div class="row align-items-center">
                                <div class="col-10">
                                    <input id="edit-username" type="text" class="form-control" name="username"
                                        id="username" placeholder="Masukkan username" required>
                                </div>
                                <div class="col-2">
                                    <button onclick="$('#edit-username').val(randomText(8))" type="button"
                                        class="btn btn-primary"><i class="fa-regular fa-shuffle"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="row align-items-center">
                                <div class="col-10">
                                    <input id="edit-password" type="text" class="form-control" name="password"
                                        placeholder="Masukkan password" required>
                                </div>
                                <div class="col-2">
                                    <button onclick="$('#edit-password').val(randomText(8))" type="button"
                                        class="btn btn-primary"><i class="fa-regular fa-shuffle"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select name="role" id="edit-role-select" class="form-control" required>
                                <option value="">Pilih Role</option>
                                <option value="siswa">Siswa</option>
                                <option value="guru">Guru</option>
                                <option value="caraka">Caraka</option>
                            </select>
                        </div>
                        <div class="form-group d-none" id="edit-user-group">
                            <label for="kelas">Kelas</label>
                            <select name="kelas_id" id="edit-kelas-select" class="form-control">
                                <option value="">Pilih Kelas</option>
                                @foreach ($kelas as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
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

    <div class="modal fade" id="importUserModal" tabindex="-1" role="dialog" aria-labelledby="importUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importUserModalLabel">Import Data User</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('admin.manage.user.import') }}" method="POST" enctype="multipart/form-data"
                    class="form-with-loading">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select name="role" id="import-role-select" class="form-control" required>
                                <option value="">Pilih Role</option>
                                <option value="siswa">Siswa</option>
                                <option value="guru">Guru</option>
                                <option value="caraka">Caraka</option>
                            </select>
                        </div>
                        <div class="form-group d-none" id="import-user-group">
                            <label for="kelas">Kelas</label>
                            <select name="kelas_id" id="import-kelas-select" class="form-control">
                                <option value="">Pilih Kelas</option>
                                @foreach ($kelas as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="file">File CSV | Excel</label>
                            <input type="file" name="file" id="file" class="form-control" required>
                        </div>
                        <div class="form-group align-items-center">
                            <label for="table">Contoh Table</label>
                            <span class="text-danger">*Tidak di beri header</span>
                            <a href="{{ asset('assets/example-import/Data User (Contoh).xlsx') }}"
                                class="btn btn-danger float-right mb-2"><i class="fa-regular fa-download"></i></a>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td class="text-center">1</td>
                                            <td>John Doe</td>
                                            <td>JHN123 (Opsional)</td>
                                            <td>DOE123 (Opsional)</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">...</td>
                                            <td>....</td>
                                            <td>...</td>
                                            <td>...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
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

    <div class="modal fade" id="exportUserModal" tabindex="-1" role="dialog" aria-labelledby="exportUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exportUserModalLabel">Export Data User</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('admin.manage.user.export') }}" method="GET" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select name="role" id="export-role-select" class="form-control" required>
                                <option value="">Pilih Role</option>
                                <option value="siswa">Siswa</option>
                                <option value="guru">Guru</option>
                                <option value="caraka">Caraka</option>
                            </select>
                        </div>
                        <div class="form-group d-none" id="export-user-group">
                            <label for="kelas">Kelas</label>
                            <select name="kelas_id" id="export-kelas-select" class="form-control">
                                <option value="">Pilih Kelas</option>
                                @foreach ($kelas as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-link" type="button" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Download</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    {{-- Custom JS for This Page --}}
    <script src="{{ asset('vendor/DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('vendor/select2/dist/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#table-1').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                scrollX: true,
                ajax: {
                    url: "{{ route('admin.user.data') }}",
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
                        data: 'fullname',
                        orderable: true,
                    },
                    {
                        data: 'username',
                        orderable: false,
                    },
                    {
                        data: 'unencrypted_password',
                        orderable: false,
                    },
                    {
                        data: 'kelas.name',
                        orderable: false,
                        render: function(data, type, row) {
                            return data ? data : 'N/A';
                        }
                    },
                    {
                        data: 'role',
                        className: 'text-capitalize',
                        orderable: true,
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row, meta) {
                            const deleteUrl =
                                `{{ route('admin.manage.user.delete', ':username') }}`;

                            let editBtn =
                                `<a onclick="edit('${row.id}')" class="btn btn-primary mr-1"><i class="fa-regular fa-edit"></i></a>`;
                            let deleteBtn =
                                `<a href="${deleteUrl.replace(':username', row.username)}" class="btn btn-danger" data-confirm-delete="true"><i class="fa-regular fa-trash"></i></a>`;
                            return `${editBtn}${deleteBtn}`;
                        }
                    }
                ],
            });

            $("#add-role-select").select2({
                theme: 'bootstrap-5',
                tags: true
            });

            $("#add-kelas-select").select2({
                theme: 'bootstrap-5',
                tags: true
            });

            $("#import-kelas-select").select2({
                theme: 'bootstrap-5',
                tags: true
            });

            $("#import-role-select").select2({
                theme: 'bootstrap-5',
                tags: true
            });

            $("#export-role-select").select2({
                theme: 'bootstrap-5',
                tags: true
            });

            $("#export-kelas-select").select2({
                theme: 'bootstrap-5',
                tags: true
            });

            $('#add-role-select').on('change', function() {
                if ($(this).val() === 'siswa') {
                    $('#add-user-group').removeClass('d-none');
                } else {
                    $('#add-user-group').addClass('d-none');
                }
            });

            $('#edit-role-select').on('change', function() {
                if ($(this).val() === 'siswa') {
                    $('#edit-user-group').removeClass('d-none');
                } else {
                    $('#edit-user-group').addClass('d-none');
                }
            });

            $('#import-role-select').on('change', function() {
                if ($(this).val() === 'siswa') {
                    $('#import-user-group').removeClass('d-none');
                } else {
                    $('#import-user-group').addClass('d-none');
                }
            });

            $('#export-role-select').on('change', function() {
                if ($(this).val() === 'siswa') {
                    $('#export-user-group').removeClass('d-none');
                } else {
                    $('#export-user-group').addClass('d-none');
                }
            });
        });
    </script>
    <script>
        const edit = (id) => {
            $.getJSON(`${window.location.origin}/admin/manage/users/${id}/data`, (data) => {
                const updateUrl = `{{ route('admin.manage.user.update', ':id') }}`

                $('#editUserForm').attr('action', updateUrl.replace(':id', id));

                $('#edit-fullname').val(data.fullname);
                $('#edit-username').val(data.username);
                $('#edit-password').val(data.unencrypted_password);
                $('#edit-kelas-select').select2({
                    theme: 'bootstrap-5',
                }).val(data.kelas_id).trigger('change');

                $('#edit-role-select').select2({
                    theme: 'bootstrap-5',
                }).val(data.role).trigger('change');

                const myModal = new bootstrap.Modal(document.getElementById('editUserModal'));
                myModal.show();
            })
        }
    </script>
    <script>
        const randomText = (length) => {
            var result = '';
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            var charactersLength = characters.length;
            for (var i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            return result;
        }
    </script>
@endpush
