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
        html {
            scroll-behavior: smooth;
        }

        body {
            animation: ruangAlatPageIn 560ms cubic-bezier(0.22, 1, 0.36, 1);
        }

        .soft-reveal {
            opacity: 0;
            transform: translateY(18px);
            animation: ruangAlatReveal 620ms cubic-bezier(0.22, 1, 0.36, 1) forwards;
            animation-delay: var(--reveal-delay, 0ms);
            will-change: transform, opacity;
        }

        a,
        button,
        input,
        select,
        textarea,
        .card,
        .product-card,
        .content-card,
        .topbar,
        .notif-item,
        .table tbody tr,
        .btn,
        .btn-primary,
        .btn-secondary,
        .btn-detail {
            transition:
                transform 180ms ease,
                box-shadow 220ms ease,
                background-color 220ms ease,
                border-color 220ms ease,
                color 220ms ease,
                opacity 220ms ease;
        }

        a:hover,
        button:hover,
        .card:hover,
        .product-card:hover,
        .content-card:hover,
        .notif-item:hover {
            transform: translateY(-2px);
        }

        @keyframes ruangAlatPageIn {
            from {
                opacity: 0;
                transform: translateY(8px);
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
            html {
                scroll-behavior: auto;
            }

            body,
            .soft-reveal {
                animation: none !important;
            }

            a,
            button,
            input,
            select,
            textarea,
            .card,
            .product-card,
            .content-card,
            .topbar,
            .notif-item,
            .table tbody tr,
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
        element.style.setProperty('--reveal-delay', `${Math.min(index * 55, 420)}ms`);
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
    injectSoftMotionStyles();
    applySoftMotion();
    showGlobalAlerts();
    setupConfirmDialogs();
});
