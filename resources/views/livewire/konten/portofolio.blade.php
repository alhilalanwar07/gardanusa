<?php

use Livewire\Volt\Component;
use App\Models\Project;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Client;
use App\Models\Service;
use Illuminate\Support\Str;


new class extends Component {
    use WithPagination, WithFileUploads;
    public ?int $projectId = null;

    public string $title = '';
    public string $description = '';
    public $image = null;
    public string $project_date = '';
    public int $client_id = 0;
    public int $service_id = 0;
    public string $url = '';
    public string $slug = '';
    public string $challenge = '';
    public string $solution = '';
    public $gallery = [];
    public string $search = '';
    public int $perPage = 10;
    public $tempImage = null;
    public $tempGallery = [];
    public $existingGallery = [];
    public $newGallery = [];

    protected $listeners = [
        'deleteConfirmed' => 'deleteProject',
        'descriptionUpdated'
    ];

    public function with(): array
    {
        $projects = Project::query()
            ->with(['client', 'service'])
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        $projects->through(function ($project) {
            if (!empty($project->gallery)) {
                $project->gallery = is_string($project->gallery) 
                    ? array_filter(json_decode($project->gallery, true) ?? [], fn($item) => is_string($item) && !empty($item))
                    : array_filter((array)$project->gallery, fn($item) => is_string($item) && !empty($item));
            }
            return $project;
        });

        return [
            'projects' => $projects,
            'clients' => Client::orderBy('name')->get(),
            'services' => Service::orderBy('title')->get(),
        ];
    }

    public function resetInputFields(): void
    {
        $this->reset([
            'title',
            'description',
            'image',
            'project_date',
            'client_id',
            'service_id',
            'url',
            'slug',
            'challenge',
            'solution',
            'gallery',
            'projectId',
            'tempImage'
        ]);
    }

    public function store(): void
    {
        try {
            $this->validate(
                [
                    'title' => 'required|string|max:255',
                    'description' => 'required|string',
                    'tempImage' => 'required|image|max:1024',
                    'project_date' => 'required|date',
                    'client_id' => 'required|integer',
                    'service_id' => 'required|integer',
                    'url' => 'nullable|string|max:255',
                    'slug' => 'required|string|max:255',
                ],
                [
                    'required' => ':attribute tidak boleh kosong.',
                    'title.required' => 'Judul portofolio tidak boleh kosong.',
                    'title.string' => 'Judul portofolio harus berupa teks.',
                    'title.max' => 'Judul portofolio tidak boleh lebih dari 255 karakter.',
                    'description.required' => 'Deskripsi portofolio tidak boleh kosong.',
                    'description.string' => 'Deskripsi portofolio harus berupa teks.',
                    'tempImage.required' => 'Gambar portofolio tidak boleh kosong.',
                    'tempImage.image' => 'File yang diunggah harus berupa gambar.',
                    'tempImage.max' => 'Ukuran gambar tidak boleh lebih dari 1 MB.',
                    'project_date.required' => 'Tanggal proyek tidak boleh kosong.',
                    'project_date.date' => 'Tanggal proyek harus berupa tanggal yang valid.',
                    'client_id.required' => 'Klien tidak boleh kosong.',
                    'client_id.integer' => 'Klien harus berupa angka.',
                    'service_id.required' => 'Layanan tidak boleh kosong.',
                    'service_id.integer' => 'Layanan harus berupa angka.',
                    'url.string' => 'URL harus berupa teks.',
                    'url.max' => 'URL tidak boleh lebih dari 255 karakter.',
                    'slug.required' => 'Slug tidak boleh kosong.',
                    'slug.string' => 'Slug harus berupa teks.',
                    'slug.max' => 'Slug tidak boleh lebih dari 255 karakter.',
                ]
            );

            $imageName = time() . '.' . $this->tempImage->extension();
            $this->tempImage->storeAs('public/projects', $imageName);

            // gallery
            $galleryPaths = [];
            if ($this->gallery) {
                foreach ($this->gallery as $file) {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $file->storeAs('public/projects/gallery', $fileName);
                    $galleryPaths[] = 'projects/gallery/' . $fileName;
                }
            }

            $this->gallery = json_encode($galleryPaths);

            Project::create([
                'title' => $this->title,
                'description' => $this->description,
                'image' => 'projects/' . $imageName,
                'project_date' => $this->project_date,
                'client_id' => $this->client_id,
                'service_id' => $this->service_id,
                'url' => $this->url,
                'slug' => $this->slug,
                'challenge' => $this->challenge,
                'solution' => $this->solution,
                'gallery' => $this->gallery,
            ]);

            $this->dispatch('tambahAlertToast');
            $this->resetInputFields();
        } catch (\Exception $e) {
            $this->dispatch('errorAlertToast');
        }
    }

    public function updatedTitle($value): void
    {
        $this->slug = Str::slug($value);
    }

    public function edit(int $id): void
    {
        $project = Project::findOrFail($id);
        $this->projectId = $project->id;
        $this->title = $project->title;
        $this->description = $project->description;
        $this->image = $project->image;
        $this->project_date = $project->project_date;
        $this->client_id = $project->client_id;
        $this->service_id = $project->service_id;
        $this->url = $project->url;
        $this->slug = $project->slug;
        $this->challenge = $project->challenge;
        $this->solution = $project->solution;
        $this->gallery = $project->gallery;
        $this->existingGallery = json_decode($project->gallery, true) ?? [];
        $this->newGallery = [];
    }

    public function updateProject(): void
    {
        try {
            $rules = [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'project_date' => 'required|date',
                'client_id' => 'required|integer',
                'service_id' => 'required|integer',
                'url' => 'nullable|string|max:255',
                'slug' => 'required|string|max:255',
            ];

            if ($this->tempImage) {
                $rules['tempImage'] = 'image|max:1024';
            }

            $this->validate($rules, [
                'title.required' => 'Judul portofolio tidak boleh kosong.',
                'description.required' => 'Deskripsi portofolio tidak boleh kosong.',
                'project_date.required' => 'Tanggal proyek tidak boleh kosong.',
                'client_id.required' => 'Klien tidak boleh kosong.',
                'service_id.required' => 'Layanan tidak boleh kosong.',
                'slug.required' => 'Slug tidak boleh kosong.',
            ]);

            $project = Project::findOrFail($this->projectId);
            $data = [
                'title' => $this->title,
                'description' => $this->description,
                'project_date' => $this->project_date,
                'client_id' => $this->client_id,
                'service_id' => $this->service_id,
                'url' => $this->url,
                'slug' => $this->slug,
                'challenge' => $this->challenge,
                'solution' => $this->solution,
            ];

            if ($this->tempImage) {
                $imageName = time() . '.' . $this->tempImage->extension();
                $this->tempImage->storeAs('public/projects', $imageName);
                $data['image'] = 'projects/' . $imageName;
            }

            if ($this->newGallery) {
                $galleryPaths = [];
                foreach ($this->newGallery as $file) {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $file->storeAs('public/projects/gallery', $fileName);
                    $galleryPaths[] = 'projects/gallery/' . $fileName;
                }

                // Merge existing gallery with new gallery
                $existingGallery = json_decode($project->gallery, true) ?? [];
                $data['gallery'] = json_encode(array_merge($existingGallery, $galleryPaths));
            }

            $project->update($data);

            $this->dispatch('updateAlertToast');
            $this->resetInputFields();
        } catch (\Exception $e) {
            $this->dispatch('errorAlertToast');
        }
    }

    public function delete(int $id): void
    {
        $this->projectId = $id;
        $this->deleteProject();
    }

    public function deleteProject(): void
    {
        try {
            if ($this->projectId) {
                Project::findOrFail($this->projectId)->delete();
                $this->dispatch('deleteAlertToast');
                $this->resetInputFields();
            }
        } catch (\Exception $e) {
            $this->dispatch('errorAlertToast');
        }
    }

    public function updated($propertyName): void
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'project_date' => 'required|date',
            'client_id' => 'required|integer',
            'service_id' => 'required|integer',
            'url' => 'nullable|string|max:255',
            'slug' => 'required|string|max:255',
        ];

        $messages = [
            'title.required' => 'Judul portofolio tidak boleh kosong.',
            'description.required' => 'Deskripsi portofolio tidak boleh kosong.',
            'project_date.required' => 'Tanggal proyek tidak boleh kosong.',
            'client_id.required' => 'Klien tidak boleh kosong.',
            'service_id.required' => 'Layanan tidak boleh kosong.',
            'slug.required' => 'Slug tidak boleh kosong.',
        ];

        if ($propertyName === 'tempImage') {
            $rules['tempImage'] = 'image|max:1024';
        }

        $this->validateOnly($propertyName, $rules, $messages);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function descriptionUpdated($value)
    {
        $this->description = $value;
    }
}; ?>

