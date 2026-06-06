<div class="mb-10" dir="rtl">
    <div class="relative rounded-3xl overflow-hidden">

        {{-- Background gradients --}}
        <div class="absolute inset-0 bg-gradient-to-br from-amber-900/80 via-[#1a1020] to-[#0d0d1a]"></div>
        <div class="absolute inset-0"
            style="background: radial-gradient(ellipse at top right, rgba(251,191,36,0.15), transparent 60%)"></div>

        {{-- Geometric circle accents --}}
        <div
            class="absolute top-0 right-0 w-64 h-64 border border-amber-400/10 rounded-full -translate-y-1/2 translate-x-1/2">
        </div>
        <div
            class="absolute top-0 right-0 w-96 h-96 border border-amber-400/5 rounded-full -translate-y-1/3 translate-x-1/3">
        </div>

        <div class="relative p-8 md:p-10">

            {{-- Top row --}}
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-2.5">
                    <div
                        class="w-8 h-8 rounded-full bg-amber-400/20 border border-amber-400/30 flex items-center justify-center">
                        <span class="text-amber-400 text-xs">✦</span>
                    </div>
                    <span class="text-amber-400 text-xs font-bold tracking-[0.2em] uppercase">حكمة اليوم</span>
                </div>
                <span class="text-gray-600 text-xs">{{ now()->translatedFormat('j F Y') }}</span>
            </div>

            {{-- Wisdom text --}}
            <blockquote class="text-2xl md:text-3xl text-white font-light leading-relaxed mb-8 text-right"
                style="font-family: Georgia, serif; text-shadow: 0 2px 20px rgba(251,191,36,0.1)">
                {{ $wisdom->text }}
            </blockquote>

            {{-- Bottom row --}}
            <div class="flex items-center justify-between flex-wrap gap-4">

                {{-- Category tags --}}
                <div class="flex gap-2 flex-wrap">
                    @foreach ($wisdom->categories as $cat)
                    <a href="/category/{{ $cat->category_url }}"
                        class="bg-white/5 backdrop-blur-sm text-white/60 text-xs px-3 py-1.5 rounded-full border border-white/10 hover:border-amber-400/30 hover:text-white/80 transition">
                        {{ $cat->category_name }}
                    </a>
                    @endforeach
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-4">
                    <button wire:click="like"
                        class="flex items-center gap-1.5 transition-colors text-sm {{ $liked ? 'text-red-400' : 'text-white/40 hover:text-red-400' }}">
                        <svg class="w-4 h-4" fill="{{ $liked ? 'currentColor' : 'none' }}" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        <span>{{ $wisdom->likes }}</span>
                    </button>

                    <button
                        onclick="shareWisdom('{{ addslashes($wisdom->text) }}', '{{ url('/wisdom/' . $wisdom->id) }}')"
                        class="bg-amber-400 text-gray-900 text-xs font-bold px-5 py-2 rounded-full hover:bg-amber-300 transition-colors">
                        مشاركة ↗
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
    function shareWisdom(text, url) {
    if (navigator.share) {
        navigator.share({ title: 'حكمة اليوم', text: text, url: url });
    } else {
        navigator.clipboard.writeText(text + '\n' + url).then(() => {
            alert('تم نسخ الحكمة والرابط');
        });
    }
}
</script>
@endpush