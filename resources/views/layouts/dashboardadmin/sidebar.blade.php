<!-- Sidebar Start -->
<aside class="left-sidebar" id="sidebar">
    <!-- Sidebar scroll-->
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="{{ url('admin/dashboard') }}" class="text-nowrap logo-img">
                <img src="{{ asset('dashboard/images/logos/logo.png') }}" width="180" alt="SIPADI Logo" />
            </a>
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8"></i>
            </div>
        </div>
        
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                
                <!-- ========== MAIN MENU ========== -->
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">MAIN MENU</span>
                </li>
                
                <!-- Dashboard -->
                <li class="sidebar-item">
                    <a wire:navigate class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                       href="{{ url('admin/dashboard') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-layout-dashboard"></i>
                        </span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
                
                <!-- Kelola Pengguna -->
                <li class="sidebar-item">
                    <a wire:navigate class="sidebar-link {{ request()->routeIs('admin.pengguna.index') ? 'active' : '' }}" 
                       href="{{ url('admin/pengguna/index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-users"></i>
                        </span>
                        <span class="hide-menu">Kelola Pengguna</span>
                    </a>
                </li>

                <!-- ========== DATA MASTER ========== -->
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">DATA MASTER</span>
                </li>
                
                <!-- Varietas Padi -->
                <li class="sidebar-item">
                    <a wire:navigate class="sidebar-link {{ request()->routeIs('admin.varietas.index') ? 'active' : '' }}" 
                       href="{{ url('admin/varietas/index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-plant"></i>
                        </span>
                        <span class="hide-menu">Varietas Padi</span>
                    </a>
                </li>
                
                <!-- Data Pupuk -->
                <li class="sidebar-item">
                    <a wire:navigate class="sidebar-link {{ request()->routeIs('admin.pupuk.index') ? 'active' : '' }}" 
                       href="{{ url('admin/pupuk/index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-droplet"></i>
                        </span>
                        <span class="hide-menu">Data Pupuk</span>
                    </a>
                </li>
                
                <!-- Data Pestisida -->
                <li class="sidebar-item">
                    <a wire:navigate class="sidebar-link {{ request()->routeIs('admin.pestisida.index') ? 'active' : '' }}" 
                       href="{{ url('admin/pestisida/index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-shield"></i>
                        </span>
                        <span class="hide-menu">Data Pestisida</span>
                    </a>
                </li>
                
                <!-- Hama & Penyakit -->
                <li class="sidebar-item">
                    <a wire:navigate class="sidebar-link {{ request()->routeIs('admin.hama.index') ? 'active' : '' }}" 
                       href="{{ url('admin/hama/index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-bug"></i>
                        </span>
                        <span class="hide-menu">Hama & Penyakit</span>
                    </a>
                </li>
                
                <!-- Fase Tumbuh Padi -->
                <li class="sidebar-item">
                    <a wire:navigate class="sidebar-link {{ request()->routeIs('admin.fasetumbuh.index') ? 'active' : '' }}" 
                       href="{{ url('admin/fasetumbuh/index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-calendar"></i>
                        </span>
                        <span class="hide-menu">Fase Tumbuh Padi</span>
                    </a>
                </li>

                <!-- ========== PEMANTAUAN ========== -->
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">PEMANTAUAN</span>
                </li>
                
                <!-- Siklus Tanam -->
                <li class="sidebar-item">
                    <a wire:navigate class="sidebar-link {{ request()->routeIs('admin.siklustanam.index') ? 'active' : '' }}" 
                       href="{{ url('admin/siklustanam/index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-arrow-loop"></i>
                        </span>
                        <span class="hide-menu">Siklus Tanam</span>
                        @php
                            $siklusAktif = \App\Models\SiklusTanam::where('status', 'aktif')->count();
                        @endphp
                        @if($siklusAktif > 0)
                        <span class="badge bg-success rounded-pill ms-auto hide-menu">{{ $siklusAktif }}</span>
                        @endif
                    </a>
                </li>
                
                <!-- Jadwal Otomatis -->
                <li class="sidebar-item">
                    <a wire:navigate class="sidebar-link {{ request()->routeIs('admin.jadwalaktivitas.index') ? 'active' : '' }}" 
                       href="{{ url('admin/jadwalaktivitas/index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-calendar-week"></i>
                        </span>
                        <span class="hide-menu">Jadwal Aktivitas</span>
                        @php
                            $jadwalPending = \App\Models\JadwalOtomatis::where('sudah_dikonfirmasi', false)->count();
                        @endphp
                        @if($jadwalPending > 0)
                        <span class="badge bg-warning rounded-pill ms-auto hide-menu">{{ $jadwalPending }}</span>
                        @endif
                    </a>
                </li>

                <!-- ========== DATA CUACA & BMKG ========== -->
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">DATA CUACA & BMKG</span>
                </li>
                
                <!-- Prakiraan Cuaca -->
                <li class="sidebar-item">
                    <a wire:navigate class="sidebar-link {{ request()->routeIs('admin.prakiraancuaca.index') ? 'active' : '' }}" 
                       href="{{ url('admin/prakiraancuaca/index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-cloud-rain"></i>
                        </span>
                        <span class="hide-menu">Prakiraan Cuaca</span>
                    </a>
                </li>
                
                <!-- Sinkronisasi BMKG -->
                <li class="sidebar-item">
                    <a wire:navigate class="sidebar-link {{ request()->routeIs('admin.sinkronisasi.index') ? 'active' : '' }}" 
                       href="{{ url('admin/sinkronisasi/index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-cloud-upload"></i>
                        </span>
                        <span class="hide-menu">Sinkronisasi BMKG</span>
                    </a>
                </li>

                <!-- ========== LAPORAN ========== -->
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">LAPORAN</span>
                </li>
                
                <!-- Laporan Hasil Panen -->
                <li class="sidebar-item">
                    <a wire:navigate class="sidebar-link {{ request()->routeIs('admin.laporanpanen.index') ? 'active' : '' }}" 
                       href="{{ url('admin/laporanpanen/index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-file-report"></i>
                        </span>
                        <span class="hide-menu">Laporan Hasil Panen</span>
                    </a>
                </li>
                
                <!-- Laporan Aktivitas -->
                <li class="sidebar-item">
                    <a wire:navigate class="sidebar-link {{ request()->routeIs('admin.laporanaktivitas.index') ? 'active' : '' }}" 
                       href="{{ url('admin/laporanaktivitas/index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-file-text"></i>
                        </span>
                        <span class="hide-menu">Laporan Aktivitas</span>
                    </a>
                </li>

                <!-- ========== PENGATURAN ========== -->
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">PENGATURAN</span>
                </li>
                
                <!-- Profil Saya -->
                <li class="sidebar-item">
                    <a wire:navigate class="sidebar-link {{ request()->routeIs('admin.profil.index') ? 'active' : '' }}" 
                       href="{{ url('admin/profil/index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-user-circle"></i>
                        </span>
                        <span class="hide-menu">Profil Saya</span>
                    </a>
                </li>

                <!-- ========== LOGOUT ========== -->
                <li class="sidebar-item mt-3">
                    <hr class="border-secondary opacity-25">
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
<!-- Sidebar End -->
 