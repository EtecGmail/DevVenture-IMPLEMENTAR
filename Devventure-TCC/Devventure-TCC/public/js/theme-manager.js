(function (global) {
    const storageKey = 'theme';
    const modes = new Set(['light', 'dark', 'auto']);
    const doc = document.documentElement;
    const media = window.matchMedia('(prefers-color-scheme: dark)');
    let currentMode = read();
    let currentResolved = resolve(currentMode);

    function read() {
        const stored = localStorage.getItem(storageKey);
        return modes.has(stored) ? stored : 'auto';
    }

    function resolve(mode) {
        if (mode === 'light' || mode === 'dark') {
            return mode;
        }
        return media.matches ? 'dark' : 'light';
    }

    function apply(mode) {
        const resolved = resolve(mode);
        if (doc.dataset.theme !== resolved) {
            doc.dataset.theme = resolved;
        }
        doc.style.colorScheme = resolved === 'dark' ? 'dark' : 'light';
        currentResolved = resolved;
    }

    function emit() {
        const detail = { mode: currentMode, resolved: currentResolved };
        global.dispatchEvent(new CustomEvent('themechange', { detail }));
    }

    function handleMediaChange() {
        if (currentMode === 'auto') {
            apply(currentMode);
            emit();
        }
    }

    const Theme = {
        init() {
            currentMode = read();
            currentResolved = resolve(currentMode);
            apply(currentMode);
            media.addEventListener('change', handleMediaChange);
            emit();
        },
        set(mode) {
            const next = modes.has(mode) ? mode : 'auto';
            currentMode = next;
            localStorage.setItem(storageKey, next);
            apply(next);
            emit();
        },
        read,
        resolved() {
            return currentResolved;
        }
    };

    global.Theme = Theme;
})(window);
