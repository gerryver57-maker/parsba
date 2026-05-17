<div>
    <div wire:ignore.self class="modal fade" id="CreateModal" tabindex="-1" aria-labelledby="CreateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="CreateModalLabel">Tambah Data Padi Saya</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <span class="text-danger">*</span>
                        <input wire:model="title" type="email" class="form-control @error('title') is-invalid @enderror" id="title" placeholder="title">
                        @error('title')
                        <small class="text-danger">
                            {{$message}}
                        </small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <span class="text-danger">*</span>
                        <textarea wire:model="content" class="form-control @error('content') is-invalid @enderror" id="content" rows="3"></textarea>
                        @error('content')
                        <small class="text-danger">
                            {{$message}}
                        </small>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keluar</button>
                    <button wire:click="store" type="button" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>