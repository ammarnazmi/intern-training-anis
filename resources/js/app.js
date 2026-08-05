import './bootstrap';

// App
Object.defineProperty(window, 'App', {
    configurable: false,
    writable: false,
    value: (() => {
        let locale = null;

        return {
            get locale() {
                return locale;
            },

            set locale(value) {
                locale = value;
                Lang.setLocale(locale);
                Lang.setMessages(App.lang);
                Validator.setLocale(locale);
                Validator.setMessages(locale, App.lang[locale].short.validation);
            },

            configure(options) {
                Object.assign(this, options);
            },
        };
    })(),
});

// Init code
Bolt.once('unload', (event) => {
    document.head.querySelectorAll('style').forEach((style) => {
        if (!style.hasAttribute('bolt-pin') && style.innerHTML.includes('url(/cf-fonts/')) {
            style.removeAttribute('bolt-transient');
            style.setAttribute('bolt-pin', '');
        }
    });
});

Bolt.on('unload', (event) => {
    document.querySelectorAll('.modal').forEach((element) => bootstrap.Modal.getInstance(element)?.hide());

    ['tooltip', 'popover'].forEach((type) =>
        document
            .querySelectorAll(`[aria-describedby^="${type}"]`)
            .forEach((element) => bootstrap[type.charAt(0).toUpperCase() + type.slice(1)].getInstance(element)?.hide())
    );
});

Bolt.once('load', (event) => {
    if ('serviceWorker' in navigator) {
        if (App.serviceWorker.enabled) {
            navigator.serviceWorker.register(App.serviceWorker.url, { scope: '/' }).then(
                (registration) => registration.active?.postMessage('ACTIVATE'),
                () => {}
            );
        }

        if (App.serviceWorker.unregister) {
            navigator.serviceWorker.getRegistrations().then((registrations) => registrations.forEach((registration) => registration.unregister()));
        }
    }
});

Bolt.on('load', (event) => {
    if (App.notification) {
        Helper.showNotification(...Object.values(App.notification));
    }
});

Bolt.setTargetSelectors('#app-container');
Bolt.start();
