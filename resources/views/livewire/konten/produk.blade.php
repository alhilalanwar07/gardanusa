<?php

use Livewire\Volt\Component;
use App\Models\Product;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Str;

new class extends Component {
    use WithPagination, WithFileUploads;
    public ?int $productId = null;
    public string $title = '';
    public $image = null;
    public string $desc = '';
    public string $link = '';
    public string $search = '';
    public int $perPage = 10;
    public $tempImage = null;


    protected $listeners = ['deleteConfirmed' => 'deleteProduct'];

    public function with(): array
    {
        return [
            'products' => Product::when($this->search, function ($query) {
                return $query->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('desc', 'like', '%' . $this->search . '%')
                    ->orWhere('link', 'like', '%' . $this->search . '%');
            })->orderBy('created_at', 'desc')->paginate($this->perPage),
        ];
    }

    private function resetInputFields(): void
    {
        $this->reset(['title', 'image', 'desc', 'link', 'productId', 'tempImage']);
    }

    public function store(): void
    {
        try {
            $this->validate([
                'title' => 'required|string|max:255',
                'tempImage' => 'required|image|max:1024',
                'desc' => 'required|string|max:1000',
                'link' => 'nullable|string|max:255',
            ],[
                'title.required' => 'Judul tidak boleh kosong',
                'title.max' => 'Judul maksimal 255 karakter',
                'tempImage.required' => 'Gambar tidak boleh kosong',
                'tempImage.image' => 'File harus berupa gambar',
                'tempImage.max' => 'Ukuran gambar maksimal 1MB',
                'desc.required' => 'Deskripsi tidak boleh kosong',
                'desc.max' => 'Deskripsi maksimal 1000 karakter',
            ]);

            $imageName = time() . '.' . $this->tempImage->extension();
            $this->tempImage->storeAs('public/products', $imageName);

            Product::create([
                'title' => $this->title,
                'image' => 'products/' . $imageName,
                'desc' => $this->desc,
                'link' => $this->link,
            ]);

            $this->dispatch('tambahAlertToast');
            $this->resetInputFields();
        } catch (\Exception $e) {
            $this->dispatch('errorAlertToast');
        }
    }

    public function edit(int $id): void
    {
        $product = Product::findOrFail($id);
        $this->productId = $product->id;
        $this->title = $product->title;
        $this->image = $product->image;
        $this->desc = $product->desc;
        $this->link = $product->link;
    }

    public function updateProduct(): void
    {
        try {
            $rules = [
                'title' => 'required|string|max:255',
                'desc' => 'required|string|max:1000',
                'link' => 'nullable|string|max:255',
            ];

            if ($this->tempImage) {
                $rules['tempImage'] = 'image|max:1024';
            }

            $this->validate($rules, [
                'title.required' => 'Judul tidak boleh kosong',
                'title.max' => 'Judul maksimal 255 karakter',
                'tempImage.image' => 'File harus berupa gambar',
                'tempImage.max' => 'Ukuran gambar maksimal 1MB',
                'desc.required' => 'Deskripsi tidak boleh kosong',
                'desc.max' => 'Deskripsi maksimal 1000 karakter',
            ]);

            $product = Product::findOrFail($this->productId);
            $data = [
                'title' => $this->title,
                'desc' => $this->desc,
                'link' => $this->link,
            ];

            if ($this->tempImage) {
                $imageName = time() . '.' . $this->tempImage->extension();
                $this->tempImage->storeAs('public/products', $imageName);
                $data['image'] = 'products/' . $imageName;
            }

            $product->update($data);

            $this->dispatch('updateAlertToast');
            $this->resetInputFields();
        } catch (\Exception $e) {
            $this->dispatch('errorAlertToast');
        }
    }

    public function delete(int $id): void
    {
        $this->productId = $id;
        $this->deleteProduct();
    }

    public function deleteProduct(): void
    {
        try {
            if ($this->productId) {
                Product::findOrFail($this->productId)->delete();
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
            'desc' => 'required|string|max:1000',
            'link' => 'nullable|string|max:255',
        ];

        if ($propertyName === 'tempImage') {
            $rules['tempImage'] = 'image|max:1024';
        }

        $this->validateOnly($propertyName, $rules, [
            'title.required' => 'Judul tidak boleh kosong',
            'title.max' => 'Judul maksimal 255 karakter',
            'tempImage.image' => 'File harus berupa gambar',
            'tempImage.max' => 'Ukuran gambar maksimal 1MB',
            'desc.required' => 'Deskripsi tidak boleh kosong',
            'desc.max' => 'Deskripsi maksimal 1000 karakter',
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
                    <div class="card-title">Produk</div>
                    <div class="card-tools">
                        <a href="#" class="btn btn-info btn-sm me-2" data-bs-toggle="modal" data-bs-target="#modalTambah">
                            <span class="btn-label">
                                <i class="fa fa-plus"></i>
                            </span>
                            Tambah Produk
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Cari produk..." wire:model.live.debounce.300ms="search">
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
                                <th>Judul</th>
                                <th>Deskripsi</th>
                                <th>Link</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $index => $product)
                            <tr>
                                <td>{{ $products->firstItem() + $index }}</td>
                                <td>
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->title }}" class="img-fluid" style="width: 80px; height: 80px; object-fit: cover;">
                                </td>
                                <td>{{ $product->title }}</td>
                                <td>{{ Str::limit($product->desc, 100) }}</td>
                                <td>
                                    @if($product->link)
                                    <a href="{{ $product->link }}" target="_blank" class="btn btn-sm btn-info">
                                        <i class="fa fa-link"></i> Link
                                    </a>
                                    @else
                                    <span class="badge bg-secondary">Tidak ada link</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="#" wire:click.prevent="edit({{ $product->id }})" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="#" wire:click.prevent="delete({{ $product->id }})" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada produk ditemukan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="justify-content-between">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahLabel">Tambah Produk</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form wire:submit.prevent="store" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="title" class="form-label">Judul</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" placeholder="Judul Produk" wire:model="title">
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
                                <label for="desc" class="form-label">Deskripsi</label>
                                <textarea class="form-control @error('desc') is-invalid @enderror" id="desc" rows="3" wire:model="desc" placeholder="Deskripsi produk"></textarea>
                                @error('desc') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="link" class="form-label">Link (Opsional)</label>
                                <input type="text" class="form-control @error('link') is-invalid @enderror" id="link" placeholder="https://example.com" wire:model="link">
                                @error('link') <span class="text-danger">{{ $message }}</span> @enderror
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
                        <h5 class="modal-title" id="modalEditLabel">Edit Produk</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form wire:submit.prevent="updateProduct" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="title" class="form-label">Judul</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" placeholder="Judul Produk" wire:model="title">
                                @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="tempImage" class="form-label">Gambar</label>
                                <input type="file" class="form-control @error('tempImage') is-invalid @enderror" id="tempImage" wire:model="tempImage">
                                @error('tempImage') <span class="text-danger">{{ $message }}</span> @enderror

                                <div wire:loading wire:target="tempImage" class="mt-2">
                                    <span class="text-muted">Mengupload...</span>
                                </div>

                                @if($tempImage && !is_string($tempImage))
                                <img src="{{ $tempImage->temporaryUrl() }}" class="img-thumbnail mt-2" style="max-height: 200px">
                                @elseif($image)
                                <img src="{{ asset('storage/' . $image) }}" class="img-thumbnail mt-2" style="max-height: 200px">
                                @endif
                                <small class="text-muted">Max size 1MB</small>
                            </div>

                            <div class="mb-3">
                                <label for="desc" class="form-label">Deskripsi</label>
                                <textarea class="form-control @error('desc') is-invalid @enderror" id="desc" rows="3" wire:model="desc" placeholder="Deskripsi produk"></textarea>
                                @error('desc') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="link" class="form-label">Link (Opsional)</label>
                                <input type="text" class="form-control @error('link') is-invalid @enderror" id="link" placeholder="https://example.com" wire:model="link">
                                @error('link') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-primary">
                                    <span wire:loading.remove wire:target="updateProduct">Update</span>
                                    <span wire:loading wire:target="updateProduct">Memperbarui...</span>
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
