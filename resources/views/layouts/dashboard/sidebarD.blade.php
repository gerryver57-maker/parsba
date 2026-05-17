    <!-- Sidebar Start -->
    <aside class="left-sidebar">
      <!-- Sidebar scroll-->
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="./index.html" class="text-nowrap logo-img">
            <img src="{{asset('public/dashboard/images/logos/logo.png')}}" width="180" alt="" />
          </a>
          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-8"></i>
          </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav">
            <li class="sidebar-item">
              <a wire:navigate class="sidebar-link @yield('MenudashboardActive')" href="{{url('admin/dashboard')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-layout-dashboard"></i>
                </span>
                <span class="hide-menu">Dashboard</span>
              </a>
              <li class="sidebar-item">
              <a wire:navigate class="sidebar-link @yield('MenuprakiraanActive')" href="{{url('admin/prakiraan/index')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-cloud"></i>
                </span>
                <span class="hide-menu">Prakiraan Cuaca</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a wire:navigate class="sidebar-link @yield('MenudatasayaActive')" href="{{url('admin/saya/index')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-user"></i>
                </span>
                <span class="hide-menu">Data Saya</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a wire:navigate class="sidebar-link @yield('MenukalenderActive')" href="{{url('admin/kalender/index')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-calendar"></i>
                </span>
                <span class="hide-menu">Kalender Manajemen</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a wire:navigate class="sidebar-link @yield('MenudatapadiActive')" href="{{url('admin/padi/index')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-server"></i>
                </span>
                <span class="hide-menu">Data Padi Saya</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a wire:navigate class="sidebar-link @yield('MenupengaturanActive')" href="{{url('admin/pengaturan/index')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-settings"></i>
                </span>
                <span class="hide-menu">Pengaturan Akun</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a wire:navigate class="sidebar-link @yield('MenukeluarActive')" href="{{url('/logout')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-lock"></i>
                </span>
                <span class="hide-menu">Keluar</span>
              </a>
            </li>
          </ul>
        </nav>
        <!-- End Sidebar navigation -->
      </div>
      <!-- End Sidebar scroll-->
    </aside>
    <!--  Sidebar End -->