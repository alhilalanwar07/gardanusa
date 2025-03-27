<?php

use App\Models\Team;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination, WithFileUploads;
    public $search = '';
    public $perpage = 10;

    public $name, $position, $bio, $photo, $tim_id;
    public $social_links = '[]';
    public $photo_baru;

    public $socialLinksData = [
        'facebook' => '',
        'instagram' => '',
        'linkedin' => '',
    ];

    public function setPerPage($perpage)
    {
        $this->perpage = $perpage;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Untuk edit data
    public $teamMember;

    protected $rules = [
        'name' => 'required|string|max:255',
        'position' => 'required|string|max:255',
        'bio' => 'required|string',
        'social_links' => 'required|json',
        'photo' => 'required|image|max:1024'
    ];

    // message
    protected $messages = [
        'name.required' => 'Nama tidak boleh kosong',
        'position.required' => 'Posisi tidak boleh kosong',
        'bio.required' => 'Bio tidak boleh kosong',
        'social_links.required' => 'Social media links tidak boleh kosong',
        'photo.required' => 'Foto tidak boleh kosong',
        'photo.image' => 'Foto harus berupa gambar',
        'photo.max' => 'Foto maksimal 1MB',
    ];

    public function mount($teamMember = null)
    {
        if ($teamMember) {
            $this->teamMember = $teamMember;
            $this->fill($teamMember->toArray());
            $this->social_links = json_encode($teamMember->social_links);
        }
    }

    public function with(): array
    {
        return [
            'tims' => Team::where('name', 'like', '%' . $this->search . '%')->paginate($this->perpage),
        ];
    }

    public function resetInput()
    {
        $this->name = '';
        $this->position = '';
        $this->bio = '';
        $this->social_links = '[]';
        $this->photo = '';
    }

    public function edit($id)
    {
        $tim = Team::find($id);
        $this->name = $tim->name;
        $this->position = $tim->position;
        $this->bio = $tim->bio;
        $this->photo = $tim->photo;
        $this->tim_id = $tim->id;
        $this->socialLinksData = $tim->social_links;
    }

    public function save()
    {
        $this->validate();

        // Prepare the data array
        $data = [
            'name' => $this->name,
            'position' => $this->position,
            'bio' => $this->bio,
            'social_links' => $this->socialLinksData ?? json_decode($this->social_links, true),
        ];

        // Handle photo upload
        if ($this->photo && !is_string($this->photo)) {
            $data['photo'] = $this->photo->store('team-members', 'public');
        }

        // Save or update the team member
        if ($this->teamMember) {
            $this->teamMember->update($data);
        } else {
            Team::create($data);
        }

        $this->resetInput();
        $this->dispatch('tambahAlertToast');
    }

    // update
    public function update()
    {
        $this->validate([
            'name' => 'required',
            'position' => 'required',
            'bio' => 'required',
            'social_links' => 'required|json',
        ], [
            'name.required' => 'Nama tidak boleh kosong',
            'position.required' => 'Posisi tidak boleh kosong',
            'bio.required' => 'Bio tidak boleh kosong',
            'social_links.required' => 'Social media links tidak boleh kosong',
        ]);

        $data = [
            'name' => $this->name,
            'position' => $this->position,
            'bio' => $this->bio,
            'social_links' => $this->socialLinksData ?? json_decode($this->social_links, true),
        ];

        if ($this->photo_baru && !is_string($this->photo_baru)) {
            $data['photo'] = $this->photo_baru->store('team-photos', 'public');
        }

        Team::find($this->tim_id)->update($data);

        $this->resetInput();
        $this->dispatch('updateAlertToast');
    }

    public function store()
    {
        // Make sure to add use Livewire\WithFileUploads; at the top of your component
        $this->validate([
            'name' => 'required',
            'position' => 'required',
            'bio' => 'required',
            'social_links' => 'required|json', // Validate as JSON string
            'photo' => 'required|image|max:1024',
        ], [
            'name.required' => 'Nama tidak boleh kosong',
            'position.required' => 'Posisi tidak boleh kosong',
            'bio.required' => 'Bio tidak boleh kosong',
            'social_links.required' => 'Social media links tidak boleh kosong',
            'photo.required' => 'Foto tidak boleh kosong',
            'photo.image' => 'Foto harus berupa gambar',
            'photo.max' => 'Foto maksimal 1MB',
        ]);

        // Store the image in public storage
        $photoPath = null;
        if ($this->photo) {
            $photoPath = $this->photo->store('team-photos', 'public');
        }

        // Make sure social_links is a proper array
        $socialLinks = is_string($this->social_links) ?
            json_decode($this->social_links, true) :
            $this->social_links;

        Team::create([
            'name' => $this->name,
            'position' => $this->position,
            'bio' => $this->bio,
            'social_links' => $socialLinks, // This will be cast to JSON by Laravel
            'photo' => $photoPath,
        ]);

        $this->resetInput();
        $this->dispatch('tambahAlertToast');
    }

    public function delete($id)
    {
        $tim = Team::find($id);
        $tim->delete();
        $this->dispatch('deleteAlertToast');
    }
}; ?>

