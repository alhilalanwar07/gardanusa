<?php

use App\Models\Post;
use Livewire\Volt\Component;
use App\Models\PostCategory;
use Livewire\WithPagination;
use Illuminate\Support\Str;

new class extends Component {
    use WithPagination;
    public string $name = '';
    public string $slug = '';
    public string $search = '';
    public int $perPage = 10;
    public ?int $categoryId = null;

    public function with(): array
    {
        return [
            'categories' => \App\Models\Category::when($this->search, function ($query) {
                return $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('slug', 'like', '%' . $this->search . '%');
            })->orderBy('created_at', 'desc')->paginate($this->perPage),
        ];
    }

    private function resetInputFields(): void
    {
        $this->reset(['name', 'slug', 'categoryId']);
    }

    public function updatedName($value): void 
    {
        $this->slug = Str::slug($value);
    }

    public function store(): void
    {
        try {
            $this->validate([
                'name' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:categories',
            ]);

            \App\Models\Category::create([
                'name' => $this->name,
                'slug' => $this->slug,
            ]);

            $this->dispatch('tambahAlertToast');
            $this->resetInputFields();
        } catch (\Exception $e) {
            $this->dispatch('errorAlertToast');
        }
    }

    public function edit(int $id): void
    {
        $category = \App\Models\Category::findOrFail($id);
        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->slug = $category->slug;
    }

    public function updateCategory(): void
    {
        try {
            $this->validate([
                'name' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:categories,slug,' . $this->categoryId,
            ]);

            \App\Models\Category::findOrFail($this->categoryId)->update([
                'name' => $this->name,
                'slug' => $this->slug,
            ]);

            $this->dispatch('updateAlertToast');
            $this->resetInputFields();
        } catch (\Exception $e) {
            $this->dispatch('errorAlertToast');
        }
    }

    public function delete(int $id): void
    {
        try {
            $category = \App\Models\Category::findOrFail($id);
            if (Post::where('category_id', $category->id)->exists()) {
                $this->dispatch('errorAlertToast', 'Kategori tidak dapat dihapus karena sudah digunakan pada artikel.');
                return;
            }
            $category->delete();
            $this->dispatch('deleteAlertToast');
        } catch (\Exception $e) {
            $this->dispatch('errorAlertToast');
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }
}; ?>

<div>
    <div class="col-md-12">
        <div class="card card-round">
            <div class="card-header">
            <div class="card-head-row">
                <div class="card-title">Kategori</div>
                <div class="card-tools">
                <a href="#" class="btn btn-info btn-sm me-2" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <span class="btn-label">
                    <i class="fa fa-plus"></i>
                    </span>
                    Tambah Kategori
                </a>
                </div>
            </div>
            </div>
            <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Cari kategori..." wire:model.live.debounce.300ms="search">
                </div>
                </div>
                <div class="col-md-2 ms-auto">
                <select class="form-select" wire:model.live="perPage">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-borderless">
                <thead>
                    <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $index => $category)
                    <tr>
                    <td>{{ $categories->firstItem() + $index }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->slug }}</td>
                    <td>
                        <a href="#" wire:click.prevent="edit({{ $category->id }})" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit">
                        <i class="fa fa-edit"></i>
                        </a>
                        <a href="#" wire:click.prevent="delete({{ $category->id }})" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                        <i class="fa fa-trash"></i>
                        </a>
                    </td>
                    </tr>
                    @empty
                    <tr>
                    <td colspan="4" class="text-center">Tidak ada kategori ditemukan</td>
                    </tr>
                    @endforelse
                </tbody>
                </table>
            </div>
            <div class="card-footer">
                <div class="justify-content-between">
                {{ $categories->links() }}
                </div>
            </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="modalTambahLabel">Tambah Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                <form wire:submit.prevent="store">
                    @csrf
                    <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" wire:model.live="name">
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" wire:model="slug">
                    @error('slug') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">
                        <span wire:loading.remove>Simpan</span>
                        <span wire:loading>Menyimpan...</span>
                    </button>
                    </div>
                </form>
                </div>
            </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="modalEditLabel">Edit Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                <form wire:submit.prevent="updateCategory">
                    @csrf
                    <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" wire:model.live="name">
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" wire:model="slug">
                    @error('slug') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">
                        <span wire:loading.remove wire:target="updateCategory">Update</span>
                        <span wire:loading wire:target="updateCategory">Memperbarui...</span>
                    </button>
                    </div>
                </form>
                </div>
            </div>
            </div>
        </div>
        
        <livewire:_alert />
        </div>
</div>
