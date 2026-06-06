<div class="max-w-2xl mx-auto" x-data>
    <div class="mb-6 text-center">
        <h1 class="text-3xl font-bold text-amber-400">✨ الذكاء الاصطناعي للحكمة</h1>
        <p class="text-gray-400 mt-1">اسأل أي شيء — الفلسفة, الحياة, المعنى, الغرض.</p>
    </div>

    {{-- Chat window --}}
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 mb-4 h-96 overflow-y-auto flex flex-col gap-4"
        id="chat-window">
        @if (empty($conversation))
        <div class="text-center text-gray-500 my-auto">
            <p class="text-4xl mb-2">🌿</p>
            <p>ابدأ محادثة مع الذكاء الاصطناعي للحكمة...</p>
        </div>
        @endif

        @foreach ($conversation as $msg)
        <div class="flex {{ $msg['role'] === 'user' ? 'justify-end' : 'justify-start' }}">
            <div class="max-w-[80%] rounded-xl px-4 py-3 text-sm leading-relaxed
                    {{ $msg['role'] === 'user'
                        ? 'bg-amber-400 text-gray-900 font-medium'
                        : 'bg-gray-800 text-gray-200 border border-gray-700' }}">
                {{ $msg['text'] }}
            </div>
        </div>
        @endforeach

        @if ($loading)
        <div class="flex justify-start">
            <div class="bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 text-sm text-gray-400 animate-pulse">
                Thinking...
            </div>
        </div>
        @endif
    </div>

    {{-- Input --}}
    <form wire:submit="sendMessage" class="flex gap-2">
        <input wire:model="message" type="text" placeholder="اكتب رسالتك هنا..." required
            class="flex-1 bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-amber-400"
            :disabled="$wire.loading">
        <button type="submit"
            class="bg-amber-400 text-gray-900 font-semibold px-6 py-3 rounded-lg hover:bg-amber-300 transition disabled:opacity-50"
            wire:loading.attr="disabled">
            <span wire:loading.remove>إرسال</span>
            <span wire:loading>...</span>
        </button>
    </form>

    <div class="text-left mt-2">
        <button wire:click="clearChat" class="text-xs text-gray-600 hover:text-gray-400 transition">مسح الدردشة</button>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:updated', () => {
        const el = document.getElementById('chat-window');
        if (el) el.scrollTop = el.scrollHeight;
    });
</script>
@endpush