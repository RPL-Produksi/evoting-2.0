<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa-regular fa-bars"></i>
    </button>

    <h5 class="text-primary d-none d-md-block">E-Voting | SMK Negeri 2 Sukabumi</h5>

    <ul class="navbar-nav ml-auto">
        @if (auth()->user()->role == 'admin')
            <li class="nav-item dropdown no-arrow mx-1">
                <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-bell fa-fw"></i>
                    @if ($notificationCount > 10)
                        <span class="badge badge-danger badge-counter">10+</span>
                    @else
                        <span class="badge badge-danger badge-counter">{{ $notificationCount }}</span>
                    @endif
                </a>
                <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                    aria-labelledby="alertsDropdown">
                    <h6 class="dropdown-header">
                        Pusat Pemberitahuan
                    </h6>
                    @if ($notification->isEmpty())
                        <button class="dropdown-item">
                            <h6 class="text-center text-primary p-2">Tidak Ada Pemberitahuan</h6>
                        </button>
                    @else
                        @foreach ($notification as $item)
                            <button class="dropdown-item d-flex align-items-center" href="#">
                                <div class="mr-3">
                                    <div class="icon-circle bg-primary">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-gray-500">{{ $item->created_at->format('d F Y | H:i') }}
                                    </div>
                                    <span class="font-weight-bold">{{ $item->user->fullname }} telah voting di
                                        {{ $item->pemilu->name }}</span>
                                </div>
                            </button>
                        @endforeach
                    @endif
                </div>
            </li>
            <div class="topbar-divider d-none d-sm-block"></div>
        @endif
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ auth()->user()->fullname }}</span>
                @if (auth()->user()->profile_picture == null)
                    <img src="{{ asset('assets/img/avatar-1.png') }}"
                        class="img-profile rounded-circle font-weight-bold"></img>
                @else
                    <img src="{{ asset('/storage/upload/' . auth()->user()->profile_picture) }}"
                        class="img-profile rounded-circle font-weight-bold"></img>
                @endif
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="{{ route('profile') }}">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Keluar
                </a>
            </div>
        </li>
    </ul>
</nav>

<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">Yakin Ingin Keluar?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Pilih "Keluar" di bawah jika Anda siap mengakhiri sesi Anda saat ini.</div>
            <div class="modal-footer">
                <button class="btn btn-link" type="button" data-dismiss="modal">Batal</button>
                <form action="{{ route('post.logout') }}" method="POST" class="form-with-loading">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-loading">
                        <span class="btn-text">Keluar</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
