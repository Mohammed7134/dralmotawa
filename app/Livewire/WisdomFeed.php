<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Wisdom;
use Livewire\Component;
use Livewire\WithPagination;

class WisdomFeed extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $categoryId = null;
    public string $sortBy = 'random';

    protected $queryString = ['search', 'categoryId', 'sortBy'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCategoryId(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Wisdom::query()
            ->when($this->search, fn($q) => $q->search($this->search))
            ->when($this->categoryId, fn($q) => $q->whereHas('categories', fn($q) => $q->where('id', $this->categoryId)))
            ->when($this->sortBy === 'popular', fn($q) => $q->orderByDesc('likes'))
            ->when($this->sortBy === 'latest',  fn($q) => $q->latest())
            ->when($this->sortBy === 'random',  fn($q) => $q->inRandomOrder());

        return view('livewire.wisdom-feed', [
            'wisdoms'    => $query->paginate(50),
            'categories' => Category::orderBy('category_name')->get(),
        ]);
    }
}
