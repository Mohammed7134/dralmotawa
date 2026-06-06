<x-layouts.app>
    <div class="mb-8 text-center">
        <h1 class="text-4xl font-bold text-amber-400 mb-2">{{ config('app.name') }}</h1>
        <p class="text-gray-400 text-lg">استكشف {{ number_format(\App\Models\Wisdom::count()) }}+ اقتباسات من الحكمة</p>
    </div>

    <livewire:wisdom-feed />
</x-layouts.app>