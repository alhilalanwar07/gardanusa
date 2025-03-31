<?php

use App\Models\Post;
use Livewire\Volt\Component;
use App\Models\Category;
use App\Models\Tag;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

new class extends Component {
    use WithPagination, WithFileUploads;

    public ?int $postId = null;
    public string $title = '';
    public string $slug = '';
    public string $content = '';
    public string $excerpt = '';
    public $featuredImage = null;
    public $tempFeaturedImage = null;
    public int $category_id = 0;
    public string $meta_title = '';
    public string $meta_description = '';
    public string $published_at = '';
    public string $status = 'draft';
    public array $tags = [];
    public string $search = '';
    public int $perPage = 10;

    protected $listeners = [
        'deleteConfirmed' => 'deletePost',
        'contentUpdated'
    ];

    public function with(): array
    {
        return [
            'posts' => Post::with(['category', 'tags'])
                ->when($this->search, function ($query) {
                    return $query->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('content', 'like', '%' . $this->search . '%');
                })
                ->orderBy('created_at', 'desc')
                ->paginate($this->perPage),
            'categories' => Category::orderBy('name')->get(),
            'allTags' => Tag::orderBy('name')->get(),
        ];
    }

    public function resetInputFields(): void
    {
        $this->reset([
            'postId',
            'title',
            'slug',
            'content',
            'excerpt',
            'tempFeaturedImage',
            'featuredImage',
            'category_id',
            'meta_title',
            'meta_description',
            'published_at',
            'status',
            'tags',
        ]);
        $this->published_at = now()->format('Y-m-d');
    }

    public function store(): void
    {
        try {
            $this->validate(
                [
                    'title' => 'required|string|max:255',
                    'slug' => ['required', 'string', 'max:255', Rule::unique('posts')->ignore($this->postId)],
                    'content' => 'required|string',
                    'tempFeaturedImage' => 'required|image|max:2048',
                    'category_id' => 'required|integer',
                    'published_at' => 'required|date',
                    'status' => 'required|in:draft,published',
                    'excerpt' => 'nullable|string|max:300',
                    'meta_title' => 'nullable|string|max:255',
                    'meta_description' => 'nullable|string|max:160',
                ],
                [
                    'required' => ':attribute tidak boleh kosong.',
                    'title.required' => 'Judul post tidak boleh kosong.',
                    'slug.required' => 'Slug tidak boleh kosong.',
                    'content.required' => 'Konten tidak boleh kosong.',
                    'tempFeaturedImage.required' => 'Gambar utama tidak boleh kosong.',
                    'category_id.required' => 'Kategori tidak boleh kosong.',
                    'published_at.required' => 'Tanggal publikasi tidak boleh kosong.',
                    'status.required' => 'Status tidak boleh kosong.',
                    'excerpt.max' => 'Ringkasan maksimal 300 karakter.',
                    'meta_title.max' => 'Meta title maksimal 255 karakter.',
                    'meta_description.max' => 'Meta deskripsi maksimal 160 karakter.',
                ]
            );

            $imageName = time() . '.' . $this->tempFeaturedImage->extension();
            $this->tempFeaturedImage->storeAs('public/posts', $imageName);

            $post = Post::create([
                'title' => $this->title,
                'slug' => $this->slug,
                'content' => $this->content,
                'excerpt' => $this->excerpt,
                'featured_image' => 'posts/' . $imageName,
                'category_id' => $this->category_id,
                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
                'published_at' => $this->published_at,
                'status' => $this->status,
                'user_id' => Auth::id(),
            ]);

            $post->tags()->sync($this->tags);

            $this->dispatch('tambahAlertToast');
            $this->resetInputFields();
        } catch (\Exception $e) {
            $this->dispatch('errorAlertToast');
        }
    }

    public function updatedTitle($value): void
    {
        $this->slug = Str::slug($value);
        $this->meta_title = $this->meta_title ?: $value;
        if (!$this->meta_description) {
            $this->meta_description = Str::limit(strip_tags($this->content), 160);
        }
    }

    public function edit(int $id): void
    {
        $post = Post::with('tags')->findOrFail($id);
        $this->postId = $post->id;
        $this->title = $post->title;
        $this->slug = $post->slug;
        $this->content = $post->content;
        $this->excerpt = $post->excerpt;
        $this->featuredImage = $post->featured_image;
        $this->category_id = $post->category_id;
        $this->meta_title = $post->meta_title;
        $this->meta_description = $post->meta_description;
        $this->published_at = $post->published_at->format('Y-m-d');
        $this->status = $post->status;
        $this->tags = $post->tags->pluck('id')->toArray();
    }

    public function updatePost(): void
    {
        try {
            $rules = [
                'title' => 'required|string|max:255',
                'slug' => ['required', 'string', 'max:255', Rule::unique('posts')->ignore($this->postId)],
                'content' => 'required|string',
                'category_id' => 'required|integer',
                'published_at' => 'required|date',
                'status' => 'required|in:draft,published',
                'excerpt' => 'nullable|string|max:300',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:160',
            ];

            if ($this->tempFeaturedImage) {
                $rules['tempFeaturedImage'] = 'image|max:2048';
            }

            $this->validate($rules, [
                'title.required' => 'Judul post tidak boleh kosong.',
                'slug.required' => 'Slug tidak boleh kosong.',
                'content.required' => 'Konten tidak boleh kosong.',
                'category_id.required' => 'Kategori tidak boleh kosong.',
                'published_at.required' => 'Tanggal publikasi tidak boleh kosong.',
                'status.required' => 'Status tidak boleh kosong.',
            ]);

            $data = [
                'title' => $this->title,
                'slug' => $this->slug,
                'content' => $this->content,
                'excerpt' => $this->excerpt,
                'category_id' => $this->category_id,
                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
                'published_at' => $this->published_at,
                'status' => $this->status,
            ];

            if ($this->tempFeaturedImage) {
                $imageName = time() . '.' . $this->tempFeaturedImage->extension();
                $this->tempFeaturedImage->storeAs('public/posts', $imageName);
                $data['featured_image'] = 'posts/' . $imageName;
            }

            $post = Post::findOrFail($this->postId);
            $post->update($data);
            $post->tags()->sync($this->tags);

            $this->dispatch('updateAlertToast');
            $this->resetInputFields();
        } catch (\Exception $e) {
            $this->dispatch('errorAlertToast');
        }
    }

    public function deletePost(): void
    {
        try {
            if ($this->postId) {
                $post = Post::findOrFail($this->postId);
                $post->tags()->detach();
                $post->delete();
                $this->dispatch('deleteAlertToast');
                $this->resetInputFields();
            }
        } catch (\Exception $e) {
            $this->dispatch('errorAlertToast');
        }
    }

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName, [
            'title' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', Rule::unique('posts')->ignore($this->postId)],
            'content' => 'required|string',
            'category_id' => 'required|integer',
            'published_at' => 'required|date',
            'status' => 'required|in:draft,published',
        ]);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function contentUpdated($value)
    {
        $this->content = $value;
    }

    public function toggleStatus($id): void
    {
        $post = Post::findOrFail($id);
        $post->status = $post->status === 'published' ? 'draft' : 'published';
        $post->save();
        $this->dispatch('updateAlertToast');
    }
}; ?>

