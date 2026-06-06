<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#e94560">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="direction" content="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
        <link rel="manifest" href="/manifest.json">
        <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap">

        <style>
            body {
                font-family: 'Cairo', sans-serif;
            }
        </style>
        {!! SEOTools::generate() !!}

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>

    <body class="bg-gray-950 text-gray-100 min-h-screen font-sans antialiased"
        dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

        <nav class="sticky top-0 z-50 bg-gray-900/80 backdrop-blur border-b border-gray-800">
            <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
                {{-- app name from env --}}
                <a href="/" class="text-xl font-bold text-amber-400 tracking-wide">✨ {{ config('app.name') }}</a>
                <div class="flex gap-4 items-center text-sm">
                    <a href="/" class="hover:text-amber-400 transition">الرئيسية</a>
                    <a href="/chat" class="hover:text-amber-400 transition">الدردشة مع الذكاء الاصطناعي</a>
                    <livewire:push-subscribe-button />
                </div>
            </div>
        </nav>

        <main class="max-w-6xl mx-auto px-4 py-8">
            {{ $slot }}
        </main>

        <footer class="border-t border-gray-800 mt-16 py-8 text-center text-gray-500 text-sm">
            <p>© {{ date('Y') }} {{ config('app.name') }} — نظريات لا تنتهي لعقلك الحديث</p>
        </footer>

        @livewireScripts
        <script>
            // Register service worker
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/service-worker.js')
                .then(reg => console.log('SW registered'))
                .catch(err => console.error('SW error', err));
        }
        </script>
        @stack('scripts')
        {{-- Toast notifications --}}
        <div x-data="toastManager()" x-on:show-toast.window="add($event.detail)"
            class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 flex flex-col gap-2 items-center pointer-events-none"
            dir="rtl">
            <template x-for="toast in toasts" :key="toast.id">
                <div x-show="toast.visible" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-4" :class="{
                'bg-green-500/90': toast.type === 'success',
                'bg-red-500/90':   toast.type === 'error',
                'bg-amber-400/90': toast.type === 'info',
            }" class="pointer-events-auto flex items-center gap-2.5 text-gray-900 text-sm font-medium px-5 py-3 rounded-full shadow-xl backdrop-blur-sm">
                    <span x-text="toast.icon" class="text-base"></span>
                    <span x-text="toast.message"></span>
                </div>
            </template>
        </div>

        <script>
            function toastManager() {
    return {
        toasts: [],
        add({ message, type = 'success', icon = '✓', duration = 3000 }) {
            const id = Date.now();
            this.toasts.push({ id, message, type, icon, visible: false });
            this.$nextTick(() => {
                const t = this.toasts.find(t => t.id === id);
                if (t) t.visible = true;
            });
            setTimeout(() => {
                const t = this.toasts.find(t => t.id === id);
                if (t) t.visible = false;
                setTimeout(() => {
                    this.toasts = this.toasts.filter(t => t.id !== id);
                }, 300);
            }, duration);
        }
    };
}
        </script>
    </body>

</html>