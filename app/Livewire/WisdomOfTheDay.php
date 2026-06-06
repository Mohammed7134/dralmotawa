<?php

namespace App\Livewire;

use App\Models\Wisdom;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class WisdomOfTheDay extends Component
{
    public Wisdom $wisdom;
    public bool $liked = false;

    public function mount(): void
    {
        // Changes every day — same wisdom for all visitors on the same day
        $this->wisdom = Cache::remember('wisdom_of_the_day_' . now()->toDateString(), now()->endOfDay(), function () {
            return Wisdom::inRandomOrder()->first();
        });
    }

    public function like(): void
    {
        if (!$this->liked) {
            $this->wisdom->incrementLike();
            $this->liked = true;
        }
    }

    public function render()
    {
        return view('livewire.wisdom-of-the-day');
    }
}
