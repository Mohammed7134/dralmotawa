<x-layouts.app>
    <div class="max-w-2xl mx-auto text-center py-12">
        <p class="text-2xl text-gray-200 italic leading-relaxed mb-6">"{{ $wisdom->text }}"</p>
        <div class="flex justify-center gap-2 flex-wrap">
            @foreach ($wisdom->categories as $cat)
            <a href="/category/{{ $cat->category_url }}"
                class="bg-amber-400/10 text-amber-400 px-3 py-1 rounded-full text-sm hover:bg-amber-400/20 transition">
                {{ $cat->category_name }}
            </a>
            @endforeach
        </div>
        <div class="mt-8">
            <a href="/" class="text-gray-500 hover:text-amber-400 transition text-sm">← العودة إلى جميع الاقتباسات</a>
        </div>
    </div>
</x-layouts.app>