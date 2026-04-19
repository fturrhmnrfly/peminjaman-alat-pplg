import './bootstrap';

import Alpine from 'alpinejs';
import Swal from 'sweetalert2';

window.Alpine = Alpine;
window.Swal = Swal;

Alpine.start();

const motionStyleId = 'ruang-alat-soft-motion';

function injectSoftMotionStyles() {
    if (document.getElementById(motionStyleId)) {
        return;
    }

    const style = document.createElement('style');
    style.id = motionStyleId;
    style.textContent = `
        html[data-motion-enabled="true"] {
            scroll-behavior: smooth;
        }

        body[data-page-motion] {
            --page-enter-duration: 320ms;
            --page-reveal-duration: 280ms;
            --page-reveal-distance: 12px;
            --page-stagger: 32ms;
            --page-hover-distance: -1px;
            animation: ruangAlatPageIn var(--page-enter-duration) ease-out;
        }

        body[data-page-motion="dashboard"] {
            --page-enter-duration: 260ms;
            --page-reveal-duration: 240ms;
            --page-reveal-distance: 10px;
            --page-stagger: 26ms;
        }

        body[data-page-motion="catalog"] {
            --page-enter-duration: 300ms;
            --page-reveal-duration: 320ms;
            --page-reveal-distance: 14px;
            --page-stagger: 22ms;
        }

        body[data-page-motion="detail"] {
            --page-enter-duration: 240ms;
            --page-reveal-duration: 260ms;
            --page-reveal-distance: 8px;
            --page-stagger: 20ms;
        }

        body[data-page-motion="table"] {
            --page-enter-duration: 220ms;
            --page-reveal-duration: 220ms;
            --page-reveal-distance: 6px;
            --page-stagger: 16ms;
            --page-hover-distance: 0;
        }

        body[data-page-motion] .soft-reveal {
            opacity: 0;
            transform: translateY(var(--page-reveal-distance));
            animation: ruangAlatReveal var(--page-reveal-duration) ease-out forwards;
            animation-delay: var(--reveal-delay, 0ms);
        }

        body[data-page-motion] a,
        body[data-page-motion] button,
        .card,
        .product-card,
        .btn,
        .btn-primary,
        .btn-secondary,
        .btn-detail {
            transition:
                transform 160ms ease,
                box-shadow 180ms ease,
                background-color 180ms ease,
                border-color 180ms ease,
                color 180ms ease;
        }

        body[data-page-motion] a:hover,
        body[data-page-motion] button:hover,
        .card:hover,
        .product-card:hover,
        .btn:hover,
        .btn-primary:hover,
        .btn-secondary:hover,
        .btn-detail:hover {
            transform: translateY(var(--page-hover-distance));
        }

        @keyframes ruangAlatPageIn {
            from {
                opacity: 0;
                transform: translateY(6px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes ruangAlatReveal {
            from {
                opacity: 0;
                transform: translateY(18px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .ruang-alat-swal-in {
            animation: ruangAlatSwalIn 220ms ease-out;
        }

        .ruang-alat-swal-out {
            animation: ruangAlatSwalOut 180ms ease-in;
        }

        @keyframes ruangAlatSwalIn {
            from {
                opacity: 0;
                transform: scale(0.96) translateY(10px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        @keyframes ruangAlatSwalOut {
            from {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
            to {
                opacity: 0;
                transform: scale(0.98) translateY(6px);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            html[data-motion-enabled="true"] {
                scroll-behavior: auto;
            }

            body[data-page-motion],
            body[data-page-motion] .soft-reveal {
                animation: none !important;
            }

            body[data-page-motion] a,
            body[data-page-motion] button,
            .card,
            .product-card,
            .btn,
            .btn-primary,
            .btn-secondary,
            .btn-detail {
                transition: none !important;
            }
        }
    `;

    document.head.appendChild(style);
}

