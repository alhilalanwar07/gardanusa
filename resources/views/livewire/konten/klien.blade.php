<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Client;
use Illuminate\Support\Str;

new class extends Component {
    use WithPagination, WithFileUploads;
    public ?int $clientId = null;
    public string $name = '';
    public $logo = null;
    public string $website = '';
    public string $search = '';
    public int $perPage = 10;
    public $tempLogo = null;

    protected $listeners = ['deleteConfirmed' => 'deleteClient'];

    public function with(): array
    {
        return [
            'clients' => Client::when($this->search, function ($query) {
                return $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('website', 'like', '%' . $this->search . '%');
            })->orderBy('created_at', 'desc')->paginate($this->perPage),
        ];
    }

    private function resetInputFields(): void
    {
        $this->reset(['name', 'logo', 'website', 'clientId', 'tempLogo']);
    }

    public function store(): void
    {
        try {
            $this->validate([
                'name' => 'required|string|max:255',
                'tempLogo' => 'required|image|max:1024',
                'website' => 'nullable|url|max:255',
            ],[
                'name.required' => 'Nama tidak boleh kosong',
                'name.max' => 'Nama maksimal 255 karakter',
                'tempLogo.required' => 'Logo tidak boleh kosong',
                'tempLogo.image' => 'File harus berupa gambar',
                'tempLogo.max' => 'Ukuran logo maksimal 1MB',
                'website.url' => 'Website harus berupa URL yang valid',
            ]);

            $logoName = time() . '.' . $this->tempLogo->extension();
            $this->tempLogo->storeAs('public/clients', $logoName);

            Client::create([
                'name' => $this->name,
                'logo' => 'clients/' . $logoName,
                'website' => $this->website,
            ]);

            $this->dispatch('tambahAlertToast');
            $this->resetInputFields();
        } catch (\Exception $e) {
            $this->dispatch('errorAlertToast');
        }
    }

    public function edit(int $id): void
    {
        $client = Client::findOrFail($id);
        $this->clientId = $client->id;
        $this->name = $client->name;
        $this->logo = $client->logo;
        $this->website = $client->website;
    }

    public function updateClient(): void
    {
        try {
            $rules = [
                'name' => 'required|string|max:255',
                'website' => 'nullable|url|max:255',
            ];

            if ($this->tempLogo) {
                $rules['tempLogo'] = 'image|max:1024';
            }

            $this->validate($rules, [
                'name.required' => 'Nama tidak boleh kosong',
                'name.max' => 'Nama maksimal 255 karakter',
                'tempLogo.image' => 'File harus berupa gambar',
                'tempLogo.max' => 'Ukuran logo maksimal 1MB',
                'website.url' => 'Website harus berupa URL yang valid',
            ]);

            $client = Client::findOrFail($this->clientId);
            $data = [
                'name' => $this->name,
                'website' => $this->website,
            ];

            if ($this->tempLogo) {
                $logoName = time() . '.' . $this->tempLogo->extension();
                $this->tempLogo->storeAs('public/clients', $logoName);
                $data['logo'] = 'clients/' . $logoName;
            }

            $client->update($data);

            $this->dispatch('updateAlertToast');
            $this->resetInputFields();
        } catch (\Exception $e) {
            $this->dispatch('errorAlertToast');
        }
    }

    public function delete(int $id): void
    {
        $this->clientId = $id;
        $this->deleteClient();
    }

    public function deleteClient(): void
    {
        try {
            if ($this->clientId) {
                Client::findOrFail($this->clientId)->delete();
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
            'name' => 'required|string|max:255',
            'website' => 'nullable|url|max:255',
        ];

        if ($propertyName === 'tempLogo') {
            $rules['tempLogo'] = 'image|max:1024';
        }

        $this->validateOnly($propertyName, $rules, [
            'name.required' => 'Nama tidak boleh kosong',
            'name.max' => 'Nama maksimal 255 karakter',
            'tempLogo.image' => 'File harus berupa gambar',
            'tempLogo.max' => 'Ukuran logo maksimal 1MB',
            'website.url' => 'Website harus berupa URL yang valid',
        ]);
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
                    <div class="card-title">Klien</div>
                    <div class="card-tools">
                        <a href="#" class="btn btn-info btn-sm me-2" data-bs-toggle="modal" data-bs-target="#modalTambah">
                            <span class="btn-label">
                                <i class="fa fa-plus"></i>
                            </span>
                            Tambah Klien
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Cari klien..." wire:model.live.debounce.300ms="search">
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
                                <th>Logo</th>
                                <th>Nama</th>
                                <th>Website</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clients as $index => $client)
                            <tr>
                                <td>{{ $clients->firstItem() + $index }}</td>
                                <td>
                                    <img src="{{ asset('storage/' . $client->logo) }}" alt="{{ $client->name }}" class="img-fluid" style="width: 80px; height: 80px; object-fit: contain;">
                                </td>
                                <td>{{ $client->name }}</td>
                                <td>
                                    @if($client->website)
                                    <a href="{{ $client->website }}" target="_blank" class="btn btn-sm btn-info">
                                        <i class="fa fa-link"></i> Website
                                    </a>
                                    @else
                                    <span class="badge bg-secondary">Tidak ada website</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="#" wire:click.prevent="edit({{ $client->id }})" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="#" wire:click.prevent="delete({{ $client->id }})" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus klien ini?')">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada klien ditemukan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="justify-content-between">
                        {{ $clients->links() }}
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahLabel">Tambah Klien</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form wire:submit.prevent="store" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Nama Klien" wire:model="name">
                                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="tempLogo" class="form-label">Logo</label>
                                <input type="file" class="form-control @error('tempLogo') is-invalid @enderror" id="tempLogo" wire:model="tempLogo">
                                <small class="text-muted">Max size 1MB</small> <br>
                                @error('tempLogo') <span class="text-danger">{{ $message }}</span> @enderror

                                <div wire:loading wire:target="tempLogo" class="mt-2">
                                    <span class="text-muted">Mengupload...</span>
                                </div>

                                @if($tempLogo)
                                <img src="{{ $tempLogo->temporaryUrl() }}" class="img-thumbnail mt-2" style="max-height: 200px">
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="website" class="form-label">Website (Opsional)</label>
                                <input type="text" class="form-control @error('website') is-invalid @enderror" id="website" placeholder="https://example.com" wire:model="website">
                                @error('website') <span class="text-danger">{{ $message }}</span> @enderror
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
                        <h5 class="modal-title" id="modalEditLabel">Edit Klien</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form wire:submit.prevent="updateClient" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Nama Klien" wire:model="name">
                                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="tempLogo" class="form-label">Logo</label>
                                <input type="file" class="form-control @error('tempLogo') is-invalid @enderror" id="tempLogo" wire:model="tempLogo">
                                @error('tempLogo') <span class="text-danger">{{ $message }}</span> @enderror

                                <div wire:loading wire:target="tempLogo" class="mt-2">
                                    <span class="text-muted">Mengupload...</span>
                                </div>

                                @if($tempLogo && !is_string($tempLogo))
                                <img src="{{ $tempLogo->temporaryUrl() }}" class="img-thumbnail mt-2" style="max-height: 200px">
                                @elseif($logo)
                                <img src="{{ asset('storage/' . $logo) }}" class="img-thumbnail mt-2" style="max-height: 200px">
                                @endif
                                <small class="text-muted">Max size 1MB</small>
                            </div>

                            <div class="mb-3">
                                <label for="website" class="form-label">Website (Opsional)</label>
                                <input type="text" class="form-control @error('website') is-invalid @enderror" id="website" placeholder="https://example.com" wire:model="website">
                                @error('website') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-primary">
                                    <span wire:loading.remove wire:target="updateClient">Update</span>
                                    <span wire:loading wire:target="updateClient">Memperbarui...</span>
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
