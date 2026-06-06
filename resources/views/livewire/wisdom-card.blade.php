{{--
Wisdom Card — two modes:
• Image mode: AI image fills the card, wisdom text overlaid with dark gradient
• Text mode: standard dark card when no image is loaded

Also contains the 9:16 Story modal (Alpine.js, no Livewire round-trip).
--}}
@php $author = 'د. عبدالعزيز فيصل المطوع'; @endphp

<div class="rounded-xl overflow-hidden border transition group flex flex-col
            {{ $showImage && $imageUrl
                ? 'border-amber-400/30 min-h-72'
                : 'bg-gray-900 border-gray-800 hover:border-amber-400/20' }}" dir="rtl" x-data="{ storyOpen: false }"
    @keydown.escape.window="storyOpen = false">

    @if ($showImage && $imageUrl)
    {{-- ─── IMAGE QUOTE CARD ──────────────────────────────────────────── --}}
    <div id="wisdom-poster-{{ $wisdom->id }}" class="relative flex-1 min-h-72">

        <img src="{{ $imageUrl }}" alt="AI art" crossorigin="anonymous"
            class="absolute inset-0 w-full h-full object-cover" loading="lazy">

        <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/20 to-black/85 pointer-events-none"></div>

        {{-- Wisdom text + author --}}
        <div class="relative flex flex-col items-center justify-center min-h-72 px-8 py-12 text-center">
            <span class="text-amber-400/50 font-serif select-none pointer-events-none leading-none"
                style="font-size:72px;line-height:0.8">«</span>
            <p class="mt-3 text-white text-xl md:text-2xl font-light leading-relaxed"
                style="font-family:Georgia,serif;text-shadow:0 2px 16px rgba(0,0,0,0.9)">
                {{ $wisdom->text }}
            </p>
            {{-- Author attribution --}}
            <p class="mt-3 text-amber-400/70 text-sm font-medium" style="text-shadow:0 1px 8px rgba(0,0,0,0.9)">
                — {{ $author }}
            </p>
            @if ($translation)
            <p class="mt-3 text-amber-200/70 text-sm italic leading-relaxed" dir="ltr"
                style="text-shadow:0 1px 8px rgba(0,0,0,0.95)">"{{ $translation }}"</p>
            @endif
            <p class="mt-5 text-white/20 text-xs tracking-widest uppercase select-none">حكمة اليوم</p>
        </div>

        {{-- UI chrome — hidden during card download --}}
        <button data-no-capture wire:click="generateImage"
            class="absolute top-3 left-3 bg-black/50 hover:bg-black/70 text-white/60 hover:text-white rounded-full p-1.5 transition backdrop-blur-sm">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        @if ($wisdom->categories->isNotEmpty())
        <div data-no-capture class="absolute top-3 right-3 flex gap-1.5 flex-wrap justify-end">
            @foreach ($wisdom->categories as $cat)
            <a href="/category/{{ $cat->category_url }}"
                class="bg-black/50 text-amber-300 px-2 py-0.5 rounded text-xs backdrop-blur-sm hover:bg-black/70 transition">
                {{ $cat->category_name }}
            </a>
            @endforeach
        </div>
        @endif

        {{-- Bottom action bar --}}
        <div data-no-capture class="absolute bottom-0 inset-x-0 px-4 py-3 flex items-center justify-between
                         bg-gradient-to-t from-black/70 to-transparent">
            <button wire:click="like" class="flex items-center gap-1.5 text-sm transition
                               {{ $liked ? 'text-red-400' : 'text-white/60 hover:text-red-400' }}">
                <svg class="w-4 h-4" fill="{{ $liked ? 'currentColor' : 'none' }}" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                <span>{{ $wisdom->likes }}</span>
            </button>
            <div class="flex items-center gap-1">
                <button onclick="downloadWisdomCard({{ $wisdom->id }})" title="تحميل البطاقة"
                    class="p-2 rounded-lg text-white/50 hover:text-emerald-400 hover:bg-emerald-400/10 transition backdrop-blur-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                </button>
                <button @click="storyOpen = true" title="مشاركة كستوري 9:16"
                    class="p-2 rounded-lg text-white/50 hover:text-pink-400 hover:bg-pink-400/10 transition backdrop-blur-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 8.25h3m-3 3h3m-3 3h3" />
                    </svg>
                </button>
                @include('livewire.partials.wisdom-actions', [
                'overlay' => true,
                'author' => $author,
                ])
            </div>
        </div>
    </div>

    @else
    {{-- ─── TEXT CARD (no image) ──────────────────────────────────────── --}}
    <div class="p-5 flex flex-col gap-4 flex-1">
        <p class="text-gray-200 text-base leading-relaxed flex-1 italic" style="font-family:Georgia,serif">«{{
            $wisdom->text }}»</p>

        {{-- Author --}}
        <p class="text-amber-400/60 text-xs font-medium -mt-1">— {{ $author }}</p>

        @if ($translation)
        <div class="bg-amber-400/5 border border-amber-400/20 rounded-lg px-4 py-3 text-gray-400 text-sm" dir="ltr">
            🌐 {{ $translation }}
        </div>
        @endif

        @if ($wisdom->categories->isNotEmpty())
        <div class="flex gap-1.5 flex-wrap">
            @foreach ($wisdom->categories as $cat)
            <a href="/category/{{ $cat->category_url }}"
                class="bg-amber-400/10 text-amber-400 px-2 py-0.5 rounded text-xs hover:bg-amber-400/20 transition">
                {{ $cat->category_name }}
            </a>
            @endforeach
        </div>
        @endif

        <div class="flex items-center justify-between pt-1 border-t border-gray-800">
            <button wire:click="like" class="flex items-center gap-1.5 text-sm transition
                               {{ $liked ? 'text-red-400' : 'text-gray-500 hover:text-red-400' }}">
                <svg class="w-4 h-4" fill="{{ $liked ? 'currentColor' : 'none' }}" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                <span>{{ $wisdom->likes }}</span>
            </button>
            <div class="flex items-center gap-1">
                @include('livewire.partials.wisdom-actions', [
                'overlay' => false,
                'author' => $author,
                ])
            </div>
        </div>
    </div>
    @endif

    {{-- ─── 9:16 STORY MODAL ──────────────────────────────────────────── --}}
    @if ($showImage && $imageUrl)
    <div x-show="storyOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 backdrop-blur-sm p-4"
        style="display:none" @click.self="storyOpen = false">

        <div class="flex flex-col items-center gap-4 w-full max-w-sm">

            {{-- 9:16 Story poster --}}
            <div id="wisdom-story-{{ $wisdom->id }}" class="relative w-72 rounded-2xl overflow-hidden shadow-2xl"
                style="aspect-ratio:9/16">

                <img src="{{ $imageUrl }}" alt="Story background" crossorigin="anonymous"
                    class="absolute inset-0 w-full h-full object-cover">

                <div class="absolute inset-0"
                    style="background:linear-gradient(180deg,rgba(0,0,0,0.55) 0%,rgba(0,0,0,0.1) 35%,rgba(0,0,0,0.1) 55%,rgba(0,0,0,0.8) 100%)">
                </div>

                {{-- Top branding --}}
                <div class="absolute top-6 inset-x-0 flex flex-col items-center">
                    <span class="text-amber-400/60 text-xs tracking-[0.3em] uppercase font-medium">حكمة اليوم</span>
                    <div class="mt-2 w-8 h-px bg-amber-400/40"></div>
                </div>

                {{-- Center: wisdom + author --}}
                <div class="absolute inset-0 flex flex-col items-center justify-center px-8 text-center">
                    <span class="text-amber-400/40 font-serif leading-none select-none"
                        style="font-size:90px;line-height:0.7">«</span>
                    <p class="mt-2 text-white font-light leading-relaxed text-lg"
                        style="font-family:Georgia,serif;text-shadow:0 2px 20px rgba(0,0,0,0.95)">
                        {{ $wisdom->text }}
                    </p>
                    {{-- Author --}}
                    <p class="mt-4 text-amber-400/80 text-sm font-medium"
                        style="text-shadow:0 1px 10px rgba(0,0,0,0.9)">
                        — {{ $author }}
                    </p>
                    @if ($translation)
                    <div class="mt-4 px-4 py-2 rounded-lg w-full"
                        style="background:rgba(0,0,0,0.45);backdrop-filter:blur(4px)">
                        <p class="text-amber-200/70 text-xs italic leading-relaxed" dir="ltr">
                            "{{ $translation }}"
                        </p>
                    </div>
                    @endif
                </div>

                {{-- Bottom footer --}}
                <div class="absolute bottom-8 inset-x-0 flex flex-col items-center gap-2">
                    <div class="w-8 h-px bg-amber-400/40"></div>
                    <div class="flex gap-1">
                        @foreach ($wisdom->categories->take(2) as $cat)
                        <span class="text-amber-300/60 text-xs">{{ $cat->category_name }}</span>
                        @if (!$loop->last)<span class="text-amber-400/30 text-xs">·</span>@endif
                        @endforeach
                    </div>
                    <p class="text-white/20 text-[10px] tracking-widest mt-1">{{ config('app.name') }}</p>
                </div>
            </div>

            {{-- Controls below poster --}}
            <div class="flex items-center gap-3">
                <button onclick="downloadWisdomStory({{ $wisdom->id }})"
                    class="flex items-center gap-2 bg-emerald-500 hover:bg-emerald-400 text-black font-semibold text-sm px-5 py-2.5 rounded-full transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    تحميل الصورة
                </button>
                <button @click="storyOpen = false"
                    class="p-2.5 rounded-full bg-white/10 hover:bg-white/20 text-white/70 hover:text-white transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <p class="text-white/30 text-xs">اضغط خارج الصورة للإغلاق · Esc</p>
        </div>
    </div>
    @endif

