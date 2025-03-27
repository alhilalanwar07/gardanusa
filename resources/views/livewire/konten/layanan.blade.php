<?php

use App\Models\Service;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Str;

new class extends Component {
    use WithPagination, WithFileUploads;
    
    // Properties with type hints
    public ?int $serviceId = null;
    public string $title = '';
    public string $description = '';
    public string $icon = '';
    public string $slug = '';
    public string $search = '';
    public int $perPage = 10;
    
    // Listeners
    protected $listeners = ['deleteConfirmed' => 'deleteService'];
    
    /**
     * Get data for the component
     */
    public function with(): array
    {
        return [
            'services' => Service::when($this->search, function ($query) {
                return $query->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%')
                    ->orWhere('slug', 'like', '%' . $this->search . '%');
            })->orderBy('created_at', 'desc')->paginate($this->perPage),
        ];
    }
    
    /**
     * Reset input fields
     */
    private function resetInputFields(): void
    {
        $this->reset(['title', 'description', 'icon', 'slug', 'serviceId']);
    }
    
    /**
     * Store a new service
     */
    public function store(): void
    {
        try {
            $this->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:1000',
                'icon' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:services,slug',
            ],[
                'title.required' => 'Judul tidak boleh kosong',
                'title.string' => 'Judul harus berupa teks',
                'title.max' => 'Judul maksimal 255 karakter',
                
                'description.required' => 'Deskripsi tidak boleh kosong',
                'description.string' => 'Deskripsi harus berupa teks',
                'description.max' => 'Deskripsi maksimal 1000 karakter',
                
                'icon.required' => 'Icon tidak boleh kosong',
                'icon.string' => 'Icon harus berupa teks',
                'icon.max' => 'Icon maksimal 255 karakter',
                
                'slug.required' => 'Slug tidak boleh kosong',
                'slug.string' => 'Slug harus berupa teks',
                'slug.max' => 'Slug maksimal 255 karakter',
                'slug.unique' => 'Slug sudah ada',
            ]);

            Service::create([
                'title' => $this->title,
                'description' => $this->description,
                'icon' => $this->icon,
                'slug' => $this->slug,
            ]);

            $this->dispatch('tambahAlertToast');
            $this->resetInputFields();
        } catch (\Exception $e) {
            $this->dispatch('errorAlertToast');
        }
    }
    
    /**
     * Load service data for editing
     */
    public function edit(int $id): void
    {
        $service = Service::findOrFail($id);
        $this->serviceId = $service->id;
        $this->title = $service->title;
        $this->description = $service->description;
        $this->icon = $service->icon;
        $this->slug = $service->slug;
    }
    
    /**
     * Update existing service
     */
    public function updateService(): void
    {
        try {
            $this->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:1000',
                'icon' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:services,slug,' . $this->serviceId,
            ],[
                'title.required' => 'Judul tidak boleh kosong',
                'title.string' => 'Judul harus berupa teks',
                'title.max' => 'Judul maksimal 255 karakter',
                
                'description.required' => 'Deskripsi tidak boleh kosong',
                'description.string' => 'Deskripsi harus berupa teks',
                'description.max' => 'Deskripsi maksimal 1000 karakter',
                
                'icon.required' => 'Icon tidak boleh kosong',
                'icon.string' => 'Icon harus berupa teks',
                'icon.max' => 'Icon maksimal 255 karakter',
                
                'slug.required' => 'Slug tidak boleh kosong',
                'slug.string' => 'Slug harus berupa teks',
                'slug.max' => 'Slug maksimal 255 karakter',
                'slug.unique' => 'Slug sudah ada',
            ]);

            $service = Service::findOrFail($this->serviceId);
            $service->update([
                'title' => $this->title,
                'description' => $this->description,
                'icon' => $this->icon,
                'slug' => $this->slug,
            ]);

            $this->dispatch('updateAlertToast');
            $this->resetInputFields();
        } catch (\Exception $e) {
            $this->dispatch('errorAlertToast');
        }
    }
    
    /**
     * Prepare for service deletion
     */
    public function delete(int $id): void
    {
        $this->serviceId = $id;
        $this->deleteService();
    }
    
    /**
     * Delete the service
     */
    public function deleteService(): void
    {
        try {
            if ($this->serviceId) {
                Service::findOrFail($this->serviceId)->delete();
                $this->dispatch('deleteAlertToast');
                $this->resetInputFields();
            }
        } catch (\Exception $e) {
            $this->dispatch('errorAlertToast');
        }
    }
    
    /**
     * Real-time validation
     */
    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName, [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'icon' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:services,slug,' . $this->serviceId,
        ],[
            'title.required' => 'Judul tidak boleh kosong',
            'title.string' => 'Judul harus berupa teks',
            'title.max' => 'Judul maksimal 255 karakter',
            
            'description.required' => 'Deskripsi tidak boleh kosong',
            'description.string' => 'Deskripsi harus berupa teks',
            'description.max' => 'Deskripsi maksimal 1000 karakter',
            
            'icon.required' => 'Icon tidak boleh kosong',
            'icon.string' => 'Icon harus berupa teks',
            'icon.max' => 'Icon maksimal 255 karakter',
            
            'slug.required' => 'Slug tidak boleh kosong',
            'slug.string' => 'Slug harus berupa teks',
            'slug.max' => 'Slug maksimal 255 karakter',
            'slug.unique' => 'Slug sudah ada',
        ]);
    }
    
    /**
     * Reset page when search changes
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }
    
    /**
     * Reset page when per page value changes
     */
    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    // slugify the title
    public function updatedTitle($value): void
    {
        $this->slug = Str::slug($value);
    }
}; ?>

