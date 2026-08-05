const flyoutViewportGap = 8;

// Mirrors the stylesheet's reveal condition for a rail flyout
const flyoutRevealSelector = ':hover, :has(:focus-visible)';

Alpine.data('SidebarLayout', (area) => {
    // Closures rather than component state: Alpine's reactive proxy cannot front a MediaQueryList
    let breakpoint = null;
    let onBreakpointChange = null;
    let groups = [];
    let openFlyouts = [];
    let toggleButton = null;

    return {
        // The server renders the same cookie into the wrapper's class
        collapsed: /(^|;\s*)sidebar_collapsed=1/.test(document.cookie),
        mobileOpen: false,

        get isMobile() {
            return breakpoint.matches;
        },

        // Below the breakpoint a persisted collapse is ignored and the sidebar is a drawer
        get isRail() {
            return this.collapsed && !this.isMobile;
        },

        // In the rail a group is not a disclosure -- the flyout opens on hover -- so the
        // attribute is dropped rather than left saying something untrue.
        syncAria() {
            for (const { el, link } of groups) {
                if (this.isRail) {
                    link.removeAttribute('aria-expanded');
                } else {
                    link.setAttribute('aria-expanded', String(el.classList.contains('is-open')));
                }
            }

            toggleButton.setAttribute('aria-expanded', String(this.isMobile ? this.mobileOpen : !this.collapsed));
        },

        toggle() {
            if (this.isMobile) {
                this.mobileOpen = !this.mobileOpen;

                return;
            }

            this.suppressSubmenuTransitions();

            this.collapsed = !this.collapsed;
        },

        closeMobile() {
            this.mobileOpen = false;
        },

        // The submenus change mode, not appearance, when the rail comes and goes, and CSS
        // cannot say "this one change does not animate".
        suppressSubmenuTransitions() {
            this.$root.classList.add('is-switching');

            this.$nextTick(() => {
                // Settles the new mode while transitions are still off
                void this.$root.offsetWidth;

                this.$root.classList.remove('is-switching');
            });
        },

        // Remembered rather than found by `:hover` on demand, because Chrome does not
        // re-evaluate hover while the rail scrolls under a still pointer. `target` is what
        // the event landed on, matched by ancestry because Firefox has not applied `:hover`
        // or `:focus-visible` to it yet at that point; the selector covers the mode changes
        // that reveal a panel with no event at all.
        trackFlyouts(target = null) {
            openFlyouts = this.isRail
                ? groups
                      .filter((group) => group.el.contains(target) || group.el.matches(flyoutRevealSelector))
                      .map((group) => ({ el: group.el, height: group.submenu.offsetHeight }))
                : [];

            this.positionFlyouts();
        },

        // A flyout escapes the scrolling nav, so its block offset is the one thing the
        // stylesheet cannot work out. A first-level panel takes the clamped coordinate, a
        // deeper one the shift away from its group; each level's rule reads only its own.
        positionFlyouts() {
            for (const { el, height } of openFlyouts) {
                const top = el.getBoundingClientRect().top;
                const fitted = Math.max(flyoutViewportGap, Math.min(top, window.innerHeight - height - flyoutViewportGap));

                el.style.setProperty('--sidebar-flyout-top', `${fitted}px`);
                el.style.setProperty('--sidebar-flyout-shift', `${fitted - top}px`);
            }
        },

        init() {
            // Read from the stylesheet, so the two can never disagree about the mode
            const breakpointMax = getComputedStyle(this.$root).getPropertyValue('--sidebar-breakpoint-max').trim();

            breakpoint = window.matchMedia(`(max-width: ${breakpointMax})`);
            toggleButton = this.$root.querySelector('.app-topbar-toggle');

            groups = [...this.$root.querySelectorAll('.app-sidebar-group')].map((el) => ({
                el,
                link: el.querySelector(':scope > .app-sidebar-link'),
                submenu: el.querySelector(':scope > .app-sidebar-submenu'),
            }));

            for (const group of groups) {
                group.link.addEventListener('click', () => {
                    // Toggling a hover flyout would only leave stale open state
                    if (this.isRail) return;

                    group.el.classList.toggle('is-open');

                    this.syncAria();
                });

                group.el.addEventListener('pointerenter', (event) => this.trackFlyouts(event.target));
                group.el.addEventListener('focusin', (event) => this.trackFlyouts(event.target));
            }

            // Scroll fires more than once per frame, and each run is a rect read plus a write
            let scrollPending = false;

            this.$root.querySelector('.app-sidebar-nav').addEventListener(
                'scroll',
                () => {
                    if (scrollPending) return;

                    scrollPending = true;

                    window.requestAnimationFrame(() => {
                        scrollPending = false;

                        this.positionFlyouts();
                    });
                },
                { passive: true }
            );

            // A mode change can reveal a flyout with no pointer or focus event to hang the
            // measurement off, so both modes are re-read rather than waiting for one.
            onBreakpointChange = () => {
                this.syncAria();
                this.trackFlyouts();

                // The drawer only exists below the breakpoint, and holds the body scroll lock
                if (!this.isMobile) {
                    this.closeMobile();
                }
            };

            breakpoint.addEventListener('change', onBreakpointChange);

            this.$watch('collapsed', (value) => {
                // Scoped to this area's URL prefix, so admin and user keep their own state
                document.cookie = `sidebar_collapsed=${value ? 1 : 0}; path=/${area}; max-age=31536000; samesite=lax`;

                this.syncAria();
                this.trackFlyouts();
            });

            this.$watch('mobileOpen', () => this.syncAria());

            this.syncAria();
        },

        destroy() {
            // The only listener not on markup Bolt is about to replace
            breakpoint.removeEventListener('change', onBreakpointChange);
        },
    };
});
