'use strict';

const swReady = navigator.serviceWorker.ready;

document.addEventListener('DOMContentLoaded', function () {
    initSW();
});

function initSW() {
    if (!"serviceWorker" in navigator) {
        //service worker isn't supported
        return;
    }

    //don't use it here if you use service worker
    //for other stuff.
    if (!"PushManager" in window) {
        //push isn't supported
        return;
    }

    //register the service worker
    navigator.serviceWorker.register('../sw.js')
        .then(() => {
            console.log('serviceWorker installed!')
        })
        .catch((err) => {
            console.log(err)
        });
}
const notificationButton = document.getElementById('permission-btn');
notificationButton.addEventListener('click', function () {

    if (!swReady) {
        return;
    }

    new Promise(function (resolve, reject) {
        if (Notification) {
            console.dir(Notification);
            const permissionResult = Notification.requestPermission(function (result) {
                resolve(result);
            });
        } else {
            console.dir(Notification);
        }

        if (permissionResult) {
            permissionResult.then(resolve, reject);
        }
    })
        .then((permissionResult) => {
            if (permissionResult !== 'granted') {
                alert('نأسف٬ لا يمكن تفعيل الإشعارات في هذا المتصفح.');
            }
            subscribeUser();
        });
});

/**
 * Subscribe the user to push
 */
function subscribeUser() {
    swReady
        .then(async (registration) => {
            const subscribeOptions = {
                userVisibleOnly: true,
                applicationServerKey: urlBase64ToUint8Array(
                    'BE8g7b9p7drelPGpzcPUN4PrB4RhQV_OxLloKZZZ60yq7h8N8X1O-egI-XwBnTPXmpzwQVT9pY5refgF4YYZ5ic'
                )
            };
            if (registration.pushManager) {
                let subscription = await registration.pushManager.subscribe(subscribeOptions);
                return subscription;
            } else {
                return false;
            }
        })
        .then((pushSubscription) => {
            if (pushSubscription) {
                console.log('Received PushSubscription: ', JSON.stringify(pushSubscription));
                storePushSubscription(pushSubscription);
            } else {
                alert('نأسف٬ لا يمكن تفعيل الإشعارات في هذا المتصفح.');
            }
        });
}

/**
 * send PushSubscription to server with AJAX.
 * @param {object} pushSubscription
 */
function storePushSubscription(pushSubscription) {
    const token = document.querySelector('meta[name=csrf-token]').getAttribute('content');

    fetch('/push', {
        method: 'POST',
        body: JSON.stringify(pushSubscription),
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-Token': token
        }
    })
        .then((res) => {
            return res.json();
        })
        .then((res) => {
            console.log(res);
            const subscribeButton = document.getElementById('permission-btn');
            subscribeButton.style.display = "none";
        })
        .catch((err) => {
            console.log(err)
        });
}

/**
 * urlBase64ToUint8Array
 * 
 * @param {string} base64String a public vapid key
 */
function urlBase64ToUint8Array(base64String) {
    var padding = '='.repeat((4 - base64String.length % 4) % 4);
    var base64 = (base64String + padding)
        .replace(/\-/g, '+')
        .replace(/_/g, '/');

    var rawData = window.atob(base64);
    var outputArray = new Uint8Array(rawData.length);

    for (var i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}

/**
 * check if the user already subscribed to the notification in this pwa 
 */

if ('serviceWorker' in navigator && 'PushManager' in window) {
    navigator.serviceWorker.ready.then(function (serviceWorkerRegistration) {
        serviceWorkerRegistration.pushManager.getSubscription()
            .then(function (subscription) {
                if (subscription === null) {
                    // The user is not subscribed
                    // console.log('User is not subscribed');
                } else {
                    // The user is already subscribed
                    const subscribeButton = document.getElementById('permission-btn');
                    subscribeButton.style.display = "none";
                }
            })
            .catch(function (error) {
                // console.error('Error getting subscription', error);
            });
    });
} else {
    // console.log('either no service worker or no push manager');
}
