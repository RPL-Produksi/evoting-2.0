@extends('layouts.auth')
@section('title', 'Login')

@push('css')
    {{-- Custom CSS for This Page --}}
    <style>
        .box-area {
            width: 930px;
        }

        .right-box {
            padding: 40px 70px 40px 0px;
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

        .box-logo {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .center {
            margin-bottom: 1.5rem;
        }

        .info {
            width: fit-content;
            background: white;
            padding: 10px;
            color: #4e73df;
            margin-top: 10px;
            border-radius: 10px;
            box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px;
        }

        .container {
            flex-wrap: wrap;
        }

        @media screen and (min-width: 768px) {
            .box-logo>img {
                width: 64%;
                filter: drop-shadow(-5px 5px 5px #4e73df);
            }

        }

        @media only screen and (max-width: 768px) {
            .box-area {
                margin: 0 10px;
            }

            .left-box {
                /* height: 100px; */
                /* overflow: hidden; */
            }

            .box-logo {
                margin-top: 24px
            }

            .box-logo>img {
                width: 64%;
                filter: drop-shadow();
            }

            .right-box {
                padding: 20px;
            }

            .center {
                margin-bottom: .5rem;
                margin-top: 1.2rem;
                text-align: center;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container d-flex justify-content-center align-items-center">
        <div class="row border rounded-5 p-3 bg-white shadow box-area">
            <div class="left-box box-logo col-md-6 flex-column">
                <img src="{{ asset('assets/img/smkn-2.png') }}" alt="">
            </div>

            <div class="col-md-6 right-box">
                <div class="align-items-center">
                    <div class="header-text center">
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
        <div class="info">
            <small class=" text-wrap text-center"
                style="width: 17rem;font-family: 'Courier New', Courier, monospace; font-weight:500;">Simple E-Voting For
                SMK Negeri 2 Sukabumi</small>
        </div>
    </div>
@endsection

@push('js')
    {{-- Custom JS for This Page --}}
@endpush