<div>
    <div class="col-md-12">
        <div class="card card-round">
            <div class="card-header">
                <div class="card-head-row">
                    <div class="card-title">Tim</div>
                    <div class="card-tools">
                        <a href="#" class="btn btn-info  btn-sm me-2" data-bs-toggle="modal" data-bs-target="#modalTambah">
                            <span class="btn-label">
                                <i class="fa fa-plus"></i>
                            </span>
                            Tambah Tim
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
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>Posisi</th>
                                <th>Bio</th>
                                <th>Sosial Media</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $no = 1;
                            @endphp
                            @forelse($tims as $tim)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>
                                    <img src="{{ asset('storage/' . $tim->photo) }}" alt="{{ $tim->name }}" class="img-fluid" style="width: 50px; height: 50px; object-fit: cover;">
                                </td>
                                <td>{{ $tim->name }}</td>
                                <td>{{ $tim->position }}</td>
                                <td>{{ $tim->bio }}</td>
                                <td>
                                    @foreach($tim->social_links as $key => $value)
                                    <a href="{{ $value }}" target="_blank" class=" text-decoration-none">
                                        <span class="fa-stack fa-1x text-decoration-none">
                                            <i class="fab fa-{{ $key }} fa-stack-2x"></i>
                                        </span>
                                    </a>
                                    @endforeach
                                </td>
                                <td>
                                    <a href="#" wire:click.prevent="edit({{ $tim->id }})" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="#" wire:click.prevent="delete({{ $tim->id }})" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus tim ini?')">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Data tidak ditemukan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="justify-content-between">
                        {{ $tims->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahLabel">Tambah Tim</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form wire:submit.prevent="store" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Nama" wire:model="name">
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="position" class="form-label">Posisi</label>
                            <input type="text" class="form-control @error('position') is-invalid @enderror" id="position" placeholder="Posisi" wire:model="position">
                            @error('position') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" rows="3" wire:model="bio"></textarea>
                            @error('bio') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Social Media Links</label>
                            <div>
                                <div class="row mb-2">
                                    <div class="col-md-6 mb-2">
                                        <label>Facebook</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            wire:model.defer="socialLinksData.facebook"
                                            placeholder="https://facebook.com/username">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Instagram</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            wire:model.defer="socialLinksData.instagram"
                                            placeholder="https://instagram.com/username">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>LinkedIn</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            wire:model.defer="socialLinksData.linkedin"
                                            placeholder="https://linkedin.com/in/username">
                                    </div>
                                </div>
                            </div>
                            @error('social_links') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="photo" class="form-label">Foto</label>
                            <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo" wire:model="photo">

                            @if($photo && !is_string($photo))
                            <img src="{{ $photo->temporaryUrl() }}" class="img-thumbnail mt-2" style="max-height: 200px">
                            @endif

                            <small class="text-muted">Max size 1MB</small>
                            @error('photo') <span class="text-danger">{{ $message }}</span> @enderror
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
    <div wire:ignore.self class="modal fade " id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditLabel">Edit Kendaraan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form wire:submit.prevent="update" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Nama" wire:model="name">
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="position" class="form-label">Posisi</label>
                            <input type="text" class="form-control @error('position') is-invalid @enderror" id="position" placeholder="Posisi" wire:model="position">
                            @error('position') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" rows="3" wire:model="bio"></textarea>
                            @error('bio') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Social Media Links</label>
                            <div wire:ignore>
                                <div class="row mb-2">
                                    <div class="col-md-6 mb-2">
                                        <label>Facebook</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            wire:model.defer="socialLinksData.facebook"
                                            placeholder="https://facebook.com/username">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>Instagram</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            wire:model.defer="socialLinksData.instagram"
                                            placeholder="https://instagram.com/username">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label>LinkedIn</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            wire:model.defer="socialLinksData.linkedin"
                                            placeholder="https://linkedin.com/in/username">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="photo_baru" class="form-label">Foto</label>
                            <input type="file" class="form-control" id="photo" wire:model="photo_baru">
                            <small class="text-muted">Max size 1MB</small><br>
                            <div wire:loading wire:target="photo_baru" class="text-info mt-2">
                                Loading preview...
                            </div>

                            @if($photo_baru && !is_string($photo_baru))
                            <!-- Jika foto baru diunggah -->
                            <img src="{{ $photo_baru->temporaryUrl() }}" class="img-thumbnail mt-2" style="max-height: 200px">

                            @elseif($photo)
                            <!-- Jika foto sudah ada di database -->
                            <img src="{{ asset('storage/' . $photo) }}" class="img-thumbnail mt-2" style="max-height: 200px">
                            @endif

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">
                                <span wire:loading.remove wire:target="update">Update</span>
                                <span wire:loading wire:target="update">Memperbarui...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <livewire:_alert />
</div>