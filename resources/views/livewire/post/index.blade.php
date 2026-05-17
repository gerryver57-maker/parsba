<div>
    <div class="content-wrapper">
        <div class="content">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <button wire:click="create" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#CreateModal">
                            <i class="fas fa-plus mr-1"></i>
                            Tambah Data
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 d-flex justify-content-between">
                            <div class="col-3">
                                <select wire:model.live="paginate" class="form-control">
                                    <option value="">--Tampilkan Jumlah Data--</option>
                                    <option value="1">1</option>
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="75">75</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <input wire:model.live="search" type="text" class="form-control" placeholder="Pencarian...">
                            </div>
                        </div>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <td>No</td>
                                    <td>title</td>
                                    <td>Content</td>
                                    <td><i class="fa fa-cog fa-spin fa-2x fa-fw"></i></td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($posts as $item)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$item->title}}</td>
                                    <td>{{$item->content}}</td>
                                    <td><button wire:click="edit({{$item->id}})" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal"><i class="fas fa-edit"></i></button></td>
                                    <td><button wire:click="confirm({{$item->id}})" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="fas fa-trash"></i></button></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{$posts ->links()}}
                    </div>
                </div>
            </div>
            @include('livewire.post.create')

            @script
            <script>
                $wire.on('closeCreateModal', () => {
                    $('#CreateModal').modal('hide');
                    Swal.fire({
                        title: "Sukses",
                        text: "Data Berhasil di Tambah",
                        icon: "success"
                    });
                });
            </script>

            @endscript

            @include('livewire.post.edit')

            @script
            <script>
                $wire.on('closeEditModal', () => {
                    $('#editModal').modal('hide');
                    Swal.fire({
                        title: "Sukses",
                        text: "Data Berhasil di Ubah",
                        icon: "success"
                    });
                });
            </script>

            @endscript

            @include('livewire.post.delete')

            @script
            <script>
                $wire.on('closeDeleteModal', () => {
                    $('#deleteModal').modal('hide');
                    Swal.fire({
                        title: "Sukses",
                        text: "Data Berhasil di dhapus",
                        icon: "success"
                    });
                });
            </script>

            @endscript
        </div>
    </div>