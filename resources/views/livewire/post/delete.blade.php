<div>
    <div wire:ignore.self class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="deleteModalLabel">Hapus Data Padi Saya</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-4">Title</div>
                        <div class="col-8">: {{$title}}</div>
                    </div>
                    <div class="row">
                        <div class="col-4">Content</div>
                        <div class="col-8">: {{$content}}</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keluar</button>
                    <button wire:click="destroy({{$Post_id}})" type="button" class="btn btn-danger">Hapus Data</button>
                </div>
            </div>
        </div>
    </div>
</div>