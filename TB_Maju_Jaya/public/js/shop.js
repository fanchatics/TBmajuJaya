(function () {
    'use strict';

    const body = document.body;
    const loginUrl = body.dataset.loginUrl || '/login';

    /* ---------- Toast ---------- */
    function toast(message, type) {
        let host = document.getElementById('toastHost');
        if (!host) {
            host = document.createElement('div');
            host.id = 'toastHost';
            host.className = 'toast-container position-fixed top-0 end-0 p-3';
            host.style.zIndex = 2000;
            document.body.appendChild(host);
        }
        const el = document.createElement('div');
        el.className = 'toast align-items-center border-0 text-bg-' + (type === 'error' ? 'danger' : 'success');
        el.setAttribute('role', 'alert');
        el.innerHTML = '<div class="d-flex"><div class="toast-body"></div>' +
            '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>';
        el.querySelector('.toast-body').textContent = message;
        host.appendChild(el);
        const t = new bootstrap.Toast(el, { delay: 3200 });
        el.addEventListener('hidden.bs.toast', () => el.remove());
        t.show();
    }
    window.tbToast = toast;

    /* ---------- Live add-to-cart ---------- */
    document.addEventListener('submit', async function (e) {
        const form = e.target.closest('[data-cart-form]');
        if (!form) return;
        e.preventDefault();

        const btn = form.querySelector('button[type=submit]');
        const original = btn ? btn.innerHTML : '';
        if (btn) { btn.disabled = true; btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>'; }

        try {
            const res = await fetch(form.action, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                body: new FormData(form),
                credentials: 'same-origin',
            });

            if (res.status === 401) { window.location.href = loginUrl; return; }
            if (res.status === 419) { window.location.reload(); return; }

            const data = await res.json();
            toast(data.message || 'Selesai', data.ok ? 'success' : 'error');

            if (typeof data.count === 'number') {
                const badge = document.getElementById('cartBadge');
                if (badge) {
                    badge.textContent = data.count;
                    badge.classList.toggle('d-none', data.count === 0);
                    badge.classList.remove('bump'); void badge.offsetWidth; badge.classList.add('bump');
                }
            }
        } catch (err) {
            toast('Terjadi kesalahan. Silakan coba lagi.', 'error');
        } finally {
            if (btn) { btn.disabled = false; btn.innerHTML = original; }
        }
    });

    /* ---------- Quantity pickers ---------- */
    document.addEventListener('click', function (e) {
        const b = e.target.closest('[data-qty-step]');
        if (!b) return;
        const wrap = b.closest('.qty-picker');
        const input = wrap.querySelector('input');
        const step = parseInt(b.dataset.qtyStep, 10);
        const min = parseInt(input.min || '1', 10);
        const max = parseInt(input.max || '9999', 10);
        const next = Math.min(max, Math.max(min, (parseInt(input.value, 10) || min) + step));
        input.value = next;
        input.dispatchEvent(new Event('change', { bubbles: true }));
    });

    document.addEventListener('change', function (e) {
        if (e.target.matches('.qty-picker input')) {
            const i = e.target;
            const min = parseInt(i.min || '1', 10), max = parseInt(i.max || '9999', 10);
            i.value = Math.min(max, Math.max(min, parseInt(i.value, 10) || min));
        }
        if (e.target.matches('[data-autosubmit]')) {
            e.target.closest('form').submit();
        }
    });

    /* ---------- Image zoom ---------- */
    document.querySelectorAll('.zoom-box').forEach(function (box) {
        const img = box.querySelector('img');
        box.addEventListener('mouseenter', () => box.classList.add('zooming'));
        box.addEventListener('mouseleave', () => { box.classList.remove('zooming'); img.style.transformOrigin = 'center'; });
        box.addEventListener('mousemove', function (ev) {
            const r = box.getBoundingClientRect();
            img.style.transformOrigin = ((ev.clientX - r.left) / r.width * 100) + '% ' + ((ev.clientY - r.top) / r.height * 100) + '%';
        });
    });

    /* ---------- Confirm dialogs ---------- */
    document.addEventListener('submit', function (e) {
        const msg = e.target.dataset && e.target.dataset.confirm;
        if (msg && !window.confirm(msg)) e.preventDefault();
    });
})();