function applySoftMotion() {
    const pageMotion = document.body.dataset.pageMotion;
    if (!pageMotion) {
        return;
    }

    const selectors = [
        '.topbar',
        '.content-card',
        '.catalog-shell',
        '.product-card',
        '.notif-item',
        '.card',
        '.table tbody tr',
        '.detail-shell',
        '.panel',
        '.filter-panel',
        '.section-head',
    ];

    const elements = document.querySelectorAll(selectors.join(', '));
    elements.forEach((element, index) => {
        if (element.classList.contains('soft-reveal')) {
            return;
        }

        element.classList.add('soft-reveal');
        element.style.setProperty('--reveal-delay', `${Math.min(index * Number.parseInt(getComputedStyle(document.body).getPropertyValue('--page-stagger') || '32', 10), 240)}ms`);
    });
}

function normalizeMessage(message) {
    return (message || '').replace(/\s+/g, ' ').trim();
}

function hideSourceElement(element) {
    if (!element) {
        return;
    }

    element.dataset.swalHandled = 'true';
    element.style.display = 'none';
}

function collectMessages() {
    const buckets = [
        { selector: '#global-feedback-data [data-swal-type]', resolveType: (el) => el.dataset.swalType || 'info' },
        { selector: '.alert-success', resolveType: () => 'success' },
        { selector: '.alert-error', resolveType: () => 'error' },
        { selector: '.alert-warning', resolveType: () => 'warning' },
        { selector: '.error-message', resolveType: () => 'error' },
    ];

    const seen = new Set();
    const messages = [];

    buckets.forEach(({ selector, resolveType }) => {
        document.querySelectorAll(selector).forEach((element) => {
            if (element.dataset.swalHandled === 'true') {
                return;
            }

            if (element.dataset.swalIgnore === 'true') {
                return;
            }

            const message = normalizeMessage(element.textContent);
            if (!message) {
                return;
            }

            const type = resolveType(element);
            const key = `${type}:${message}`;

            if (seen.has(key)) {
                hideSourceElement(element);
                return;
            }

            seen.add(key);
            messages.push({ type, message, element });
        });
    });

    return messages;
}

async function showGlobalAlerts() {
    const messages = collectMessages();
    const swalTitle = document.body.dataset.swalTitle || null;

    for (const item of messages) {
        hideSourceElement(item.element);

        await Swal.fire({
            icon: item.type === 'warning' ? 'warning' : item.type === 'error' ? 'error' : 'success',
            title:
                swalTitle ||
                (item.type === 'error'
                    ? 'Terjadi Kesalahan'
                    : item.type === 'warning'
                      ? 'Perhatian'
                      : 'Berhasil'),
            text: item.message,
            confirmButtonText: 'OK',
            confirmButtonColor: '#0f766e',
            background: '#ffffff',
            color: '#111827',
            showClass: {
                popup: 'swal2-show ruang-alat-swal-in',
            },
            hideClass: {
                popup: 'swal2-hide ruang-alat-swal-out',
            },
        });
    }
}

function setupConfirmDialogs() {
    document.querySelectorAll('form[data-confirm-message]').forEach((form) => {
        if (form.dataset.confirmBound === 'true') {
            return;
        }

        form.dataset.confirmBound = 'true';
        form.addEventListener('submit', async (event) => {
            if (form.dataset.confirmed === 'true') {
                form.dataset.confirmed = 'false';
                return;
            }

            event.preventDefault();

            const result = await Swal.fire({
                icon: form.dataset.confirmIcon || 'warning',
                title: form.dataset.confirmTitle || 'Yakin?',
                text: form.dataset.confirmMessage || 'Tindakan ini akan diproses.',
                showCancelButton: true,
                confirmButtonText: form.dataset.confirmButton || 'Ya, lanjutkan',
                cancelButtonText: form.dataset.cancelButton || 'Batal',
                confirmButtonColor: form.dataset.confirmColor || '#dc2626',
                cancelButtonColor: '#64748b',
                background: '#ffffff',
                color: '#111827',
                reverseButtons: true,
                showClass: {
                    popup: 'swal2-show ruang-alat-swal-in',
                },
                hideClass: {
                    popup: 'swal2-hide ruang-alat-swal-out',
                },
            });

            if (result.isConfirmed) {
                form.dataset.confirmed = 'true';
                form.requestSubmit();
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    document.documentElement.dataset.motionEnabled = 'true';
    injectSoftMotionStyles();
    applySoftMotion();
    showGlobalAlerts();
    setupConfirmDialogs();
});
