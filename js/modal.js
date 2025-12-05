
/*
  Simple Modal Script (plain JavaScript)
  --------------------------------------
  Features:
  - Open modal when clicking any element with data-open="#modalId"
  - Close modal when clicking any element inside it with data-close
  - Close modal when clicking outside the modal-content area
  - Close modal when pressing the Escape key
*/

(function () {
  if (window.__modalInit) return;
  window.__modalInit = true;

  // Open: populate fields from opener's data-* and open the modal
  document.addEventListener('click', function (event) {
    const btn = event.target.closest('[data-open]');
    if (!btn) return;

    const targetSel = btn.dataset.open;
    const modal = targetSel && document.querySelector(targetSel);
    if (!modal) return;

    // Fill fields from opener's dataset
    for (const [key, value] of Object.entries(btn.dataset)) {
      if (key === 'open') continue;

      const esc = (window.CSS && CSS.escape) ? CSS.escape(key) : key.replace(/(["\\])/g, '\\$1');
      const selector = [
        `[name="${esc}"]`,
        `#${esc}`,
        `[id$="_${esc}"]`,
        `[id$="${esc}"]`,
        `[id*="${esc}"]`,
        `[data-fill="${esc}"]`,
      ].join(',');

      const field = modal.querySelector(selector);
      if (!field) continue;

      const tag = field.tagName.toLowerCase();
      if (tag === 'input' || tag === 'textarea' || tag === 'select') {
        field.value = value;
        field.dispatchEvent(new Event('input', { bubbles: true }));
      } else {
        field.textContent = value;
      }
    }

    // Show the modal
    modal.classList.add('open');
  }, true);

  // Close on [data-close] or backdrop click
  document.addEventListener('click', function (e) {
    const closeBtn = e.target.closest('[data-close]');
    if (closeBtn) {
      const modal = closeBtn.closest('.modal-wrapper');
      if (modal) modal.classList.remove('open');
      return;
    }

    // Backdrop click: close if click is on wrapper outside .modal-content
    const wrapper = e.target.closest('.modal-wrapper');
    if (wrapper && !e.target.closest('.modal-content')) {
      wrapper.classList.remove('open');
    }
  });

  // Escape key: closes all open modals
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      document.querySelectorAll('.modal-wrapper.open').forEach(m => m.classList.remove('open'));
    }
  });
})();