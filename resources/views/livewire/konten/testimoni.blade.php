<?php

use App\Models\Testimonial;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

new class extends Component {
    use WithPagination, WithFileUploads;
    public ?int $testimoniId = null;
    public ?int $client_id = null;
    public ?int $project_id = null;
    public string $name = '';
    public string $position = '';
    public string $company = '';
    public string $message = '';
    public $image = null;
    public $tempImage = null;
    public string $search = '';
    public int $perPage = 10;

    protected $listeners = ['deleteConfirmed' => 'deleteTestimoni'];

    public function with(): array
    {
        return [
            'testimonials' => Testimonial::with(['client', 'project'])
                ->when($this->search, function($query) {
                    return $query->where('name', 'like', '%' . $this->search . '%')
                                ->orWhere('content', 'like', '%' . $this->search . '%');
                })
                ->orderBy('created_at', 'desc')
                ->paginate($this->perPage),
            'clients' => \App\Models\Client::orderBy('name', 'asc')->get(),
            'projects' => \App\Models\Project::whereNotIn('id', function($query) {
                $query->select('project_id')->from('testimonials');
            })->orderBy('title', 'asc')->get(),
        ];
    }

    public function resetInputFields(): void 
    {
        $this->reset([
            'name',
            'position', 
            'company',
            'message',
            'image',
            'tempImage',
            'testimoniId',
            'client_id',
            'project_id'
        ]);
    }

    // edit update
    public function edit(int $id): void
    {
        $testimonial = Testimonial::find($id);
        if ($testimonial) {
            $this->testimoniId = $testimonial->id;
            $this->name = $testimonial->name;
            $this->position = $testimonial->position;
            $this->company = $testimonial->company;
            $this->message = $testimonial->content;
            $this->image = $testimonial->image;
            $this->client_id = $testimonial->client_id;
            $this->project_id = $testimonial->project_id;
        }
    }

    public function update(): void
    {
        try {
            $this->validate([
                'name' => 'required|string|max:255',
                'position' => 'required|string|max:255',
                'company' => 'required|string|max:255', 
                'message' => 'required|string',
                'tempImage' => 'nullable|image|max:1024',
                'client_id' => 'required|exists:clients,id',
                'project_id' => 'required|exists:projects,id'
            ],[
                'name.required' => 'Nama harus diisi',
                'name.string' => 'Nama harus berupa teks',
                'name.max' => 'Nama maksimal 255 karakter',
                'position.required' => 'Jabatan harus diisi',
                'position.string' => 'Jabatan harus berupa teks', 
                'position.max' => 'Jabatan maksimal 255 karakter',
                'company.required' => 'Perusahaan harus diisi',
                'company.string' => 'Perusahaan harus berupa teks',
                'company.max' => 'Perusahaan maksimal 255 karakter',
                'message.required' => 'Pesan harus diisi',
                'message.string' => 'Pesan harus berupa teks',
                'tempImage.image' => 'File harus berupa gambar',
                'tempImage.max' => 'Ukuran gambar maksimal 1MB',
                'client_id.required' => 'Klien harus dipilih',
                'client_id.exists' => 'Klien tidak valid',
                'project_id.required' => 'Proyek harus dipilih', 
                'project_id.exists' => 'Proyek tidak valid'
            ]);

            $testimonial = Testimonial::find($this->testimoniId);
            if ($testimonial) {
                if ($this->tempImage) {
                    $imageName = time() . '.' . $this->tempImage->extension();
                    $this->tempImage->storeAs('public/testimonials', $imageName);
                    $testimonial->image = 'testimonials/' . $imageName;
                }
                
                $testimonial->name = $this->name;
                $testimonial->position = $this->position;
                $testimonial->company = $this->company;
                $testimonial->content = $this->message;
                $testimonial->client_id = $this->client_id;
                $testimonial->project_id = $this->project_id;
                
                $testimonial->save();

                $this->dispatch('updateAlertToast');
            } else {
                throw new \Exception('Testimonial not found');
            }
        } catch (\Exception $e) {
            $this->dispatch('errorAlertToast');
        }
    }

    public function store(): void
    {
        try {
            $this->validate([
                'name' => 'required|string|max:255',
                'position' => 'required|string|max:255',
                'company' => 'required|string|max:255', 
                'message' => 'required|string',
                'tempImage' => 'required|image|max:1024',
                'client_id' => 'required|exists:clients,id',
                'project_id' => 'required|exists:projects,id'
            ]);

            $imageName = time() . '.' . $this->tempImage->extension();
            $this->tempImage->storeAs('public/testimonials', $imageName);

            Testimonial::create([
                'name' => $this->name,
                'position' => $this->position,
                'company' => $this->company,
                'content' => $this->message,
                'image' => 'testimonials/' . $imageName,
                'client_id' => $this->client_id,
                'project_id' => $this->project_id
            ]);

            $this->dispatch('tambahAlertToast');
            $this->resetInputFields();
        } catch (\Exception $e) {
            $this->dispatch('errorAlertToast');
        }
    }

    public function delete(int $id): void
    {
        $this->testimoniId = $id;
        $this->dispatch('deleteTestimoni');
    }

    public function deleteTestimoni(): void
    {
        try {
            $testimonial = Testimonial::find($this->testimoniId);
            if ($testimonial) {
                $testimonial->delete();
                $this->dispatch('deleteAlertToast');
            } else {
                $this->dispatch('errorAlertToast');
            }
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
                    <div class="card-title">Testimoni</div>
                    <div class="card-tools">
                        <a href="#" class="btn btn-info btn-sm me-2" data-bs-toggle="modal" data-bs-target="#modalTambah" wire:click="resetInputFields">
                            <span class="btn-label">
                                <i class="fa fa-plus"></i>
                            </span>
                            Tambah Testimoni
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Cari testimoni..." wire:model.live.debounce.300ms="search">
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
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>Perusahaan</th>
                                <th>Proyek</th>
                                <th>Pesan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($testimonials as $index => $testimonial)
                            <tr>
                                <td>{{ $testimonials->firstItem() + $index }}</td>
                                <td>
                                    <img src="{{ asset('storage/' . $testimonial->image) }}" alt="{{ $testimonial->name }}" class="img-fluid rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                                </td>
                                <td>
                                    <b>{{ $testimonial->name }}</b><br>
                                    <small class="text-muted">{{ $testimonial->position }}</small>
                                </td>
                                <td class="text-wrap">{{ $testimonial->company }}</td>
                                <td>
                                    <a href="{{ $testimonial->project->url }}" class="text-decoration-none" target="_blank">
                                        {{ $testimonial->project->title }}
                                    </a>
                                </td>
                                <td class="text-wrap">{{ $testimonial->content }}</td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus testimoni ini?')" wire:click="delete({{ $testimonial->id }})">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada testimoni ditemukan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="justify-content-between">
                        {{ $testimonials->links() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Tambah -->
        <div wire:ignore.self class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahLabel">Tambah Testimoni</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="store">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" wire:model="name">
                                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="position" class="form-label">Jabatan</label>
                                <input type="text" class="form-control @error('position') is-invalid @enderror" id="position" wire:model="position">
                                @error('position') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="company" class="form-label">Perusahaan</label>
                                <input type="text" class="form-control @error('company') is-invalid @enderror" id="company" wire:model="company">
                                @error('company') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="message" class="form-label">Pesan</label>
                                <textarea class="form-control @error('message') is-invalid @enderror" id="message" rows="3" wire:model="message"></textarea>
                                @error('message') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="tempImage" class="form-label">Foto</label>
                                <input type="file" class="form-control @error('tempImage') is-invalid @enderror" id="tempImage" wire:model="tempImage">
                                @error('tempImage') <span class="text-danger">{{ $message }}</span> @enderror
                                
                                @if($tempImage)
                                <img src="{{ $tempImage->temporaryUrl() }}" class="img-thumbnail mt-2" style="max-height: 200px">
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="client_id" class="form-label">Klien</label>
                                <select class="form-select @error('client_id') is-invalid @enderror" id="client_id" wire:model="client_id">
                                    <option value="">Pilih Klien</option>
                                    @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                    @endforeach
                                </select>
                                @error('client_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="project_id" class="form-label">Proyek</label>
                                <select class="form-select @error('project_id') is-invalid @enderror" id="project_id" wire:model="project_id">
                                    <option value="">Pilih Proyek</option>
                                    @foreach($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->title }}</option>
                                    @endforeach
                                </select>
                                @error('project_id') <span class="text-danger">{{ $message }}</span> @enderror
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
        <livewire:_alert />
    </div>
</div>
