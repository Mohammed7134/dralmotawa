<div>
    {{-- Search & Filter Bar --}}
    <div class="flex flex-wrap gap-3 mb-6">
        <input wire:model.live.debounce.400ms="search" type="search" placeholder="ابحث عن الحكمة..."
            class="flex-1 min-w-[200px] bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-amber-400">
        <select wire:model.live="categoryId"
            class="bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-amber-400">
            <option value="">جميع التصنيفات</option>
            @foreach ($categories as $cat)
            <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
            @endforeach
        </select>
        <select wire:model.live="sortBy"
            class="bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-amber-400">
            <option value="random">عشوائي</option>
            <option value="latest">الأحدث</option>
            <option value="popular">الأكثر إعجاباً</option>
        </select>
    </div>

    {{-- Wisdom Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse ($wisdoms as $wisdom)
        <livewire:wisdom-card :wisdom="$wisdom" :key="$wisdom->id" />
        @empty
        <div class="col-span-3 text-center py-16 text-gray-500">
            <p class="text-4xl mb-3">🔍</p>
            <p>لم يتم العثور على حكمة. جرب بحثاً مختلفاً.</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $wisdoms->links('vendor.pagination.tailwind') }}
    </div>
</div>