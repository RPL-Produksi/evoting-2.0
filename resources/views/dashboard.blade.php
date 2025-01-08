@extends('layouts.app')
@section('title', 'Dashboard')

@push('css')
    {{-- Custom CSS for This Page --}}
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
    </div>

    @if (auth()->user()->role == 'admin')
        <div class="row">
            @if (Session::has('status'))
                <div class="col-12">
                    <div class="alert alert-success border-left-success" role="alert">
                        {{ session('status') }}
                    </div>
                </div>
            @endif
            <div class="col-12">
                <div class="alert alert-primary collapsed" role="button" data-toggle="collapse"
                    data-target="#changelogItems" aria-expanded="false">
                    Changelog
                    <ul class="collapse" id="changelogItems" style="margin-left: 30px;">
                        <li>Perbaikan Loader</li>
                        <li>Perbaikan Fitur Submit Form</li>
                        <li>Perbaikan Login</li>
                        <li>Menambah Fitur Profile</li>
                        <li>Menambah Password Pada Pemilu Yang Private</li>
                    </ul>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header" id="collapseVotingAktif">
                        <div class="row">
                            <div class="col">
                                <h4 class="text-primary card-title">Daftar Pemilu Yang Sedang Aktif</h4>
                            </div>
                            <div class="ml-auto">
                                <button class="btn btn-primary" data-toggle="collapse" data-target="#collapseBody"
                                    aria-expanded="true" aria-controls="collapseBody" onclick="toggleIcon()">
                                    <i class="fa-regular fa-plus" id="icon-button"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="collapseBody" class="collapse" aria-labelledby="collapseVotingAktif"
                        data-parent="#collapseVotingAktif">
                        <div class="card-body">
                            <div class="row">
                                @foreach ($pemilu as $item)
                                    <div class="col-12 col-md-6 col-lg-4 mt-3">
                                        <div class="card h-100">
                                            <div class="card-header bg-primary">
                                                <h5 class="card-title text-white">{{ $item->name }}</h5>
                                            </div>
                                            <div class="card-body">
                                                <p class="card-text">{{ $item->description }}</p>
                                            </div>
                                            <div class="card-footer">
                                                @if ($item->is_private)
                                                    <button onclick="verify('{{ $item->slug }}')"
                                                        class="btn btn-primary float-right"><i
                                                            class="fa-regular fa-door-open mr-1"></i>Masuk</button>
                                                @else
                                                    <a href="{{ route('user.pemilu.join', $item->slug) }}"
                                                        class="btn btn-primary float-right"><i
                                                            class="fa-regular fa-door-open mr-1"></i>Masuk</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="verifyPasswordModal" tabindex="-1" role="dialog"
            aria-labelledby="verifyPasswordModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="verifyPasswordModalLabel">Verifikasi Password Pemilu</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form action="" method="POST" id="verifyPasswordForm" class="form-with-loading">
                        <div class="modal-body">
                            @csrf
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input id="password" type="text" class="form-control" name="password"
                                    placeholder="Masukkan password" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-link" type="button" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success btn-loading">
                                <span class="btn-text">Verifikasi</span>
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('js')
    {{-- Custom JS for This Page --}}
    <script>
        const toggleIcon = () => {
            const iconButton = document.getElementById('icon-button');
            const collapseBody = document.getElementById('collapseBody');
            const isExpanded = collapseBody.classList.contains('show');

            if (isExpanded) {
                iconButton.classList.remove('fa-minus');
                iconButton.classList.add('fa-plus');
            } else {
                iconButton.classList.remove('fa-plus');
                iconButton.classList.add('fa-minus');
            }
        }
    </script>
    <script>
        const verify = (slug) => {
            console.log(slug)
            const verifyUrl = `{{ route('user.pemilu.verify-password.join', [':slug']) }}`

            $('#verifyPasswordForm').attr('action', verifyUrl.replace(':slug', slug))
            const myModal = new bootstrap.Modal(document.getElementById('verifyPasswordModal'));
            myModal.show();
        }
    </script>
    <script src="{{ asset('../../public/js/loader.js') }}"></script>
@endpush
