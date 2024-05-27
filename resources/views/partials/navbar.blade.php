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
                      <img class="img-profile rounded-circle" src="{{ asset('storage/' . auth()->user()->photo) }}"
                          style="max-width: 60px">
                  @else
                      <img class="img-profile rounded-circle" src="/assets/img/profil.png" style="max-width: 60px">
                  @endif
                  <span class="ml-2 d-none d-lg-inline text-white small">{{ auth()->user()->name }} -
                      {{ auth()->user()->role->role }}</span>
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
