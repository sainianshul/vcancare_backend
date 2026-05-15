/**
 * VCanCares Admin Sidebar — Custom Lightweight JS
 * Replaces Metronic KTMenu, KTDrawer, KTScroll plugins
 * Zero dependencies, ~3KB, runs in <1ms
 */
(function () {
    'use strict';

    // Cache DOM refs once
    var sidebar = document.getElementById('kt_app_sidebar');
    var mobileToggle = document.getElementById('kt_app_sidebar_mobile_toggle');
    var overlay = null;
    var MOBILE_BP = 992; // lg breakpoint

    // =========================================
    // 1. ACCORDION MENUS (replaces data-kt-menu)
    // =========================================
    function initAccordions() {
        if (!sidebar) return;

        // All accordion triggers
        var accordions = sidebar.querySelectorAll('.menu-accordion > .menu-link');
        for (var i = 0; i < accordions.length; i++) {
            accordions[i].addEventListener('click', handleAccordionClick);
        }
    }

    function handleAccordionClick(e) {
        e.preventDefault();
        e.stopPropagation();

        var item = this.parentElement; // .menu-item.menu-accordion
        var sub = item.querySelector('.menu-sub');
        if (!sub) return;

        var isOpen = item.classList.contains('show');

        if (isOpen) {
            // Close this accordion
            closeAccordion(item, sub);
        } else {
            // Close siblings first (single-expand behavior)
            var siblings = item.parentElement.children;
            for (var i = 0; i < siblings.length; i++) {
                if (siblings[i] !== item && siblings[i].classList.contains('menu-accordion') && siblings[i].classList.contains('show')) {
                    var sibSub = siblings[i].querySelector('.menu-sub');
                    if (sibSub) closeAccordion(siblings[i], sibSub);
                }
            }
            // Open this accordion
            openAccordion(item, sub);
        }
    }

    function openAccordion(item, sub) {
        item.classList.add('here', 'show');
        sub.style.display = 'flex';
        sub.style.overflow = 'hidden';
        var h = sub.scrollHeight;
        sub.style.height = '0px';
        // Force reflow
        sub.offsetHeight;
        sub.style.transition = 'height 0.2s ease';
        sub.style.height = h + 'px';
        // Clean up after transition
        var done = function () {
            sub.style.height = '';
            sub.style.overflow = '';
            sub.style.transition = '';
            sub.removeEventListener('transitionend', done);
        };
        sub.addEventListener('transitionend', done);
    }

    function closeAccordion(item, sub) {
        sub.style.overflow = 'hidden';
        sub.style.height = sub.scrollHeight + 'px';
        sub.offsetHeight;
        sub.style.transition = 'height 0.2s ease';
        sub.style.height = '0px';
        var done = function () {
            item.classList.remove('here', 'show');
            sub.style.display = '';
            sub.style.height = '';
            sub.style.overflow = '';
            sub.style.transition = '';
            sub.removeEventListener('transitionend', done);
        };
        sub.addEventListener('transitionend', done);
    }

    // =========================================
    // 2. MOBILE DRAWER (replaces data-kt-drawer)
    // =========================================
    function createOverlay() {
        if (overlay) return overlay;
        overlay = document.createElement('div');
        overlay.id = 'sidebar-overlay';
        overlay.style.cssText = 'position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.35);z-index:104;opacity:0;transition:opacity 0.25s ease;display:none;';
        document.body.appendChild(overlay);
        overlay.addEventListener('click', closeMobileDrawer);
        return overlay;
    }

    function openMobileDrawer() {
        if (!sidebar) return;
        createOverlay();
        sidebar.classList.add('drawer-on');
        sidebar.style.cssText = 'display:flex !important; position:fixed; top:0; left:0; bottom:0; z-index:105; width:250px; transform:translateX(0); transition:transform 0.25s ease;';
        overlay.style.display = 'block';
        requestAnimationFrame(function () {
            overlay.style.opacity = '1';
        });
        document.body.style.overflow = 'hidden';
    }

    function closeMobileDrawer() {
        if (!sidebar) return;
        sidebar.style.transform = 'translateX(-100%)';
        if (overlay) {
            overlay.style.opacity = '0';
        }
        setTimeout(function () {
            sidebar.classList.remove('drawer-on');
            sidebar.style.cssText = '';
            if (overlay) overlay.style.display = 'none';
            document.body.style.overflow = '';
        }, 250);
    }

    function initMobileDrawer() {
        if (!mobileToggle || !sidebar) return;
        mobileToggle.addEventListener('click', function (e) {
            e.preventDefault();
            if (sidebar.classList.contains('drawer-on')) {
                closeMobileDrawer();
            } else {
                openMobileDrawer();
            }
        });

        // Close drawer on window resize to desktop
        window.addEventListener('resize', function () {
            if (window.innerWidth >= MOBILE_BP && sidebar.classList.contains('drawer-on')) {
                closeMobileDrawer();
            }
        });
    }

    // =========================================
    // 3. USER DROPDOWN (replaces data-kt-menu on footer)
    // =========================================
    function initUserDropdown() {
        var footer = document.getElementById('kt_app_sidebar_footer');
        if (!footer) return;

        var trigger = footer.querySelector('[data-kt-menu-trigger]');
        var dropdown = footer.querySelector('.menu-sub-dropdown');
        if (!trigger || !dropdown) return;

        // Position dropdown above the trigger
        trigger.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            var isOpen = dropdown.classList.contains('show');

            // Close any other dropdowns first
            closeAllDropdowns();

            if (!isOpen) {
                var rect = trigger.getBoundingClientRect();
                dropdown.style.cssText = 'display:block; position:fixed; z-index:107; animation:fadeIn 0.15s ease;';
                dropdown.style.bottom = (window.innerHeight - rect.top + 5) + 'px';
                dropdown.style.left = rect.left + 'px';
                dropdown.classList.add('show');

                // Also init any nested sub-dropdown triggers inside this dropdown
                initNestedDropdowns(dropdown);
            }
        });

        // Close on outside click
        document.addEventListener('click', function (e) {
            if (!footer.contains(e.target)) {
                closeAllDropdowns();
            }
        });
    }

    function initNestedDropdowns(parentDropdown) {
        var nestedTriggers = parentDropdown.querySelectorAll('[data-kt-menu-trigger]');
        for (var i = 0; i < nestedTriggers.length; i++) {
            // Avoid duplicate listeners
            if (nestedTriggers[i]._customInit) continue;
            nestedTriggers[i]._customInit = true;

            nestedTriggers[i].addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                var subDrop = this.querySelector('.menu-sub-dropdown');
                if (!subDrop) return;

                var isOpen = subDrop.classList.contains('show');
                // Close sibling nested dropdowns
                var siblings = this.parentElement.parentElement.querySelectorAll('.menu-sub-dropdown.show');
                for (var j = 0; j < siblings.length; j++) {
                    if (siblings[j] !== subDrop) {
                        siblings[j].classList.remove('show');
                        siblings[j].style.display = '';
                    }
                }

                if (!isOpen) {
                    var rect = this.getBoundingClientRect();
                    subDrop.style.cssText = 'display:block; position:fixed; z-index:108; animation:fadeIn 0.15s ease;';
                    subDrop.style.bottom = (window.innerHeight - rect.bottom) + 'px';
                    subDrop.style.left = (rect.right + 5) + 'px';
                    subDrop.classList.add('show');
                } else {
                    subDrop.classList.remove('show');
                    subDrop.style.display = '';
                }
            });
        }
    }

    function closeAllDropdowns() {
        var openDropdowns = document.querySelectorAll('.menu-sub-dropdown.show');
        for (var i = 0; i < openDropdowns.length; i++) {
            openDropdowns[i].classList.remove('show');
            openDropdowns[i].style.display = '';
        }
    }

    // =========================================
    // 4. THEME MODE SWITCHER (replaces KTThemeMode)
    // =========================================
    function initThemeMode() {
        var modeLinks = document.querySelectorAll('[data-kt-element="mode"]');
        if (!modeLinks.length) return;

        for (var i = 0; i < modeLinks.length; i++) {
            modeLinks[i].addEventListener('click', function (e) {
                e.preventDefault();
                var value = this.getAttribute('data-kt-value');
                if (!value) return;

                var resolved = value;
                if (value === 'system') {
                    resolved = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                }

                document.documentElement.setAttribute('data-bs-theme', resolved);
                localStorage.setItem('data-bs-theme', value);

                // Update active states
                for (var j = 0; j < modeLinks.length; j++) {
                    modeLinks[j].classList.remove('active');
                }
                this.classList.add('active');

                // Close the dropdown
                closeAllDropdowns();
            });
        }

        // Mark current mode as active
        var current = localStorage.getItem('data-bs-theme') || 'light';
        for (var i = 0; i < modeLinks.length; i++) {
            if (modeLinks[i].getAttribute('data-kt-value') === current) {
                modeLinks[i].classList.add('active');
            }
        }
    }

    // =========================================
    // 5. INIT ON DOM READY
    // =========================================
    function init() {
        initAccordions();
        initMobileDrawer();
        initUserDropdown();
        initThemeMode();
    }

    // Run immediately if DOM already loaded, else wait
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Expose for debugging
    window.VCSidebar = {
        openMobile: openMobileDrawer,
        closeMobile: closeMobileDrawer
    };

})();
