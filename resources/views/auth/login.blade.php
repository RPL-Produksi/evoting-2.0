@extends('layouts.auth')
@section('title', 'Login')

@push('css')
    {{-- Custom CSS for This Page --}}
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 text-center d-lg-none d-block">
                <img class="img-fluid" width="250" height="250" src="{{ asset('assets/img/e-vote-logo-3.png') }}"
                    alt="">
                {{-- <img src="{{ asset('assets/img/logo_smea.png') }}" alt=""> --}}
            </div>
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="bg-gradient-primary col-lg-6 d-none d-lg-block">
                                <div class="p-5 d-flex align-items-center h-100">
                                    <div class="text-left">
                                        <h1 class="h4 text-gray-100">Simple E-Vote</h1>
                                        <p class="text-gray-100 mt-2">For SMK Negeri 2 Sukabumi</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h3 text-gray-900 mb-4">Login |
                                            @if ($adminLoggedIn)
                                                Admin
                                            @else
                                                E-Voting <sup>2.0</sup>
                                            @endif
                                        </h1>

                                        @if ($errors->any())
                                            <div class="text-left alert alert-danger border-left-danger" role="alert">
                                                <ul class="pl-4 my-2">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <form action="{{ route('post.login', $adminLoggedIn) }}" method="POST"
                                            class="form-with-loading">
                                            @csrf
                                            <div class="form-group">
                                                <input type="text" name="username" id="username"
                                                    class="form-control form-control-user" placeholder="Username" required
                                                    autofocus>
                                            </div>
                                            <div class="form-group">
                                                <input type="password" name="password" id="password"
                                                    class="form-control form-control-user" placeholder="Password" required>
                                            </div>

                                            @if (Session::has('error'))
                                                <div class="form-group text-center">
                                                    <p class="text-danger">{{ Session::get('error') }}</p>
                                                </div>
                                            @endif

                                            <div class="form-group">
                                                <button type="submit"
                                                    class="btn btn-primary btn-user btn-block btn-loading">
                                                    <span class="btn-text">Login</span>
                                                    <span class="spinner-border spinner-border-sm d-none"
                                                        role="status"></span>
                                                </button>
                                            </div>

                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    {{-- Custom JS for This Page --}}
@endpush