<div>

    <div class="col-md-12">
        <div class="card card-round">
            <div class="card-header">
                <div class="card-head-row">
                    <div class="card-title">Portofolio</div>
                    <div class="card-tools">
                        <a href="#" class="btn btn-info btn-sm me-2" data-bs-toggle="modal" data-bs-target="#modalTambah" wire:click="resetInputFields">
                            <span class="btn-label">
                                <i class="fa fa-plus"></i>
                            </span>
                            Tambah Portofolio
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Cari portofolio..." wire:model.live.debounce.300ms="search">
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
                                <th>Gambar</th>
                                <th>Judul / Produk</th>
                                <th>Klien</th>
                                <th>Slug</th>
                                <th>Tanggal</th>
                                <th>Gallery</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($projects as $index => $project)
                            <tr>
                                <td>{{ $projects->firstItem() + $index }}</td>
                                <td>
                                    <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->title }}" class="img-fluid" style="width: 80px; height: 80px; object-fit: cover;">
                                </td>
                                <td><b><a href="{{ $project->url}}" target="_blank">{{ $project->title }}</a></b>
                                    <br> <small class="text-muted">{{ $project->service->title }}</small>
                                </td>
                                </td>
                                <td>{{ $project->client->name }}</td>
                                <td>{{ $project->slug }}</td>
                                <td>{{ $project->project_date }}</td>
                                <td>
                                    @if(!empty($project->gallery) && is_array($project->gallery))
                                    <div class="d-flex flex-wrap gap-1" style="max-width: 200px;">
                                        @foreach($project->gallery as $image)
                                        @if(is_string($image) && !empty($image))
                                        <img src="{{ asset('storage/' . $image) }}" alt="Gallery Image" class="img-fluid" style="width: 50px; height: 50px; object-fit: cover;">
                                        @endif
                                        @endforeach
                                    </div>
                                    @else
                                    <span class="text-muted">Tidak ada galeri</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="#" wire:click.prevent="edit({{ $project->id }})" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="#" wire:click.prevent="delete({{ $project->id }})" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus portofolio ini?')">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada portofolio ditemukan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="justify-content-between">
                        {{ $projects->links() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- modalTambah -->
        <div wire:ignore.self class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahLabel">Tambah Portofolio</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form wire:submit.prevent="store" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="title" class="form-label">Judul</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" placeholder="Judul Portofolio" wire:model.live="title">
                                @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="tempImage" class="form-label">Gambar</label>
                                <input type="file" class="form-control @error('tempImage') is-invalid @enderror" id="tempImage" wire:model="tempImage">
                                <small class="text-muted">Max size 1MB</small> <br>
                                @error('tempImage') <span class="text-danger">{{ $message }}</span> @enderror

                                <div wire:loading wire:target="tempImage" class="mt-2">
                                    <span class="text-muted">Mengupload...</span>
                                </div>

                                @if($tempImage)
                                <img src="{{ $tempImage->temporaryUrl() }}" class="img-thumbnail mt-2" style="max-height: 200px">
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <div wire:ignore>
                                    <textarea id="ckeditor" class="form-control">{{ $description }}</textarea>
                                </div>
                                @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="project_date" class="form-label">Tanggal Proyek</label>
                                <input type="date" class="form-control @error('project_date') is-invalid @enderror" id="project_date" wire:model="project_date">
                                @error('project_date') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="client_id" class="form-label">Klien</label>
                                <select class="form-select @error('client_id') is-invalid @enderror" id="client_id" wire:model="client_id">
                                    <option value="0">Pilih Klien</option>
                                    @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                    @endforeach
                                </select>
                                @error('client_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="service_id" class="form-label">Layanan</label>
                                <select class="form-select @error('service_id') is-invalid @enderror" id="service_id" wire:model="service_id">
                                    <option value="0">Pilih Layanan</option>
                                    @foreach($services as $service)
                                    <option value="{{ $service->id }}">{{ $service->title }}</option>
                                    @endforeach
                                </select>
                                @error('service_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="url" class="form-label">URL (Opsional)</label>
                                <input type="text" class="form-control @error('url') is-invalid @enderror" id="url" placeholder="https://example.com" wire:model="url">
                                @error('url') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" placeholder="Slug Portofolio" wire:model.live="slug">
                                @error('slug') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="challenge" class="form-label">Tantangan (Opsional)</label>
                                <textarea class="form-control @error('challenge') is-invalid @enderror" id="challenge" rows="3" wire:model="challenge" placeholder="Tantangan proyek"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="solution" class="form-label">Solusi (Opsional)</label>
                                <textarea class="form-control @error('solution') is-invalid @enderror" id="solution" rows="3" wire:model="solution" placeholder="Solusi proyek"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="gallery" class="form-label">Galeri (Opsional)</label>
                                <input type="file" class="form-control" id="gallery" wire:model="gallery" multiple>
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
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditLabel">Edit Portofolio</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form wire:submit.prevent="updateProject" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="title" class="form-label">Judul</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" wire:model.live="title">
                                @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="tempImage" class="form-label">Gambar</label>
                                <input type="file" class="form-control @error('tempImage') is-invalid @enderror" id="tempImage" wire:model="tempImage">
                                <small class="text-muted">Max size 1MB</small> <br>
                                @error('tempImage') <span class="text-danger">{{ $message }}</span> @enderror

                                <div wire:loading wire:target="tempImage" class="mt-2">
                                    <span class="text-muted">Mengupload...</span>
                                </div>

                                @if($tempImage)
                                <img src="{{ $tempImage->temporaryUrl() }}" class="img-thumbnail mt-2" style="max-height: 200px">
                                @elseif($image)
                                <img src="{{ asset('storage/' . $image) }}" class="img-thumbnail mt-2" style="max-height: 200px">
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <div wire:ignore>
                                    <textarea id="ckeditor-edit">{{ $description }}</textarea>
                                </div>
                                @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="project_date" class="form-label">Tanggal Proyek</label>
                                <input type="date" class="form-control @error('project_date') is-invalid @enderror" id="project_date" wire:model="project_date">
                                @error('project_date') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="client_id" class="form-label">Klien</label>
                                <select class="form-select @error('client_id') is-invalid @enderror" id="client_id" wire:model="client_id">
                                    <option value="0">Pilih Klien</option>
                                    @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                    @endforeach
                                </select>
                                @error('client_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="service_id" class="form-label">Layanan</label>
                                <select class="form-select @error('service_id') is-invalid @enderror" id="service_id" wire:model="service_id">
                                    <option value="0">Pilih Layanan</option>
                                    @foreach($services as $service)
                                    <option value="{{ $service->id }}">{{ $service->title }}</option>
                                    @endforeach
                                </select>
                                @error('service_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="url" class="form-label">URL (Opsional)</label>
                                <input type="text" class="form-control @error('url') is-invalid @enderror" id="url" wire:model="url">
                                @error('url') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" wire:model.live="slug">
                                @error('slug') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="challenge" class="form-label">Tantangan (Opsional)</label>
                                <textarea class="form-control @error('challenge') is-invalid @enderror" id="challenge" rows="3" wire:model="challenge"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="solution" class="form-label">Solusi (Opsional)</label>
                                <textarea class="form-control @error('solution') is-invalid @enderror" id="solution" rows="3" wire:model="solution"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="gallery" class="form-label">Galeri (Opsional)</label>
                                <input type="file" class="form-control" id="newGallery" wire:model="newGallery" multiple>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-primary">
                                    <span wire:loading.remove>Update</span>
                                    <span wire:loading>Mengupdate...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <livewire:_alert />
    </div>
    @push('styles')
    <style>
        .ck-editor__editable {
            min-height: 200px;
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.2.0/classic/ckeditor.js"></script>
    <script>
        document.addEventListener('livewire:init', () => {
            let editors = {};

            // Inisialisasi CKEditor untuk modal tambah
            function initCreateEditor() {
                if (!editors['create']) {
                    ClassicEditor
                        .create(document.querySelector('#ckeditor'), {
                            ckfinder: {
                                uploadUrl: "{{ route('image.upload') }}?_token={{ csrf_token() }}"
                            }
                        })
                        .then(editor => {
                            editors['create'] = editor;
                            editor.model.document.on('change:data', () => {
                                @this.set('description', editor.getData());
                            });
                        })
                        .catch(error => {
                            console.error(error);
                        });
                }
            }

            // Inisialisasi CKEditor untuk modal edit
            function initEditEditor() {
                if (!editors['edit']) {
                    ClassicEditor
                        .create(document.querySelector('#ckeditor-edit'), {
                            ckfinder: {
                                uploadUrl: "{{ route('image.upload') }}?_token={{ csrf_token() }}"
                            }
                        })
                        .then(editor => {
                            editors['edit'] = editor;
                            editor.model.document.on('change:data', () => {
                                @this.set('description', editor.getData());
                            });

                            // Set data awal dari Livewire ke CKEditor
                            editor.setData(@this.get('description'));
                        })
                        .catch(error => {
                            console.error(error);
                        });
                } else {
                    // Jika editor sudah ada, perbarui datanya
                    editors['edit'].setData(@this.get('description'));
                }
            }

            // Handle modal show
            $('#modalTambah').on('shown.bs.modal', () => {
                initCreateEditor();
                if (editors['create']) {
                    editors['create'].setData(@this.get('description'));
                }
            });

            $('#modalEdit').on('shown.bs.modal', () => {
                initEditEditor();
            });

            // Reset saat modal tertutup
            $('#modalTambah, #modalEdit').on('hidden.bs.modal', () => {
                for (let key in editors) {
                    if (editors[key]) {
                        editors[key].destroy();
                        editors[key] = null;
                    }
                }
            });

            // Update editor saat data berubah
            Livewire.on('descriptionUpdated', (content) => {
                if (editors['edit']) {
                    editors['edit'].setData(content);
                }
            });
        });
    </script>
    @endpush
</div>