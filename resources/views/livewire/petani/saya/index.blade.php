<div>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <!-- Header Profil -->
                    <div class="profile-header p-4 text-center position-relative">
                        <img src="https://ui-avatars.com/api/?name=John+Doe&background=ffffff&color=28a745&size=120" 
                             alt="Foto Profil" 
                             class="profile-img rounded-circle mb-3">
                        <h3>{{$nama}}</h3>
                        <p class="mb-0">Petani</p>
                    </div>
                    
                    <!-- Detail Profil -->
                    <div class="card-body p-4">
                        <h5 class="card-title mb-4">
                            <i class="bi bi-person-lines-fill text-success"></i> Informasi Pribadi
                        </h5>
                        
                        <!-- NIK -->
                        <div class="detail-item row">
                            <div class="col-sm-4">
                                <h6 class="mb-0 text-muted">NIK</h6>
                            </div>
                            <div class="col-sm-8">
                                <p class="mb-0">{{$nik}}</p>
                            </div>
                        </div>
                        
                        <!-- Nama Lengkap -->
                        <div class="detail-item row">
                            <div class="col-sm-4">
                                <h6 class="mb-0 text-muted">Nama Lengkap</h6>
                            </div>
                            <div class="col-sm-8">
                                <p class="mb-0">{{$nama}}</p>
                            </div>
                        </div>
                        
                        <!-- Tanggal Lahir -->
                        <div class="detail-item row">
                            <div class="col-sm-4">
                                <h6 class="mb-0 text-muted">Tanggal Lahir</h6>
                            </div>
                            <div class="col-sm-8">
                                <p class="mb-0">{{$ttl}}</p>
                                <small class="text-muted">({{Carbon\Carbon::parse($ttl)->age}} tahun)</small>
                            </div>
                        </div>
                        
                        <!-- Alamat -->
                        <div class="detail-item row">
                            <div class="col-sm-4">
                                <h6 class="mb-0 text-muted">Alamat</h6>
                            </div>
                            <div class="col-sm-8">
                                <p class="mb-0">{{$alamat}}</p>
                            </div>
                        </div>
                        
                        <!-- Nomor HP -->
                        <div class="detail-item row">
                            <div class="col-sm-4">
                                <h6 class="mb-0 text-muted">Nomor HP</h6>
                            </div>
                            <div class="col-sm-8">
                                <p class="mb-0">{{$nohp}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
</div>
