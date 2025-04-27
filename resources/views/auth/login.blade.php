@extends('layouts.auth')
@section('title', 'Login')

@push('css')
    {{-- Custom CSS for This Page --}}
    <style>
        .box-area {
            width: 930px;
        }

        .right-box {
            padding: 40px 30px 40px 40px;
        }

        ::placeholder {
            font-size: 16px;
        }

        .rounded-4 {
            border-radius: 20px;
        }

        .rounded-5 {
            border-radius: 30px;
        }

        @media only screen and (max-width: 768px) {
            .box-area {
                margin: 0 10px;
            }

            .left-box {
                height: 100px;
                overflow: hidden;
            }

            .right-box {
                padding: 20px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="row border rounded-5 p-3 bg-white shadow box-area">
            <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box bg-primary">
                <div class="featured-image mb-3">
                    <img src="{{ asset('assets/img/smkn-2.png') }}" class="img-fluid d-none d-md-block" style="width: 250px;">
                </div>
                <p class="text-white fs-2" style="font-family: 'Courier New', Courier, monospace; font-weight: 600;">E-Voting
                </p>
                <small class="text-white text-wrap text-center"
                    style="width: 17rem;font-family: 'Courier New', Courier, monospace;">Simple E-Voting For SMK Negeri 2
                    Sukabumi</small>
            </div>

            <div class="col-md-6 right-box">
                <div class="row align-items-center">
                    <div class="header-text mb-4">
                        <h2>Login |
                            @if ($adminLoggedIn)
                                Admin
                            @else
                                E-Voting <sup>2.0</sup>
                            @endif
                        </h2>
                        <p>Silakan login untuk mulai memilih</p>
                    </div>
                    <form action="{{ route('post.login', $adminLoggedIn) }}" method="POST" class="w-100 form-with-loading">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="text" class="form-control form-control-lg bg-light fs-6" placeholder="Username"
                                name="username" required>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control form-control-lg bg-light fs-6" placeholder="Password"
                                name="password" required>
                        </div>
                        @if ($errors->any())
                            <div class="text-left alert alert-danger border-left-danger" role="alert">
                                <ul class="pl-4 my-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (Session::has('error'))
                            <div class="alert alert-danger fade show w-100 mb-5" role="alert">
                                {{ Session::get('error') }}
                            </div>
                        @endif
                        <div class="input-group mb-3">
                            <button type="submit" class="btn btn-lg btn-primary w-100 fs-6 btn-loading">
                                <span class="btn-text">Login</span>
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    {{-- Custom JS for This Page --}}
@endpush
