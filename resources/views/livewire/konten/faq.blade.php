<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;
    public string $question = '';
    public string $answer = '';
    public string $search = '';
    public int $perPage = 10;
    public ?int $faqId = null;
    
    public function with(): array
    {
        return [
            'faqs' => \App\Models\Faq::when($this->search, function ($query) {
                return $query->where('question', 'like', '%' . $this->search . '%')
                    ->orWhere('answer', 'like', '%' . $this->search . '%');
            })->orderBy('created_at', 'desc')->paginate($this->perPage),
        ];
    }
    
    private function resetInputFields(): void
    {
        $this->reset(['question', 'answer', 'faqId']);
    }
    
    public function store(): void
    {
        try {
            $this->validate([
                'question' => 'required|string|max:255',
                'answer' => 'required|string',
            ]);
            
            \App\Models\Faq::create([
                'question' => $this->question,
                'answer' => $this->answer,
            ]);
            
            $this->dispatch('tambahAlertToast');
            $this->resetInputFields();
        } catch (\Exception $e) {
            $this->dispatch('errorAlertToast');
        }
    }
    
    public function edit(int $id): void
    {
        $faq = \App\Models\Faq::findOrFail($id);
        $this->faqId = $faq->id;
        $this->question = $faq->question;
        $this->answer = $faq->answer;
    }
    
    public function updateFaq(): void
    {
        try {
            $this->validate([
                'question' => 'required|string|max:255',
                'answer' => 'required|string',
            ]);
            
            \App\Models\Faq::findOrFail($this->faqId)->update([
                'question' => $this->question,
                'answer' => $this->answer,
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
            \App\Models\Faq::findOrFail($id)->delete();
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
    <div class="col-md-12"></div>
        <div class="card card-round">
            <div class="card-header">
                <div class="card-head-row">
                    <div class="card-title">Frequently Asked Questions (FAQ)</div>
                    <div class="card-tools">
                        <a href="#" class="btn btn-info btn-sm me-2" data-bs-toggle="modal" data-bs-target="#modalTambah">
                            <span class="btn-label">
                                <i class="fa fa-plus"></i>
                            </span>
                            Tambah FAQ
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Cari FAQ..." wire:model.live.debounce.300ms="search">
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
                                <th>Question</th>
                                <th>Answer</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($faqs as $index => $faq)
                            <tr>
                                <td>{{ $faqs->firstItem() + $index }}</td>
                                <td>{{ $faq->question }}</td>
                                <td>{{ Str::limit($faq->answer, 100) }}</td>
                                <td>
                                    <a href="#" wire:click.prevent="edit({{ $faq->id }})" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="#" wire:click.prevent="delete({{ $faq->id }})" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus FAQ ini?')">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada FAQ ditemukan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="justify-content-between">
                        {{ $faqs->links() }}
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahLabel">Tambah FAQ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form wire:submit.prevent="store">
                            @csrf
                            <div class="mb-3">
                                <label for="question" class="form-label">Question</label>
                                <input type="text" class="form-control @error('question') is-invalid @enderror" id="question" wire:model.live="question">
                                @error('question') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="answer" class="form-label">Answer</label>
                                <textarea class="form-control @error('answer') is-invalid @enderror" id="answer" wire:model="answer" rows="4"></textarea>
                                @error('answer') <span class="text-danger">{{ $message }}</span> @enderror
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
                        <h5 class="modal-title" id="modalEditLabel">Edit FAQ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form wire:submit.prevent="updateFaq">
                            @csrf
                            <div class="mb-3">
                                <label for="question" class="form-label">Question</label>
                                <input type="text" class="form-control @error('question') is-invalid @enderror" id="question" wire:model.live="question">
                                @error('question') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="answer" class="form-label">Answer</label>
                                <textarea class="form-control @error('answer') is-invalid @enderror" id="answer" wire:model="answer" rows="4"></textarea>
                                @error('answer') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-primary">
                                    <span wire:loading.remove wire:target="updateFaq">Update</span>
                                    <span wire:loading wire:target="updateFaq">Memperbarui...</span>
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
