self.addEventListener('install', event => {
    console.log('Service Worker instalado');
});

self.addEventListener('push', event => {
    const data = event.data ? event.data.json() : {};
    const options = {
        body: data.body || 'Nueva notificación',
        icon: '/imagenes/rayito.png',
        badge: '/imagenes/rayito.png',
        vibrate: [200, 100, 200],
        data: { url: data.url || '/' }
    };
    event.waitUntil(
        self.registration.showNotification(data.title || 'EcoVolt', options)
    );
});

self.addEventListener('notificationclick', event => {
    event.notification.close();
    event.waitUntil(
        clients.openWindow(event.notification.data.url)
    );
});
