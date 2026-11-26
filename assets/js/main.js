// Shared, persistent display preferences. Storage failures never disable buttons.
(() => {
  const root = document.documentElement;
  const read = (key) => { try { return localStorage.getItem(key); } catch { return null; } };
  const save = (key, value) => { try { localStorage.setItem(key, value); } catch {} };
  const setTheme = (theme) => {
    root.dataset.bsTheme = theme;
    const button = document.getElementById('theme-toggle');
    if (button) {
      button.setAttribute('aria-pressed', String(theme === 'dark'));
      button.textContent = theme === 'dark' ? 'Switch to light mode' : 'Switch to dark mode';
    }
  };
  const setTextSize = (large) => {
    root.dataset.textSize = large ? 'large' : 'normal';
    const button = document.getElementById('text-size-toggle');
    if (button) {
      button.setAttribute('aria-pressed', String(large));
      button.textContent = large ? 'Use standard text' : 'Use larger text';
    }
  };
  setTheme(read('sf-theme') === 'dark' ? 'dark' : 'light');
  setTextSize(read('sf-large-text') === 'true');
  document.addEventListener('DOMContentLoaded', () => {
    setTheme(root.dataset.bsTheme);
    setTextSize(root.dataset.textSize === 'large');
    document.getElementById('theme-toggle')?.addEventListener('click', () => {
      const next = root.dataset.bsTheme === 'dark' ? 'light' : 'dark';
      setTheme(next);
      save('sf-theme', next);
    });
    document.getElementById('text-size-toggle')?.addEventListener('click', () => {
      const large = root.dataset.textSize !== 'large';
      setTextSize(large);
      save('sf-large-text', String(large));
    });
  });
})();
function goBack() { window.history.back(); }
