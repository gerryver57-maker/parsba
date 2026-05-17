<?php

namespace App\Livewire\Post;

use App\Models\Post;
use Livewire\WithPagination;
use Livewire\Component;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $paginate = '5';
    public $search = '';
    public $title;
    public $content;
    public $Post_id;
    public function render()
    {
        $data = array(
            'posts' => Post::Where('title', 'like', '%' . $this->search . '%')
                ->OrWhere('content', 'like', '%' . $this->search . '%')
                ->OrderBy('id', 'asc')->paginate($this->paginate),
        );
        return view('livewire.post.index', $data);
    }

    public function create()
    {
        $this->resetValidation();
        $this->reset([
            'title',
            'content'
        ]);
    }

    public function store()
    {
        $this->validate([
            'title' => 'required',
            'content' => 'required',
        ], [
            'title.required' => 'ttle tidak boleh kosong',
            'content.required' => 'content tidak boleh kosong',
        ]);

        $post = new POST;
        $post->title = $this->title;
        $post->content = $this->content;
        $post->save();

        $this->dispatch('closeCreateModal');
        return redirect('petani/padi/index');
    }

    public function edit($id)
    {
        $this->resetValidation();
        $post = Post::findOrFail($id);
        $this->title = $post->title;
        $this->content = $post->content;
        $this->Post_id = $post->id;
    }

    public function update($id)
    {
        $post = Post::findOrFail($id);
        $this->validate([
            'title' => 'required',
            'content' => 'required',
        ], [
            'title.required' => 'ttle tidak boleh kosong',
            'content.required' => 'content tidak boleh kosong',
        ]);

        $post->title = $this->title;
        $post->content = $this->content;
        $post->save();

        $this->dispatch('closeEditModal');
        return redirect('petani/padi/index');
    }

    public function confirm($id)
    {
        $post = Post::findOrFail($id);
        $this->title = $post->title;
        $this->content = $post->content;
        $this->Post_id = $post->id;
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        $this->dispatch('closeDeleteModal');
        return redirect('petani/padi/index');
    }
}
