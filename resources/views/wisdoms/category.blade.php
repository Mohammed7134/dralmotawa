<x-layouts.app>
    <div class="mb-8 text-center">
        <h1 class="text-4xl font-bold text-amber-400 mb-2">{{ $category->category_name }}</h1>
        <p class="text-gray-400">{{ $category->wisdoms()->count() }} اقتباسات من الحكمة</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach ($category->wisdoms()->latest()->paginate(50) as $wisdom)
        <livewire:wisdom-card :wisdom="$wisdom" :key="$wisdom->id" />
        @endforeach
    </div>
</x-layouts.app>