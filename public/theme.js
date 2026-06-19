(function(){
  var root = document.documentElement;
  var key = 'theme';

  function getStored(){
    try {
      var v = localStorage.getItem(key);
      if(v === 'dark' || v === 'light') return v;
    } catch(e) {}
    return null;
  }

  function prefersDark(){
    return !!(window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches);
  }

  function setTheme(v){
    var mode = v === 'dark' ? 'dark' : 'light';
    root.setAttribute('data-theme', mode);
    root.classList.toggle('dark', mode === 'dark');
    try { localStorage.setItem(key, mode); } catch(e) {}
    updateButtons(mode);
  }

  function updateButtons(mode){
    var btns = Array.prototype.slice.call(document.querySelectorAll('[data-theme-toggle], #themeToggle, #admin-theme-toggle, #theme-toggle'));
    btns.forEach(function(btn){
      if(!btn) return;

      btn.setAttribute('aria-pressed', mode === 'dark' ? 'true' : 'false');

      var sun = btn.querySelector('[data-icon="sun"]');
      var moon = btn.querySelector('[data-icon="moon"]');
      if(sun && moon){
        sun.classList.toggle('hidden', mode !== 'dark');
        moon.classList.toggle('hidden', mode === 'dark');
      } else {
        btn.innerHTML = mode === 'dark' ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
      }
    });
  }

  function toggle(){
    var cur = root.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
    setTheme(cur === 'dark' ? 'light' : 'dark');
  }

  var initial = getStored();
  if(!initial) initial = prefersDark() ? 'dark' : 'light';
  setTheme(initial);

  document.addEventListener('click', function(e){
    var el = e.target;
    if(!el) return;
    var btn = el.closest ? el.closest('[data-theme-toggle], #themeToggle, #admin-theme-toggle, #theme-toggle') : null;
    if(btn){
      e.preventDefault();
      toggle();
    }
  });

  window.setTheme = setTheme;
  window.toggleTheme = toggle;
})();
