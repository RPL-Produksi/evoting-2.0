@extends('layouts.app')
@section('title', 'Manage Kandidat')

@push('css')
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
                            <h4 class="text-primary card-title">Daftar Kandidat | {{ $pemilu->name }}</h4>
                        </div>
                        <div class="ml-auto">
                            <button data-target="#addKandidatModal" data-toggle="modal" class="btn btn-success mr-1">
                                <i class="fa-regular fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered w-100 nowrap" id="table-1">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Nama Kandidat</th>
                                <th>Foto Kandidat</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kandidat as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>
                                        <a type="button" href="{{ asset('storage/upload/' . $item->image) }}"
                                            onclick="previewImage(this.href)" class="btn btn-success w-100"
                                            data-toggle="modal" data-target="#previewImageModal">
                                            <i class="fa-regular fa-eye"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <button onclick="edit('{{ $pemilu->slug }}', {{ $item->id }})"
                                            class="btn btn-primary"><i class="fa-regular fa-edit"></i></button>
                                        <a href="{{ route('admin.manage.pemilu.kandidat.delete', [$pemilu->slug, $item->id]) }}"
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

    <div class="modal fade" id="addKandidatModal" tabindex="-1" role="dialog" aria-labelledby="addKandidatModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addKandidatModalLabel">Tambah Kandidat</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('admin.manage.pemilu.kandidat.add', $pemilu->slug) }}" method="POST"
                    enctype="multipart/form-data" class="form-with-loading">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nama Kandidat</label>
                            <input type="text" class="form-control" name="name" id="name"
                                placeholder="Masukkan nama kandidat" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control" name="description" id="description" cols="30" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="vision_mission">Visi & Misi</label>
                            <textarea class="form-control" name="vision_mission" id="vision_mission" cols="30" rows="5" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="image">Foto Kandidat</label>
                            <input type="file" name="image" id="image" class="form-control" required>
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

    <div class="modal fade" id="editKandidatModal" tabindex="-1" role="dialog" aria-labelledby="editKandidatModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editKandidatModalLabel">Edit Kandidat</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="editKandidatForm" action="" method="POST" enctype="multipart/form-data"
                    class="form-with-loading">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nama Kandidat</label>
                            <input type="text" class="form-control" name="name" id="edit-name"
                                placeholder="Masukkan nama kandidat" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control" name="description" id="edit-description" cols="30" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="vision_mission">Visi & Misi</label>
                            <textarea class="form-control" name="vision_mission" id="edit-vision_mission" cols="30" rows="5"
                                required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="image">Foto Kandidat</label>
                            <p class="text-danger">*Jika tidak ingin mengubah, jangan di isi</p>
                            <input type="file" name="image" id="image" class="form-control">
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

    <div class="modal fade" id="previewImageModal" tabindex="-1" role="dialog"
        aria-labelledby="previewImageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewImageModalLabel">Preview Gambar</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center justify-content-center">
                        <img src="" alt="Image Preview" class="img-fluid rounded-lg" loading="lazy">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-link" type="button" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('vendor/DataTables/datatables.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#table-1').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.kandidat.data', ['slug' => $pemilu->slug]) }}",
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
                        data: 'image',
                        orderable: false,
                        render: function(data, type, row, meta) {
                            return `<a type="button" href="{{ asset('storage/upload/' . '${data}') }}" onclick="previewImage(this.href)" class="btn btn-success w-100" data-toggle="modal" data-target="#previewImageModal"><i class="fa-regular fa-eye"></i></a>`;
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function(data, type, row, meta) {
                            const deleteUrl =
                                `{{ route('admin.manage.pemilu.kandidat.delete', [':slug', ':id']) }}`;

                            let editBtn =
                                `<a onclick="edit('${row.pemilu.slug}', '${row.id}')" class="btn btn-primary mr-1"><i class="fa-regular fa-edit"></i></a>`;
                            let deleteBtn =
                                `<a href="${deleteUrl.replace(':slug', row.pemilu.slug).replace(':id', row.id)}" class="btn btn-danger" data-confirm-delete="true"><i class="fa-regular fa-trash"></i></a>`;
                            return `${editBtn}${deleteBtn}`;
                        }
                    }
                ],
            });
        });
    </script>
    <script>
        const previewImage = (src) => {
            const modal = document.getElementById('previewImageModal');
            const img = modal.querySelector('img');
            img.src = src;
        }
    </script>
    <script>
        const edit = (slug, id) => {
            $.getJSON(`${window.location.origin}/admin/manage/pemilu/${slug}/kandidat/${id}/data`, (data) => {
                const updateUrl = `{{ route('admin.manage.pemilu.kandidat.update', [':slug', ':id']) }}`
                $('#editKandidatForm').attr('action', updateUrl.replace(':slug', slug).replace(':id', id))

                $('#edit-name').val(data.name);
                $('#edit-description').val(data.description);
                $('#edit-vision_mission').val(data.vision_mission);

                const myModal = new bootstrap.Modal(document.getElementById('editKandidatModal'));
                myModal.show();
            })
        }
    </script>
@endpush
