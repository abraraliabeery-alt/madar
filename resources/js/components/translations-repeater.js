import { Toast } from 'bootstrap';

function escapeRegExp(str) {
    return String(str).replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}

function ensureToastContainer() {
    let container = document.getElementById('app-toast-container');
    if (container) return container;

    container = document.createElement('div');
    container.id = 'app-toast-container';
    container.className = 'toast-container position-fixed top-0 start-50 translate-middle-x p-3';
    container.style.zIndex = '1090';
    document.body.appendChild(container);
    return container;
}

function showToast(message) {
    if (!message) return;

    const container = ensureToastContainer();

    const el = document.createElement('div');
    el.className = 'toast align-items-center border-0';
    el.setAttribute('role', 'alert');
    el.setAttribute('aria-live', 'assertive');
    el.setAttribute('aria-atomic', 'true');

    el.style.background = 'var(--brand-surface)';
    el.style.color = 'var(--brand-fg)';
    el.style.border = '1px solid var(--brand-border)';

    el.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${String(message)}</div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;

    container.appendChild(el);
    const toast = new Toast(el, { delay: 2500 });
    el.addEventListener('hidden.bs.toast', () => el.remove());
    toast.show();
}

function initTranslationsRepeater(root) {
    if (!root || root.dataset.trInited === '1') return;
    root.dataset.trInited = '1';

    const itemsEl = root.querySelector('[data-tr-items]');
    const addBtn = root.querySelector('[data-tr-add]');
    const tpl = document.getElementById(root.getAttribute('data-tr-repeater') + '_tpl');
    if (!itemsEl || !tpl) return;

    const namePrefix = root.getAttribute('data-tr-name-prefix') || 'translations';
    const minItems = parseInt(root.getAttribute('data-tr-min') || '1', 10);
    const maxRaw = root.getAttribute('data-tr-max');
    const maxItems = (maxRaw === null || maxRaw === '') ? null : parseInt(maxRaw, 10);
    const dupMsg = root.getAttribute('data-tr-duplicate-msg') || 'Duplicate locale';

    function getItems() {
        return Array.from(itemsEl.querySelectorAll('[data-tr-item]'));
    }

    function setInvalid(fieldWrap, key, message) {
        if (!fieldWrap) return;
        const ctrl = fieldWrap.querySelector(`[name$="[${key}]"]`) || fieldWrap.querySelector('select, input, textarea');
        if (ctrl) ctrl.classList.add('is-invalid');

        const fb = fieldWrap.querySelector(`[data-tr-invalid-for="${key}"]`);
        if (fb) {
            fb.textContent = message || '';
            fb.classList.remove('d-none');
        }
    }

    function clearInvalid(fieldWrap, key) {
        if (!fieldWrap) return;
        const ctrl = fieldWrap.querySelector(`[name$="[${key}]"]`) || fieldWrap.querySelector('select, input, textarea');
        if (ctrl) ctrl.classList.remove('is-invalid');

        const fb = fieldWrap.querySelector(`[data-tr-invalid-for="${key}"]`);
        if (fb) {
            fb.textContent = '';
            fb.classList.add('d-none');
        }
    }

    function validateAll() {
        let ok = true;
        const items = getItems();

        items.forEach((item) => {
            item.querySelectorAll('[data-tr-invalid-for]').forEach((fb) => {
                const key = fb.getAttribute('data-tr-invalid-for');
                const fieldWrap = fb.closest('.col-12') || fb.closest('.flex-grow-1') || item;
                clearInvalid(fieldWrap, key);
            });
            item.querySelectorAll('.is-invalid').forEach((el) => el.classList.remove('is-invalid'));
        });

        const selects = getLocaleSelects();
        const seen = new Set();
        selects.forEach((sel) => {
            const item = sel.closest('[data-tr-item]');
            const fieldWrap = sel.closest('.flex-grow-1') || item;
            const v = (sel.value || '').trim();

            if (!v) {
                setInvalid(fieldWrap, 'locale', 'اللغة مطلوبة');
                ok = false;
                return;
            }

            if (seen.has(v)) {
                setInvalid(fieldWrap, 'locale', dupMsg);
                ok = false;
                return;
            }

            seen.add(v);
        });

        items.forEach((item, idx) => {
            const controls = item.querySelectorAll('input[data-tr-required-first="1"], textarea[data-tr-required-first="1"], input[data-tr-required="1"], textarea[data-tr-required="1"]');
            controls.forEach((ctrl) => {
                const name = ctrl.getAttribute('name') || '';
                const m = name.match(/\[([^\]]+)\]$/);
                const key = m ? m[1] : '';
                const fieldWrap = ctrl.closest('.col-12') || item;

                const requiredAll = ctrl.getAttribute('data-tr-required') === '1';
                const requiredFirst = ctrl.getAttribute('data-tr-required-first') === '1';

                if (requiredFirst && idx !== 0) {
                    clearInvalid(fieldWrap, key);
                    return;
                }

                if (!requiredAll && !requiredFirst) {
                    return;
                }

                const val = (ctrl.value || '').trim();
                if (!val) {
                    setInvalid(fieldWrap, key, 'هذا الحقل مطلوب');
                    ok = false;
                }
            });
        });

        return ok;
    }

    function getLocaleSelects() {
        return Array.from(itemsEl.querySelectorAll('select[name$="[locale]"]'));
    }

    function selectedLocales() {
        return getLocaleSelects().map(s => (s.value || '').trim()).filter(Boolean);
    }

    function remainingLocalesCount() {
        const first = getLocaleSelects()[0];
        if (!first) return 0;
        const selected = selectedLocales();
        const opts = Array.from(first.options).filter(o => !!o.value);
        return opts.filter(o => !selected.includes(o.value)).length;
    }

    function syncDisable() {
        const selected = selectedLocales();
        getLocaleSelects().forEach(sel => {
            const self = (sel.value || '').trim();
            Array.from(sel.options).forEach(opt => {
                if (!opt.value) return;
                opt.disabled = selected.includes(opt.value) && opt.value !== self;
            });
        });
    }

    function enforceUnique(changed) {
        const val = (changed.value || '').trim();
        if (!val) return true;
        const others = getLocaleSelects().filter(s => s !== changed);
        if (others.some(s => (s.value || '').trim() === val)) {
            changed.value = '';
            syncDisable();
            showToast(dupMsg);
            return false;
        }
        return true;
    }

    function renumber() {
        const re = new RegExp(escapeRegExp(namePrefix) + '\\[\\d+\\]', 'g');

        getItems().forEach((item, idx) => {
            item.setAttribute('data-index', String(idx));

            item.querySelectorAll('input, textarea, select').forEach(el => {
                const nm = el.getAttribute('name');
                if (nm) el.setAttribute('name', nm.replace(re, `${namePrefix}[${idx}]`));

                const id = el.getAttribute('id');
                if (id) el.setAttribute('id', id.replace(re, `${namePrefix}[${idx}]`));
            });

            item.querySelectorAll('label[for]').forEach(lab => {
                const fr = lab.getAttribute('for');
                if (fr) lab.setAttribute('for', fr.replace(re, `${namePrefix}[${idx}]`));
            });
        });
    }

    function refreshButtons() {
        const items = getItems();

        items.forEach((item, idx) => {
            const rm = item.querySelector('[data-tr-remove]');
            if (!rm) return;
            rm.classList.toggle('d-none', idx === 0 && minItems >= 1);
            rm.disabled = items.length <= minItems;
        });

        if (addBtn) {
            const noLocales = remainingLocalesCount() <= 0;
            const maxReached = (maxItems !== null && items.length >= maxItems);
            addBtn.disabled = noLocales || maxReached;
        }
    }

    function add() {
        const items = getItems();
        if (maxItems !== null && items.length >= maxItems) return;
        if (remainingLocalesCount() <= 0) return;

        const idx = items.length;
        const html = tpl.innerHTML.replaceAll('__INDEX__', String(idx));
        const wrap = document.createElement('div');
        wrap.innerHTML = html.trim();
        const node = wrap.firstElementChild;
        if (node) {
            itemsEl.appendChild(node);
            renumber();
            syncDisable();
            refreshButtons();
        }
    }

    root.addEventListener('change', (e) => {
        const target = e.target;
        if (!(target instanceof HTMLElement)) return;
        if (!target.matches('select[name$="[locale]"]')) return;
        if (!enforceUnique(target)) return;
        syncDisable();
        refreshButtons();
    });

    root.addEventListener('input', (e) => {
        const t = e.target;
        if (!(t instanceof HTMLElement)) return;
        if (!(t.matches('input, textarea, select'))) return;

        const fieldWrap = t.closest('.col-12') || t.closest('.flex-grow-1') || root;
        const nm = t.getAttribute('name') || '';
        const m = nm.match(/\[([^\]]+)\]$/);
        const key = m ? m[1] : 'locale';
        clearInvalid(fieldWrap, key);
    });

    root.addEventListener('click', (e) => {
        const t = e.target;
        if (!(t instanceof HTMLElement)) return;

        const rm = t.closest('[data-tr-remove]');
        if (rm) {
            const items = getItems();
            if (items.length <= minItems) return;

            const item = rm.closest('[data-tr-item]');
            if (item) {
                item.remove();
                renumber();
                syncDisable();
                refreshButtons();
            }
            return;
        }

        const addClick = t.closest('[data-tr-add]');
        if (addClick) add();
    });

    renumber();
    syncDisable();
    refreshButtons();

    const form = root.closest('form');
    if (form && form.dataset.trRepeaterValidated !== '1') {
        form.dataset.trRepeaterValidated = '1';
        form.addEventListener('submit', (e) => {
            const roots = Array.from(form.querySelectorAll('[data-tr-repeater]'));
            const allOk = roots.map((r) => r === root ? validateAll() : true).every(Boolean);
            if (!allOk) {
                e.preventDefault();
                showToast('تحقق من الحقول المطلوبة');
            }
        });
    }
}

function initAll() {
    document.querySelectorAll('[data-tr-repeater]').forEach(initTranslationsRepeater);
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAll);
} else {
    initAll();
}
