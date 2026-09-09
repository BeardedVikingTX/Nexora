```javascript
(() => {
    'use strict';


    /*
    |--------------------------------------------------------------------------
    | DOM Ready
    |--------------------------------------------------------------------------
    */

    const ready = (callback) => {

        if (document.readyState === 'loading') {

            document.addEventListener(
                'DOMContentLoaded',
                callback,
                { once: true }
            );

        } else {

            callback();

        }
    };


    /*
    |--------------------------------------------------------------------------
    | Utility
    |--------------------------------------------------------------------------
    */

    const setText = (id, value) => {

        const element = document.getElementById(id);

        if (element) {
            element.textContent = value;
        }
    };


    /*
    |--------------------------------------------------------------------------
    | Nexora Time
    |--------------------------------------------------------------------------
    */

    const getNexoraTime = () => {

        try {

            const now = new Date();

            const clockElement =
                document.getElementById('nexora-system-clock');

            const timezone =
                clockElement?.dataset?.timezone
                || 'America/Chicago';


            const parts = new Intl.DateTimeFormat(
                'en-US',
                {
                    timeZone: timezone,
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false
                }
            ).formatToParts(now);


            const map = Object.fromEntries(
                parts.map(part => [
                    part.type,
                    part.value
                ])
            );


            return {
                text:
                    `${map.hour}:${map.minute}:${map.second}`,

                iso:
                    now.toISOString()
            };


        } catch (error) {

            const fallback = new Date();

            return {
                text:
                    fallback.toLocaleTimeString(),

                iso:
                    fallback.toISOString()
            };
        }
    };


    /*
    |--------------------------------------------------------------------------
    | Clock Synchronization
    |--------------------------------------------------------------------------
    */

    const updateClocks = () => {

        const time = getNexoraTime();


        setText(
            'nexora-system-clock',
            time.text
        );


        setText(
            'nexora-mobile-clock',
            time.text
        );


        setText(
            'nexora-footer-time',
            time.text
        );


        const mobileClock =
            document.getElementById(
                'nexora-mobile-clock'
            );

        if (mobileClock) {

            mobileClock.dateTime =
                time.iso;
        }
    };


    /*
    |--------------------------------------------------------------------------
    | Connection State
    |--------------------------------------------------------------------------
    */

    const updateConnectionState = () => {

        const online =
            navigator.onLine;


        const state =
            document.getElementById(
                'nexora-client-state'
            );


        const connection =
            document.getElementById(
                'nexora-footer-connection'
            );


        if (state) {

            state.classList.toggle(
                'is-online',
                online
            );


            state.classList.toggle(
                'is-offline',
                !online
            );


            state.innerHTML =
                `<i aria-hidden="true"></i>` +
                `${online ? 'ONLINE' : 'OFFLINE'}`;
        }


        if (connection) {

            connection.textContent =
                online
                    ? 'ONLINE'
                    : 'OFFLINE';
        }
    };


    /*
    |--------------------------------------------------------------------------
    | Browser Engine
    |--------------------------------------------------------------------------
    */

    const detectBrowser = () => {

        const userAgent =
            navigator.userAgent;


        if (/Firefox/i.test(userAgent)) {
            return 'Firefox';
        }


        if (/Edg/i.test(userAgent)) {
            return 'Edge';
        }


        if (/Chrome/i.test(userAgent)) {
            return 'Chromium';
        }


        if (/Safari/i.test(userAgent)) {
            return 'Safari';
        }


        return 'Browser';
    };


    /*
    |--------------------------------------------------------------------------
    | Display Information
    |--------------------------------------------------------------------------
    */

    const updateDisplayMetrics = () => {

        setText(
            'nexora-footer-display',
            `${window.innerWidth} × ${window.innerHeight}`
        );
    };


    /*
    |--------------------------------------------------------------------------
    | Back To Top
    |--------------------------------------------------------------------------
    */

    const initializeBackToTop = () => {

        const button =
            document.getElementById(
                'nexora-back-to-top'
            );


        if (!button) {
            return;
        }


        const syncVisibility = () => {

            button.classList.toggle(
                'is-visible',
                window.scrollY > 600
            );
        };


        syncVisibility();


        window.addEventListener(
            'scroll',
            syncVisibility,
            {
                passive: true
            }
        );


        button.addEventListener(
            'click',
            () => {

                const reducedMotion =
                    window.matchMedia(
                        '(prefers-reduced-motion: reduce)'
                    ).matches;


                window.scrollTo({
                    top: 0,
                    behavior:
                        reducedMotion
                            ? 'auto'
                            : 'smooth'
                });
            }
        );
    };


    /*
    |--------------------------------------------------------------------------
    | Bootstrap Offcanvas Enhancement
    |--------------------------------------------------------------------------
    */

    const initializeOffcanvas = () => {

        document
            .querySelectorAll(
                '.nexora-offcanvas a[href]'
            )
            .forEach((link) => {

                link.addEventListener(
                    'click',
                    () => {

                        const offcanvas =
                            link.closest(
                                '.offcanvas'
                            );


                        if (
                            !offcanvas
                            ||
                            !window.bootstrap?.Offcanvas
                        ) {
                            return;
                        }


                        const instance =
                            window.bootstrap.Offcanvas
                                .getInstance(offcanvas);


                        if (instance) {
                            instance.hide();
                        }
                    }
                );
            });
    };


    /*
    |--------------------------------------------------------------------------
    | Message Badge Synchronization
    |--------------------------------------------------------------------------
    */

    const initializeMessageBadgeSync = () => {

        const desktopBadge =
            document.getElementById(
                'nexora-message-indicator'
            );


        const mobileBadge =
            document.getElementById(
                'nexora-mobile-message-indicator'
            );


        if (
            !desktopBadge
            ||
            !mobileBadge
        ) {
            return;
        }


        const sync = () => {

            mobileBadge.textContent =
                desktopBadge.textContent;


            mobileBadge.hidden =
                desktopBadge.hidden;
        };


        sync();


        const observer =
            new MutationObserver(sync);


        observer.observe(
            desktopBadge,
            {
                childList: true,
                subtree: true,
                attributes: true,
                attributeFilter: [
                    'hidden'
                ]
            }
        );
    };


    /*
    |--------------------------------------------------------------------------
    | Boot
    |--------------------------------------------------------------------------
    */

    ready(() => {


        /*
         * Clock
         */

        updateClocks();

        window.setInterval(
            updateClocks,
            1000
        );


        /*
         * Connection
         */

        updateConnectionState();


        window.addEventListener(
            'online',
            updateConnectionState,
            { passive: true }
        );


        window.addEventListener(
            'offline',
            updateConnectionState,
            { passive: true }
        );


        /*
         * Browser
         */

        setText(
            'nexora-footer-engine',
            detectBrowser()
        );


        /*
         * Display
         */

        updateDisplayMetrics();


        window.addEventListener(
            'resize',
            updateDisplayMetrics,
            { passive: true }
        );


        /*
         * Interaction
         */

        initializeBackToTop();
        initializeOffcanvas();
        initializeMessageBadgeSync();

    });

})();
```