<div>
    <div class="col-md-12">
        <div class="card card-round">
            <div class="card-header">
                <div class="card-head-row">
                    <div class="card-title">Manajemen Post</div>
                    <div class="card-tools">
                        <button class="btn btn-info btn-sm me-2"
                            data-bs-toggle="modal"
                            data-bs-target="#modalTambah"
                            wire:click="resetInputFields">
                            <i class="fa fa-plus"></i>
                            Tambah Post
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text"
                                class="form-control"
                                placeholder="Cari post..."
                                wire:model.live.debounce.300ms="search">
                        </div>
                    </div>
                    <div class="col-md-2 ms-auto">
                        <select class="form-select" wire:model.live="perPage">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-borderless">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Judul</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($posts as $index => $post)
                            <tr>
                                <td>{{ $posts->firstItem() + $index }}</td>
                                <td>
                                    <b>{{ Str::limit($post->title, 50) }}</b>
                                    <br>
                                    <small class="text-muted">{{ $post->category->name }}</small>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-{{ $post->status === 'published' ? 'success' : 'danger' }}"
                                        wire:click="toggleStatus({{ $post->id }})">
                                        <i class="fa fa-toggle-{{ $post->status === 'published' ? 'on' : 'off' }}"></i>
                                        {{ ucfirst($post->status) }}
                                    </button>
                                </td>
                                <td>{{ $post->published_at->format('d M Y') }}</td>
                                <td>
                                    <button wire:click="edit({{ $post->id }})"
                                        class="btn btn-sm btn-warning me-2"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEdit">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button wire:click="deletePost({{ $post->id }})"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Hapus post ini?')">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada post ditemukan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $posts->links() }}
                </div>
            </div>
        </div>

        <!-- Modal Tambah -->
        <div wire:ignore.self class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Post Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="store">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label>Judul Post</label>
                                        <input type="text"
                                            class="form-control @error('title') is-invalid @enderror"
                                            wire:model="title">
                                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label>Slug</label>
                                        <input type="text"
                                            class="form-control @error('slug') is-invalid @enderror"
                                            wire:model="slug">
                                        @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label>Konten</label>
                                        <div wire:ignore>
                                            <textarea id="ckeditor-tambah" class="form-control">{{ $content }}</textarea>
                                        </div>
                                        @error('content') <div class="text-danger small">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card border-0 shadow-sm mb-3">
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label>Gambar Utama</label>
                                                <input type="file"
                                                    class="form-control @error('tempFeaturedImage') is-invalid @enderror"
                                                    wire:model="tempFeaturedImage">
                                                @error('tempFeaturedImage') <div class="invalid-feedback">{{ $message }}</div> @enderror

                                                @if($tempFeaturedImage)
                                                <img src="{{ $tempFeaturedImage->temporaryUrl() }}"
                                                    class="img-fluid mt-3 rounded">
                                                @endif
                                            </div>

                                            <div class="mb-3">
                                                <label>Kategori</label>
                                                <select class="form-select @error('category_id') is-invalid @enderror"
                                                    wire:model="category_id">
                                                    <option value="0">Pilih Kategori</option>
                                                    @foreach($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label>Tag</label>
                                                <select class="form-select"
                                                    wire:model="tags"
                                                    multiple>
                                                    @foreach($allTags as $tag)
                                                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label>Tanggal Publikasi</label>
                                                <input type="date"
                                                    class="form-control @error('published_at') is-invalid @enderror"
                                                    wire:model="published_at">
                                                @error('published_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label>Status</label>
                                                <select class="form-select" wire:model="status">
                                                    <option value="draft">Draft</option>
                                                    <option value="published">Published</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label>Ringkasan (Excerpt)</label>
                                                <textarea class="form-control"
                                                    rows="3"
                                                    wire:model="excerpt"></textarea>
                                            </div>

                                            <div class="mb-3">
                                                <label>Meta Title</label>
                                                <input type="text"
                                                    class="form-control"
                                                    wire:model="meta_title">
                                            </div>

                                            <div class="mb-3">
                                                <label>Meta Description</label>
                                                <textarea class="form-control"
                                                    rows="3"
                                                    wire:model="meta_description"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
        <!-- Modal Edit -->
        <div wire:ignore.self class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Post</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="updatePost">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label>Judul Post</label>
                                        <input type="text"
                                            class="form-control @error('title') is-invalid @enderror"
                                            wire:model="title">
                                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label>Slug</label>
                                        <input type="text"
                                            class="form-control @error('slug') is-invalid @enderror"
                                            wire:model="slug">
                                        @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label>Konten</label>
                                        <div wire:ignore>
                                            <textarea id="ckeditor-edit" class="form-control">{{ $content }}</textarea>
                                        </div>
                                        @error('content') <div class="text-danger small">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card border-0 shadow-sm mb-3">
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label>Gambar Utama</label>
                                                <input type="file"
                                                    class="form-control @error('tempFeaturedImage') is-invalid @enderror"
                                                    wire:model="tempFeaturedImage">
                                                @error('tempFeaturedImage') <div class="invalid-feedback">{{ $message }}</div> @enderror

                                                @if($tempFeaturedImage)
                                                <img src="{{ $tempFeaturedImage->temporaryUrl() }}"
                                                    class="img-fluid mt-3 rounded">
                                                @elseif($featuredImage)
                                                <img src="{{ asset('storage/' . $featuredImage) }}"
                                                    class="img-fluid mt-3 rounded">
                                                @endif
                                            </div>

                                            <div class="mb-3">
                                                <label>Kategori</label>
                                                <select class="form-select @error('category_id') is-invalid @enderror"
                                                    wire:model="category_id">
                                                    <option value="0">Pilih Kategori</option>
                                                    @foreach($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label>Tag</label>
                                                <select class="form-select"
                                                    wire:model="tags"
                                                    multiple>
                                                    @foreach($allTags as $tag)
                                                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label>Tanggal Publikasi</label>
                                                <input type="date"
                                                    class="form-control @error('published_at') is-invalid @enderror"
                                                    wire:model="published_at">
                                                @error('published_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label>Status</label>
                                                <select class="form-select" wire:model="status">
                                                    <option value="draft">Draft</option>
                                                    <option value="published">Published</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label>Ringkasan (Excerpt)</label>
                                                <textarea class="form-control"
                                                    rows="3"
                                                    wire:model="excerpt"></textarea>
                                            </div>

                                            <div class="mb-3">
                                                <label>Meta Title</label>
                                                <input type="text"
                                                    class="form-control"
                                                    wire:model="meta_title">
                                            </div>

                                            <div class="mb-3">
                                                <label>Meta Description</label>
                                                <textarea class="form-control"
                                                    rows="3"
                                                    wire:model="meta_description"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
        <!-- End Modal Edit -->
        @push('styles')
        <style>
            .ck-editor__editable {
                min-height: 300px;
            }
        </style>
        @endpush
        @push('scripts')
        <script src="https://cdn.ckeditor.com/ckeditor5/41.2.0/classic/ckeditor.js"></script>
        <script>
            document.addEventListener('livewire:init', () => {
                let editors = {};

                // Initialize CKEditor for the "Tambah" modal
                function initCreateEditor() {
                    if (!editors['create']) {
                        ClassicEditor
                            .create(document.querySelector('#ckeditor-tambah'), {
                                ckfinder: {
                                    uploadUrl: "{{ route('image.upload') }}?_token={{ csrf_token() }}"
                                }
                            })
                            .then(editor => {
                                editors['create'] = editor;
                                editor.model.document.on('change:data', () => {
                                    @this.set('content', editor.getData());
                                });
                            })
                            .catch(error => {
                                console.error(error);
                            });
                    }
                }

                // Initialize CKEditor for the "Edit" modal
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
                                    @this.set('content', editor.getData());
                                });

                                // Set initial data from Livewire to CKEditor
                                editor.setData(@this.get('content'));
                            })
                            .catch(error => {
                                console.error(error);
                            });
                    } else {
                        // If editor already exists, update its data
                        editors['edit'].setData(@this.get('content'));
                    }
                }

                // Handle modal show
                $('#modalTambah').on('shown.bs.modal', () => {
                    initCreateEditor();
                    if (editors['create']) {
                        editors['create'].setData(@this.get('content'));
                    }
                });

                $('#modalEdit').on('shown.bs.modal', () => {
                    initEditEditor();
                });

                // Reset editors when modals are closed
                $('#modalTambah, #modalEdit').on('hidden.bs.modal', () => {
                    for (let key in editors) {
                        if (editors[key]) {
                            editors[key].destroy();
                            editors[key] = null;
                        }
                    }
                });

                // Update editor content when Livewire data changes
                Livewire.on('contentUpdated', (content) => {
                    if (editors['edit']) {
                        editors['edit'].setData(content);
                    }
                });
            });
        </script>
        @endpush