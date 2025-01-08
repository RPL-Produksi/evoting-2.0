@extends('layouts.app')
@section('title', 'Profile')

@push('css')
    {{-- Custom CSS for This Page --}}
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
        <div class="col-12 col-md-8 order-2 order-md-1">
            <div class="card h-100">
                <div class="card-header">
                    <h4 class="text-primary">Akun Saya</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST" class="form-with-loading">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="" class="form-label">Nama Lengkap</label>
                                    <input value="{{ $user->fullname }}" name="fullname" type="text" class="form-control"
                                        placeholder="Masukan nama lengkap" required>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="" class="form-label">Username</label>
                                    <input value="{{ $user->username }}" name="username" type="text" class="form-control"
                                        placeholder="Masukan username" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <div class="row align-items-center">
                                        <div class="col-10">
                                            <input value="{{ $user->unencrypted_password }}" type="password" id="password"
                                                class="form-control" name="password" placeholder="Masukkan password"
                                                required>
                                        </div>
                                        <div class="col-2">
                                            <button onclick="seePassword()" type="button" class="btn btn-primary"><i
                                                    class="fa-regular fa-eye" id="password-icon"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="" class="form-label">Role</label>
                                    <select disabled class="form-control" id="role-select">
                                        <option value="{{ $user->role }}" selected>{{ $user->role }}</option>
                                    </select>
                                </div>
                            </div>
                            @if ($user->role == 'siswa')
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="" class="form-label">Kelas</label>
                                        <select disabled class="form-control" id="kelas-select">
                                            <option value="{{ $user->kelas_id }}" selected>{{ $user->kelas->name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                            <div class="col-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success btn-loading float-right">
                                        <span class="btn-text">Simpan</span>
                                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 order-1 order-md-2 mb-2">
            <div class="card h-100">
                <div class="card-header">
                    <h4 class="text-primary text-center">Foto Profile</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update.image') }}" method="POST" enctype="multipart/form-data"
                        class="form-with-loading">
                        @csrf
                        <div class="row">
                            <div class="col-12 d-flex align-items-center justify-content-center">
                                @if ($user->profile_picture == null)
                                    <img src="{{ asset('assets/img/avatar-1.png') }}"
                                        class="img-fluid w-50 rounded-circle font-weight-bold"></img>
                                @else
                                    <img src="{{ asset('/storage/upload/' . $user->profile_picture) }}"
                                        class="img-fluid w-50 rounded-circle font-weight-bold"></img>
                                @endif
                            </div>
                            <div class="col-12 d-flex align-items-center justify-content-center mt-3">
                                <input onchange="this.form.submit()" class="d-none" type="file" name="image"
                                    id="image-input">
                                <button type="button" class="btn btn-primary btn-sm text-center" id="change-button">Ubah
                                    Profile</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    {{-- Custom JS for This Page --}}
    <script src="{{ asset('vendor/select2/dist/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#role-select").select2({
                theme: 'bootstrap-5',
                tags: true
            });

            $("#kelas-select").select2({
                theme: 'bootstrap-5',
                tags: true
            });
        })
    </script>
    <script>
        const seePassword = () => {
            const passwordInput = document.querySelector('#password');
            const passwordIcon = document.querySelector('#password-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('fa-eye');
                passwordIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('fa-eye-slash');
                passwordIcon.classList.add('fa-eye');
            }
        }
    </script>
    <script>
        const changeButton = document.getElementById('change-button');
        const imageInput = document.getElementById('image-input');

        changeButton.addEventListener('click', () => {
            imageInput.click();
        })
    </script>
@endpush