</div>{{-- end card root --}}

@once
@push('scripts')
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js" defer></script>
<script>
    async function downloadWisdomCard(wisdomId) {
    const el = document.getElementById('wisdom-poster-' + wisdomId);
    if (!el) return;
    const hidden = el.querySelectorAll('[data-no-capture]');
    hidden.forEach(e => e.style.visibility = 'hidden');
    try {
        const canvas = await html2canvas(el, {
            useCORS: true, allowTaint: false, scale: 2,
            backgroundColor: '#000', logging: false, imageTimeout: 15000,
        });
        _triggerDownload(canvas, 'hikma-' + wisdomId + '.png');
        _toast('تم حفظ البطاقة بنجاح', 'success', '💾');
    } catch { _toast('تعذّر حفظ البطاقة', 'error', '✕'); }
    finally { hidden.forEach(e => e.style.visibility = ''); }
}

async function downloadWisdomStory(wisdomId) {
    const el = document.getElementById('wisdom-story-' + wisdomId);
    if (!el) return;
    try {
        const canvas = await html2canvas(el, {
            useCORS: true, allowTaint: false, scale: 3,
            backgroundColor: '#000', logging: false, imageTimeout: 15000,
        });
        _triggerDownload(canvas, 'hikma-story-' + wisdomId + '.png');
        _toast('تم حفظ الستوري بنجاح', 'success', '📱');
    } catch { _toast('تعذّر حفظ الستوري', 'error', '✕'); }
}

function _triggerDownload(canvas, filename) {
    const a = document.createElement('a');
    a.download = filename;
    a.href = canvas.toDataURL('image/png');
    a.click();
}
function _toast(message, type, icon) {
    window.dispatchEvent(new CustomEvent('show-toast', { detail: { message, type, icon } }));
}
function copyWisdomLink(url, text, author) {
    const content = '«' + text + '»\n— ' + author + '\n' + url;
    navigator.clipboard.writeText(content)
        .then(() => _toast('تم نسخ الحكمة والرابط', 'success', '📋'))
        .catch(() => _toast('تعذّر النسخ، حاول مرة أخرى', 'error', '✕'));
}
</script>
@endpush
@endonce