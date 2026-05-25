<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Quick Navigation Routes
        const pages = [
            // Dashboard
            { name: 'Dashboard', route: '{{ route('admin.dashboard') }}', icon: 'ki-outline ki-element-11', color: 'primary' },

            // Requests
            { name: 'All Requests', route: '{{ route('admin.requests.index') }}', icon: 'ki-outline ki-calendar-8', color: 'success' },
            { name: 'Today\'s Requests', route: '{{ route('admin.requests.today') }}', icon: 'ki-outline ki-calendar-add', color: 'success' },

            // Bids
            { name: 'All Bids', route: '{{ route('admin.bids.index') }}', icon: 'ki-outline ki-handcart', color: 'warning' },
            { name: 'Today\'s Bids', route: '{{ route('admin.bids.today') }}', icon: 'ki-outline ki-abstract-26', color: 'warning' },
            { name: 'Accepted Bids', route: '{{ route('admin.bids.accepted') }}', icon: 'ki-outline ki-check-circle', color: 'success' },
            { name: 'Rejected Bids', route: '{{ route('admin.bids.rejected') }}', icon: 'ki-outline ki-cross-circle', color: 'danger' },

            // Bookings
            { name: 'All Bookings', route: '{{ route('admin.bookings.index') }}', icon: 'ki-outline ki-calendar-tick', color: 'info' },
            { name: 'Active Bookings', route: '{{ route('admin.bookings.active') }}', icon: 'ki-outline ki-pulse', color: 'primary' },
            { name: 'Cancelled Bookings', route: '{{ route('admin.bookings.cancelled') }}', icon: 'ki-outline ki-abstract-11', color: 'danger' },

            // Patients
            { name: 'All Patients', route: '{{ route('admin.patients.index') }}', icon: 'ki-outline ki-user', color: 'primary' },
            { name: 'Blocked Patients', route: '{{ route('admin.patients.blocked') }}', icon: 'ki-outline ki-cross-circle', color: 'danger' },

            // Nurses
            { name: 'All Nurses', route: '{{ route('admin.nurses.index') }}', icon: 'ki-outline ki-profile-user', color: 'success' },
            { name: 'Pending Nurses', route: '{{ route('admin.nurses.pending_approval') }}', icon: 'ki-outline ki-time', color: 'warning' },
            { name: 'Approved Nurses', route: '{{ route('admin.nurses.approved') }}', icon: 'ki-outline ki-verify', color: 'success' },
            { name: 'Rejected Nurses', route: '{{ route('admin.nurses.rejected') }}', icon: 'ki-outline ki-cross-square', color: 'danger' },

            // Services
            { name: 'Care Types', route: '{{ route('admin.services.care-types.index') }}', icon: 'ki-outline ki-heart', color: 'danger' },

            // Support
            { name: 'Support Tickets', route: '{{ route('admin.support.index') }}', icon: 'ki-outline ki-message-text-2', color: 'primary' },
            { name: 'Support Categories', route: '{{ route('admin.support.categories.index') }}', icon: 'ki-outline ki-category', color: 'info' },
            { name: 'FAQs', route: '{{ route('admin.support.faqs.index') }}', icon: 'ki-outline ki-question', color: 'warning' },

            // System
            { name: 'Login History', route: '{{ route('admin.login-history.index') }}', icon: 'ki-outline ki-entrance-left', color: 'secondary' },
            { name: 'Error Logs', route: '{{ route('admin.system.error-logs') }}', icon: 'ki-outline ki-bug', color: 'danger' }
        ];

        const input = document.querySelector('.search-input');
        const menu = document.querySelector('[data-kt-search-element="content"]');
        const main = document.querySelector('[data-kt-search-element="main"]');
        const empty = document.querySelector('[data-kt-search-element="empty"]');
        const results = document.querySelector('[data-kt-search-element="results"]');
        const resultsContainer = results.querySelector('.scroll-y');
        const resetBtn = document.querySelector('[data-kt-search-element="clear"]');

        if (!input || !menu) return;

        // Ensure menu acts as a proper dropdown
        menu.style.position = 'absolute';
        menu.style.top = '100%';
        menu.style.left = '0';
        menu.style.zIndex = '105';
        menu.style.backgroundColor = '#fff';
        menu.style.border = '1px solid #eff2f5';
        menu.style.boxShadow = '0px 0px 50px 0px rgba(82, 63, 105, 0.15)';
        menu.style.display = 'none';

        input.addEventListener('focus', function () {
            menu.style.display = 'block';
        });

        document.addEventListener('click', function (e) {
            const isClickInside = input.contains(e.target) || menu.contains(e.target);
            if (!isClickInside) {
                menu.style.display = 'none';
            }
        });

        resetBtn.addEventListener('click', function () {
            input.value = '';
            input.dispatchEvent(new Event('input'));
            input.focus();
        });

        input.addEventListener('input', function () {
            const val = this.value.trim().toLowerCase();

            if (val === '') {
                main.classList.remove('d-none');
                empty.classList.add('d-none');
                results.classList.add('d-none');
                resetBtn.classList.add('d-none');
                return;
            }

            resetBtn.classList.remove('d-none');

            const matches = pages.filter(p => p.name.toLowerCase().includes(val));

            if (matches.length === 0) {
                main.classList.add('d-none');
                results.classList.add('d-none');
                empty.classList.remove('d-none');
            } else {
                main.classList.add('d-none');
                empty.classList.add('d-none');
                results.classList.remove('d-none');

                resultsContainer.innerHTML = '';
                matches.forEach(m => {
                    const html = `
                        <a href="${m.route}" class="d-flex align-items-center p-3 rounded bg-state-light bg-state-opacity-50 mb-2 text-decoration-none transition-base hover-scale">
                            <div class="symbol symbol-40px me-4">
                                <span class="symbol-label bg-light-${m.color}">
                                    <i class="${m.icon} fs-2 text-${m.color}"></i>
                                </span>
                            </div>
                            <div class="d-flex flex-column flex-grow-1">
                                <span class="fs-6 fw-bold text-gray-900 text-hover-${m.color}">${m.name}</span>
                                <span class="fs-8 fw-semibold text-muted">Jump to this page</span>
                            </div>
                            <i class="ki-outline ki-arrow-right fs-4 text-muted ms-auto"></i>
                        </a>
                    `;
                    resultsContainer.insertAdjacentHTML('beforeend', html);
                });
            }
        });
    });
</script>