<div>
    <div class="col-md-12">
        <div class="card card-round">
            <div class="card-header">
                <div class="card-head-row">
                    <div class="card-title">Layanan</div>
                    <div class="card-tools">
                        <a href="#" class="btn btn-info btn-sm me-2" data-bs-toggle="modal" data-bs-target="#modalTambah">
                            <span class="btn-label">
                                <i class="fa fa-plus"></i>
                            </span>
                            Tambah Layanan
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table table-responsive">
                    <table class="table table-hover table-borderless">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Icon</th>
                                <th>Judul</th>
                                <th>Deskripsi</th>
                                <th>Slug</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $no = 1;
                            @endphp
                            @forelse($services as $service)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>
                                    <i class="{{ $service->icon }}"></i>
                                </td>
                                <td>{{ $service->title }}</td>
                                <td>{{ $service->description }}</td>
                                <td>{{ $service->slug }}</td>
                                <td>
                                    <a href="#" wire:click.prevent="edit({{ $service->id }})" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="#" wire:click.prevent="delete({{ $service->id }})" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus layanan ini?')">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Data tidak ditemukan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="justify-content-between">
                        {{ $services->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- modalTambah -->
    <div wire:ignore.self class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahLabel">Tambah Layanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form wire:submit.prevent="store">
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label">Judul</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" wire:model.live="title">
                            @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="icon" class="form-label">Icon</label>
                            <input type="text" class="form-control @error('icon') is-invalid @enderror" id="icon" wire:model="icon" placeholder="fas fa-cog">
                            @error('icon') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" wire:model.live="slug">
                            @error('slug') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" rows="3" wire:model="description"></textarea>
                            @error('description') <span class="text-danger">{{ $message }}</span> @enderror
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditLabel">Edit Layanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form wire:submit.prevent="updateService">
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label">Judul</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" wire:model="title">
                            @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="icon" class="form-label">Icon</label>
                            <input type="text" class="form-control @error('icon') is-invalid @enderror" id="icon" wire:model="icon" placeholder="fas fa-cog">
                            @error('icon') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" wire:model="slug">
                            @error('slug') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" rows="3" wire:model="description"></textarea>
                            @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">
                                <span wire:loading.remove wire:target="updateService">Update</span>
                                <span wire:loading wire:target="updateService">Memperbarui...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <livewire:_alert />
</div>
