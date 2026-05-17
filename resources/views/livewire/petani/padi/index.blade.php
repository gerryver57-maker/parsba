<div>
   <div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Padi</h3>
            <button wire:click="create" class="btn btn-primary float-right">Tambah Data</button>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <input type="text" wire:model="search" class="form-control" placeholder="Cari data padi...">
            </div>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Lahan</th>
                            <th>Nama Padi</th>
                            <th>Umur</th>
                            <th>Hasil Panen</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($padis as $padi)
                        <tr>
                            <td>{{ $padi->idpadi }}</td>
                            <td>{{ $padi->lahan->lokasi }}</td>
                            <td>{{ $padi->nama }}</td>
                            <td>{{ $padi->umur }} hari</td>
                            <td>{{ $padi->hasilpanen }} kg</td>
                            <td>
                                <button wire:click="edit({{ $padi->idpadi }})" class="btn btn-sm btn-warning">Edit</button>
                                <button wire:click="delete({{ $padi->idpadi }})" class="btn btn-sm btn-danger" onclick="confirm('Yakin menghapus?') || event.stopImmediatePropagation()">Hapus</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $padis->links() }}
            </div>
        </div>
    </div>

    @if($showForm)
        <livewire:padi-form :padiId="$padiId" />
    @endif
</div>
</div>
