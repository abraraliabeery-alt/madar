(function(){
  // Front theme toggle
  var root = document.documentElement;
  var frontKey = 'site_theme';
  var frontBtn = document.getElementById('themeToggle');
  function setFrontTheme(v){
    root.setAttribute('data-theme', v);
    if(frontBtn){ frontBtn.innerHTML = v==='dark' ? '<i class="bi bi-sun"></i>' : '<i class="bi bi-moon"></i>'; }
  }
  try {
    var stored = localStorage.getItem(frontKey);
    if(stored === 'dark' || stored === 'light') { setFrontTheme(stored); }
  } catch(e) {}
  if(frontBtn){
    frontBtn.addEventListener('click', function(){
      var cur = root.getAttribute('data-theme')==='dark' ? 'dark' : 'light';
      var next = cur==='dark' ? 'light' : 'dark';
      try { localStorage.setItem(frontKey, next); } catch(e) {}
      setFrontTheme(next);
    });
  }

  // Admin theme toggle
  var adminKey = 'admin_theme';
  var adminBtn = document.getElementById('admin-theme-toggle');
  function applyAdmin(v){
    root.setAttribute('data-theme', v==='dark' ? 'dark' : 'light');
    if(adminBtn){
      // Use icon-only button for admin theme toggle (Font Awesome)
      adminBtn.innerHTML = v==='dark'
        ? '<i class="fas fa-sun"></i>'
        : '<i class="fas fa-moon"></i>';
    }
  }
  try {
    var aStored = localStorage.getItem(adminKey) || 'light';
    applyAdmin(aStored);
  } catch(e) {}
  if(adminBtn){
    adminBtn.addEventListener('click', function(){
      var cur = root.getAttribute('data-theme')==='dark' ? 'dark' : 'light';
      var next = cur==='dark' ? 'light' : 'dark';
      try { localStorage.setItem(adminKey, next); } catch(e) {}
      applyAdmin(next);
    });
  }
})();
