@extends('layouts.copyright')
@section('title', 'Copyright')

@push('css')
    {{-- Custom CSS for This Page --}}
@endpush

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 my-4">
                <div class="card bg-dark border-secondary">
                    <div class="card-body">
                        <h4 class="text-center text-white">Member Of <a href="https://github.com/RPL-Produksi" target="_blank">RPL
                                Produksi</a></h4>
                    </div>
                </div>
            </div>
            @foreach ($member as $item)
                <div class="col-12 col-md-3 mb-3">
                    <div class="card bg-dark border-secondary h-100">
                        <div class="card-header bg-dark border-secondary">
                            <h4 class="card-title text-center text-primary">{{ $item['name'] }}</h4>
                        </div>
                        <div class="card-body bg-dark d-flex align-items-center justify-content-center">
                            <img src="{{ asset($item['img']) }}" alt="" class="img-fluid">
                        </div>
                        <div class="card-footer bg-dark border-secondary text-center">
                            <a href="{{ $item['github'] }}" target="_blank" class="btn btn-primary"><i
                                    class="fa-brands fa-github"></i></a>
                            <a href="{{ $item['website'] }}" target="_blank" class="btn btn-warning"><i
                                    class="fa-regular fa-browser"></i></a>
                            <a href="{{ $item['instagram'] }}" target="_blank" class="btn btn-danger"><i
                                    class="fa-brands fa-instagram"></i></a>
                            <a href="{{ $item['steam'] }}" target="_blank" class="btn btn-secondary"><i
                                    class="fa-brands fa-steam"></i></a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('js')
    {{-- Custom JS for This Page --}}
@endpush
