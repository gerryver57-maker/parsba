<!-- Header Start -->
<header class="app-header">
  <nav class="navbar navbar-expand-lg navbar-light">

    {{-- LEFT --}}
    <ul class="navbar-nav align-items-center">
      <li class="nav-item d-block d-xl-none">
        <a class="nav-link nav-icon-hover sidebartoggler"
           id="headerCollapse"
           href="javascript:void(0)">
          <i class="ti ti-menu-2"></i>
        </a>
      </li>

      <li class="nav-item">
        <h6 class="mb-0 fw-bold">@yield('title', 'Dashboard')</h6>
      </li>
    </ul>

    {{-- RIGHT --}}
    <div class="collapse navbar-collapse justify-content-end px-0" id="navbarNav">
      <ul class="navbar-nav flex-row ms-auto align-items-center">

        
        {{-- PROFILE DROPDOWN (IMPORTANT: wire:ignore) --}}
        <li class="nav-item dropdown" wire:ignore>
          <a class="nav-link nav-icon-hover d-flex align-items-center gap-2"
             href="#"
             id="drop2"
             role="button"
             data-bs-toggle="dropdown"
             aria-expanded="false">

            <img src="{{ asset('dashboard/images/profile/user-1.jpg') }}"
                 alt="User"
                 width="35"
                 height="35"
                 class="rounded-circle">

            <span class="d-none d-md-block small fw-semibold">
              {{ Auth::user()->nama }}
            </span>
          </a>

          <ul class="dropdown-menu dropdown-menu-end shadow"
              aria-labelledby="drop2">

            <li class="px-3 py-2 border-bottom">
              <div class="d-flex align-items-center gap-2">
                <img src="{{ asset('dashboard/images/profile/user-1.jpg') }}"
                     alt="User"
                     width="40"
                     height="40"
                     class="rounded-circle">

                <div>
                  <h6 class="mb-0">{{ Auth::user()->nama }}</h6>
                  <small class="text-muted">{{ Auth::user()->NIK }}</small>
                </div>
              </div>
            </li>

            <li>
              <a wire:navigate href="{{ url('petani/profil/index') }}"
                 class="dropdown-item d-flex align-items-center gap-2">
                <i class="ti ti-user-circle fs-5"></i>
                Profil Saya
              </a>
            </li>

            <li><hr class="dropdown-divider"></li>

            <li>
              <form action="{{ url('logout') }}">
                @csrf
                <button type="submit"
                        class="dropdown-item d-flex align-items-center gap-2 text-danger">
                  <i class="ti ti-logout fs-5"></i>
                  Keluar
                </button>
              </form>
            </li>

          </ul>
        </li>

      </ul>
    </div>

  </nav>
</header>
<!-- Header End -->