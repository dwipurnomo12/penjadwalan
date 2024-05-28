  <nav class="navbar navbar-expand navbar-light bg-navbar topbar mb-4 static-top">
      <button id="sidebarToggleTop" class="btn btn-link rounded-circle mr-3">
          <i class="fa fa-bars"></i>
      </button>
      <ul class="navbar-nav ml-auto">

          <div class="topbar-divider d-none d-sm-block"></div>
          <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                  aria-haspopup="true" aria-expanded="false">
                  @if (auth()->user()->photo)
                      <img class="img-profile rounded-circle" src="/assets/img/profil.png" style="max-width: 60px">
                  @else
                      @if (auth()->user()->role->role == 'tata usaha')
                          @if (auth()->user()->tataUsaha->photo)
                              <img class="img-profile rounded-circle"
                                  src="{{ asset('storage/' . auth()->user()->tataUsaha->photo) }}"
                                  style="max-width: 60px">
                          @else
                              <img class="img-profile rounded-circle" src="/assets/img/profil.png"
                                  style="max-width: 60px">
                          @endif
                      @elseif (auth()->user()->role->role == 'dosen')
                          @if (auth()->user()->dosen->photo)
                              <img class="img-profile rounded-circle"
                                  src="{{ asset('storage/' . auth()->user()->dosen->photo) }}" style="max-width: 60px">
                          @else
                              <img class="img-profile rounded-circle" src="/assets/img/profil.png"
                                  style="max-width: 60px">
                          @endif
                      @elseif (auth()->user()->role->role == 'mahasiswa')
                          @if (auth()->user()->mahasiswa->photo)
                              <img class="img-profile rounded-circle"
                                  src="{{ asset('storage/' . auth()->user()->mahasiswa->photo) }}"
                                  style="max-width: 60px">
                          @else
                              <img class="img-profile rounded-circle" src="/assets/img/profil.png"
                                  style="max-width: 60px">
                          @endif
                      @endif
                  @endif

                  <span class="ml-2 d-none d-lg-inline text-white small">
                      @if (auth()->check())
                          @if (auth()->user()->tataUsaha)
                              {{ auth()->user()->tataUsaha->name }}
                          @elseif(auth()->user()->dosen)
                              {{ auth()->user()->dosen->name }}
                          @elseif(auth()->user()->mahasiswa)
                              {{ auth()->user()->mahasiswa->name }}
                          @endif
                      @endif
                      -
                      {{ auth()->user()->role->role }}
                  </span>
              </a>
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                  <a class="dropdown-item" href="/profile">
                      <i class="fas fa-user fa-sm fa-fw mr-2
                      text-gray-400"></i>
                      Profile
                  </a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="{{ route('logout') }}"
                      onclick="event.preventDefault();
                                Swal.fire({
                                    title: 'Konfirmasi Keluar',
                                    text: 'Apakah Anda yakin ingin keluar?',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Ya, Keluar!'
                                  }).then((result) => {
                                    if (result.isConfirmed) {
                                      document.getElementById('logout-form').submit();
                                    }
                                  });">
                      <i class="fa fa-power-off"></i> {{ __('Keluar') }}
                  </a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                      @csrf
                  </form>
                  </a>
              </div>
          </li>
      </ul>
  </nav>
