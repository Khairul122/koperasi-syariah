/**
 * SynAlert — Neobrutalist Alert Dialog Component
 *
 * Global API:
 *   SynAlert.success(title, message, onClose?)
 *   SynAlert.error(title, message, onClose?)
 *   SynAlert.warning(title, message, onClose?)
 *   SynAlert.loading(title?, message?)
 *   SynAlert.info(title, message, onClose?)
 *   SynAlert.show({ type, title, message, html?, onClose?, dismissOnOverlay? })
 *   SynAlert.confirm({ title, message, html?, type?, confirmText?, cancelText?, onConfirm?, onCancel? })
 *   SynAlert.close()
 */
const SynAlert = (() => {
    'use strict';

    /* ── Type configuration ───────────────────────────────── */
    const TYPE_CONFIG = {
        success: {
            btnLabel : 'OK',
            icon     : `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                          stroke="currentColor" stroke-width="3"
                          stroke-linecap="round" stroke-linejoin="round">
                          <polyline points="20 6 9 17 4 12"/>
                        </svg>`,
        },
        error: {
            btnLabel : 'TUTUP',
            icon     : `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                          stroke="currentColor" stroke-width="3"
                          stroke-linecap="round" stroke-linejoin="round">
                          <line x1="18" y1="6"  x2="6"     y2="18"/>
                          <line x1="6"  y1="6"  x2="18"    y2="18"/>
                        </svg>`,
        },
        warning: {
            btnLabel : 'MENGERTI',
            icon     : `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                          stroke="currentColor" stroke-width="3"
                          stroke-linecap="round" stroke-linejoin="round">
                          <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94
                                   a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                          <line x1="12" y1="9"  x2="12"   y2="13"/>
                          <line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>`,
        },
        loading: {
            btnLabel : null,
            icon     : null,
        },
        info: {
            btnLabel : 'OK',
            icon     : `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                          stroke="currentColor" stroke-width="3"
                          stroke-linecap="round" stroke-linejoin="round">
                          <circle cx="12" cy="12" r="10"/>
                          <line x1="12" y1="8"  x2="12"   y2="12"/>
                          <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>`,
        },
    };

    /* ── Internal state ───────────────────────────────────── */
    let _overlay = null;

    /* ── Helpers ──────────────────────────────────────────── */
    function _esc(str) {
        return String(str ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function _dismiss(onClose) {
        if (!_overlay) return;
        const el = _overlay;
        el.classList.add('syna-exit');
        _overlay = null;
        setTimeout(() => el.remove(), 160);
        if (typeof onClose === 'function') onClose();
    }

    /* ── Core render ──────────────────────────────────────── */
    function show({ type = 'info', title = '', message = '', html = null, onClose = null, dismissOnOverlay = true } = {}) {
        close();

        const cfg  = TYPE_CONFIG[type] ?? TYPE_CONFIG.info;
        const overlay = document.createElement('div');
        overlay.className = 'syna-overlay';
        overlay.setAttribute('role', 'dialog');
        overlay.setAttribute('aria-modal', 'true');

        if (dismissOnOverlay && type !== 'loading') {
            overlay.addEventListener('click', (e) => {
                if (e.target === overlay) _dismiss(onClose);
            });
        }

        const iconContent = type === 'loading'
            ? `<div class="syna-spinner"></div>`
            : cfg.icon;

        /* message body: prefer raw html over escaped text */
        const bodyContent = html
            ? `<div class="syna-message">${html}</div>`
            : `<p class="syna-message">${_esc(message)}</p>`;

        const footerHtml = cfg.btnLabel
            ? `<div class="syna-footer">
                 <button class="syna-btn syna-btn--${type}" data-syna-close>
                   ${cfg.btnLabel}
                 </button>
               </div>`
            : '';

        overlay.innerHTML = `
            <div class="syna-dialog syna-dialog--${type}">
                <div class="syna-body">
                    <div class="syna-icon syna-icon--${type}" aria-hidden="true">
                        ${iconContent}
                    </div>
                    <div class="syna-text">
                        <p class="syna-title">${_esc(title)}</p>
                        ${bodyContent}
                    </div>
                </div>
                ${footerHtml}
            </div>
        `;

        const btn = overlay.querySelector('[data-syna-close]');
        if (btn) {
            btn.addEventListener('click', () => _dismiss(onClose));
            setTimeout(() => btn.focus(), 60);
        }

        document.body.appendChild(overlay);
        _overlay = overlay;

        return { close: () => _dismiss(onClose) };
    }

    /* ── Confirm dialog (two buttons) ─────────────────────── */
    function confirm({
        title        = 'Konfirmasi',
        message      = '',
        html         = null,
        type         = 'warning',
        confirmText  = 'Ya',
        cancelText   = 'Batal',
        onConfirm    = null,
        onCancel     = null,
    } = {}) {
        close();

        const cfg     = TYPE_CONFIG[type] ?? TYPE_CONFIG.warning;
        const overlay = document.createElement('div');
        overlay.className = 'syna-overlay';
        overlay.setAttribute('role', 'alertdialog');
        overlay.setAttribute('aria-modal', 'true');

        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                _dismiss(onCancel);
            }
        });

        const iconContent = cfg.icon || '';
        const bodyContent = html
            ? `<div class="syna-message">${html}</div>`
            : `<p class="syna-message">${_esc(message)}</p>`;

        overlay.innerHTML = `
            <div class="syna-dialog syna-dialog--${type}">
                <div class="syna-body">
                    <div class="syna-icon syna-icon--${type}" aria-hidden="true">
                        ${iconContent}
                    </div>
                    <div class="syna-text">
                        <p class="syna-title">${_esc(title)}</p>
                        ${bodyContent}
                    </div>
                </div>
                <div class="syna-footer">
                    <button class="syna-btn syna-btn--outline" data-syna-cancel>
                        ${_esc(cancelText)}
                    </button>
                    <button class="syna-btn syna-btn--${type}" data-syna-confirm>
                        ${_esc(confirmText)}
                    </button>
                </div>
            </div>
        `;

        overlay.querySelector('[data-syna-cancel]').addEventListener('click', () => _dismiss(onCancel));
        overlay.querySelector('[data-syna-confirm]').addEventListener('click', () => {
            _dismiss();
            if (typeof onConfirm === 'function') onConfirm();
        });

        setTimeout(() => overlay.querySelector('[data-syna-cancel]')?.focus(), 60);

        document.body.appendChild(overlay);
        _overlay = overlay;
    }

    /* ── Public shortcuts ─────────────────────────────────── */
    function success(title, message, onClose) {
        return show({ type: 'success', title, message, onClose });
    }

    function error(title, message, onClose) {
        return show({ type: 'error', title, message, onClose });
    }

    function warning(title, message, onClose) {
        return show({ type: 'warning', title, message, onClose });
    }

    function loading(title = 'Memproses...', message = 'Mohon tunggu sebentar.') {
        return show({ type: 'loading', title, message });
    }

    function info(title, message, onClose) {
        return show({ type: 'info', title, message, onClose });
    }

    function close() {
        if (_overlay) _dismiss();
    }

    /* ── Global ESC key ───────────────────────────────────── */
    document.addEventListener('keydown', (e) => {
        if (e.key !== 'Escape' || !_overlay) return;
        const isLoading = _overlay.querySelector('.syna-dialog--loading');
        if (!isLoading) close();
    });

    /* ── Public API ───────────────────────────────────────── */
    return { show, confirm, success, error, warning, loading, info, close };
})();
