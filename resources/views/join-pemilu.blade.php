@extends('layouts.app')
@section('title', 'Dashboard')

@push('css')
    {{-- Custom CSS for This Page --}}
    <link rel="stylesheet" href="{{ asset('css/users/JoinPemilu.css') }}">
@endpush

@php
    $class = 'col-12 col-md-6 col-lg-4';
    if ($kandidat->count() == 1) {
        $class = 'col-12';
    }
@endphp

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg">
                <div class="card-header">
                    <h4 class="card-title text-primary">Daftar Kandidat | {{ $pemilu->name }}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($kandidat as $item)
                            <div class="{{ $class }} mt-3">
                                <div class="card h-100 shadow-lg">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col">
                                                <h5 class="card-title pt-2 text-primary">Kandidat {{ $loop->iteration }}
                                                </h5>
                                            </div>
                                            <div class="ml-auto pt-1">
                                                <button onclick="visiMisi('{{ $pemilu->slug }}', '{{ $item->id }}')"
                                                    class="btn btn-warning btn-sm">Visi & Misi</button>
                                                <button onclick="voting('{{ $pemilu->slug }}', '{{ $item->id }}')"
                                                    class="btn btn-primary btn-sm">Vote</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body d-flex align-items-center justify-content-center">
                                        <img src="{{ asset('storage/upload/' . $item->image) }}" alt=""
                                            class="img-fluid rounded-lg">
                                    </div>
                                    <div class="card-footer">
                                        <h5 class="text-center card-text text-primary">{{ $item->name }}</h6>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="visionMissionModal" tabindex="-1" role="dialog" aria-labelledby="visionMissionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title home-title-vote" id="visionMissionModalLabel"></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <textarea class="form-control" style="cursor: no-drop" cols="30" rows="20" id="visionMissionParagraph"
                            readonly></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-link" type="button" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="votingModal" tabindex="-1" role="dialog" aria-labelledby="votingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="votingModalLabel">Voting Konfirmasi</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="" method="POST" id="voteForm" class="form-with-loading">
                    @csrf
                    <div class="modal-body">
                        <p>Apakah kamu yakin ingin memilih kandidat ini?</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-link" type="button" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success btn-loading">
                            <span class="btn-text">Iya</span>
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
    <script>
        const visiMisi = (slug, id) => {
            $.getJSON(`${window.location.origin}/user/pemilu/${slug}/kandidat/${id}/data`, (data) => {
                $('#visionMissionParagraph').val(data.vision_mission)
                $('#visionMissionModalLabel').text(data.name)

                const myModal = new bootstrap.Modal(document.getElementById('visionMissionModal'));
                myModal.show();
            })
        }

        const voting = (slug, id) => {
            const urlVote = `{{ route('user.pemilu.vote', [':slug', ':id']) }}`
            $('#voteForm').attr('action', urlVote.replace(':id', id).replace(':slug', slug));

            const myModal = new bootstrap.Modal(document.getElementById('votingModal'));
            myModal.show();
        }
    </script>
@endpush
