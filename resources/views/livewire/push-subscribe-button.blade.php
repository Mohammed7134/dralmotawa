<div x-data="pushSubscription(@js(config('webpush.vapid.public_key')))" x-init="init()">
    <button @click="subscribed ? unsubscribe() : subscribe()" class="text-xs px-3 py-1.5 rounded-lg border transition"
        :class="subscribed
            ? 'border-green-500 text-green-400 hover:border-red-500 hover:text-red-400'
            : 'border-amber-400 text-amber-400 hover:bg-amber-400/10'">
        <span x-text="subscribed ? '🔔 تم الاشتراك' : '🔕 الاشتراك'"></span>
    </button>
</div>

@push('scripts')
<script>
    function pushSubscription(vapidKey) {
    return {
        subscribed: false,
        registration: null,
        async init() {
            if (!('serviceWorker' in navigator) || !('PushManager' in window)) return;
            this.registration = await navigator.serviceWorker.ready;
            const sub = await this.registration.pushManager.getSubscription();
            this.subscribed = !!sub;
        },
        async subscribe() {
            const permission = await Notification.requestPermission();
            if (permission !== 'granted') return alert('Please allow notifications.');

            const sub = await this.registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: this.urlBase64ToUint8Array(vapidKey),
            });

            const key = sub.getKey('p256dh');
            const token = sub.getKey('auth');

            await fetch('/push/subscribe', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                body: JSON.stringify({
                    endpoint:  sub.endpoint,
                    publicKey: key ? btoa(String.fromCharCode(...new Uint8Array(key))) : null,
                    authToken: token ? btoa(String.fromCharCode(...new Uint8Array(token))) : null,
                    encoding:  'aesgcm',
                }),
            });

            this.subscribed = true;
        },
        async unsubscribe() {
            const sub = await this.registration.pushManager.getSubscription();
            if (!sub) return;
            await fetch('/push/unsubscribe', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                body: JSON.stringify({ endpoint: sub.endpoint }),
            });
            await sub.unsubscribe();
            this.subscribed = false;
        },
        urlBase64ToUint8Array(base64String) {
            const padding = '='.repeat((4 - base64String.length % 4) % 4);
            const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
            const rawData = atob(base64);
            return Uint8Array.from([...rawData].map(c => c.charCodeAt(0)));
        },
    };
}
</script>
@endpush