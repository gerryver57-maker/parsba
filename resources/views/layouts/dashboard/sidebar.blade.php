<!-- Sidebar Start -->
<aside class="left-sidebar" id="sidebar">
    <!-- Sidebar scroll-->
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="{{ url('petani/dashboard') }}" class="text-nowrap logo-img">
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
                    <a wire:navigate class="sidebar-link {{ request()->routeIs('petani.dashboard') ? 'active' : '' }}" 
                       href="{{ url('petani/dashboard') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-layout-dashboard"></i>
                        </span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>

                <!-- ========== DATA SAYA ========== -->
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">DATA SAYA</span>
                </li>
                
                <!-- Lahan Saya -->
                <li class="sidebar-item">
                    <a wire:navigate class="sidebar-link {{ request()->routeIs('petani.lahan.*') ? 'active' : '' }}" 
                       href="{{ url('petani/lahan/index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-map"></i>
                        </span>
                        <span class="hide-menu">Lahan Saya</span>
                    </a>
                </li>

                <!-- ========== MANAJEMEN TANAM ========== -->
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">MANAJEMEN TANAM</span>
                </li>
                
                <!-- Siklus Tanam -->
                <li class="sidebar-item">
                    <a wire:navigate class="sidebar-link {{ request()->routeIs('petani.siklus.*') ? 'active' : '' }}" 
                       href="{{ url('petani/siklustanam/index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-plant"></i>
                        </span>
                        <span class="hide-menu">Siklus Tanam</span>
                        @php
                            $siklusAktif = \App\Models\SiklusTanam::where('user_id', Auth::id())->where('status', 'aktif')->count();
                        @endphp
                        @if($siklusAktif > 0)
                        <span class="badge bg-success rounded-pill ms-auto hide-menu">{{ $siklusAktif }}</span>
                        @endif
                    </a>
                </li>

                <!-- Kalender Manajemen -->
                <li class="sidebar-item">
                    <a wire:navigate class="sidebar-link {{ request()->routeIs('petani.kalender') ? 'active' : '' }}" 
                       href="{{ url('petani/kalender1/index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-calendar"></i>
                        </span>
                        <span class="hide-menu">Kalender Manajemen</span>
                    </a>
                </li>
                
                <!-- Jadwal Kegiatan -->
                <li class="sidebar-item">
                    <a wire:navigate class="sidebar-link {{ request()->routeIs('petani.jadwal.*') ? 'active' : '' }}" 
                       href="{{ url('petani/jadwal/index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-calendar"></i>
                        </span>
                        <span class="hide-menu">Jadwal Kegiatan</span>
                        @php
                            $jadwalPending = \App\Models\JadwalOtomatis::whereHas('siklusTanam', function($q) {
                                $q->where('user_id', Auth::id());
                            })->where('sudah_dikonfirmasi', false)->count();
                        @endphp
                        @if($jadwalPending > 0)
                        <span class="badge bg-warning rounded-pill ms-auto hide-menu">{{ $jadwalPending }}</span>
                        @endif
                    </a>
                </li>
                
                <!-- Panen Saya -->
                <li class="sidebar-item">
                    <a wire:navigate class="sidebar-link {{ request()->routeIs('petani.panen.*') ? 'active' : '' }}" 
                       href="{{ url('petani/panen/index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-basket"></i>
                        </span>
                        <span class="hide-menu">Panen Saya</span>
                    </a>
                </li>

                <!-- ========== INFORMASI ========== -->
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">INFORMASI</span>
                </li>
                
                <!-- Prakiraan Cuaca -->
                <li class="sidebar-item">
                    <a wire:navigate class="sidebar-link {{ request()->routeIs('petani.cuaca') ? 'active' : '' }}" 
                       href="{{ url('petani/cuaca/index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-cloud-rain"></i>
                        </span>
                        <span class="hide-menu">Prakiraan Cuaca</span>
                    </a>
                </li>
                
                <!-- Info Varietas -->
                <li class="sidebar-item">
                    <a wire:navigate class="sidebar-link {{ request()->routeIs('petani.varietas') ? 'active' : '' }}" 
                       href="{{ url('petani/varietas/index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-plant"></i>
                        </span>
                        <span class="hide-menu">Info Varietas Padi</span>
                    </a>
                </li>
                
                <!-- Hama & Penyakit -->
                <li class="sidebar-item">
                    <a wire:navigate class="sidebar-link {{ request()->routeIs('petani.hama') ? 'active' : '' }}" 
                       href="{{ url('petani/hama/index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-bug"></i>
                        </span>
                        <span class="hide-menu">Hama & Penyakit</span>
                    </a>
                </li>

                <!-- ========== PENGATURAN ========== -->
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">PENGATURAN</span>
                </li>
                
                <!-- Profil Saya -->
                <li class="sidebar-item">
                    <a wire:navigate class="sidebar-link {{ request()->routeIs('petani.profil') ? 'active' : '' }}" 
                       href="{{ url('petani/profil/index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-user-circle"></i>
                        </span>
                        <span class="hide-menu">Profil Saya</span>
                    </a>
                </li>

                <!-- ========== LOGOUT FIXED BOTTOM ========== -->
                <li class="sidebar-item mt-3 logout-item">
                    <hr class="border-secondary opacity-25">
                </li>
                <li class="sidebar-item logout-item">
                    <a wire:navigate class="sidebar-link" href="{{ url('/logout') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-logout"></i>
                        </span>
                        <span class="hide-menu text-danger">Keluar</span>
                    </a>
                </li>
                
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
<!-- Sidebar End -->
