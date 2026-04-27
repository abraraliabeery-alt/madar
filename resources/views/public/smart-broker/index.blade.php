<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>وسيط عقاري ذكي — تشغيل تلقائي بدون إدخال</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
  <style>
    body{font-family:'Tajawal','Cairo','Segoe UI',Tahoma,Arial,sans-serif;background:#0f172a;color:#e5e7eb;margin:0;padding:18px}
    .wrap{max-width:1100px;margin:0 auto}
    h1{margin:0 0 6px;text-align:center}
    .sub{opacity:.85;text-align:center;line-height:1.7;margin:0 0 14px}
    .card{background:#020617;border:1px solid #243043;border-radius:14px;padding:14px;box-shadow:0 10px 24px rgba(0,0,0,.35)}
    .pill{display:inline-flex;align-items:center;gap:8px;padding:7px 10px;border:1px solid rgba(255,255,255,.14);border-radius:999px;background:rgba(0,0,0,.25);font-size:12px;opacity:.9}
    .hr{height:1px;background:rgba(255,255,255,.12);margin:12px 0}
    .btns{display:flex;gap:10px;flex-wrap:wrap;margin-top:10px}
    button{padding:10px 12px;border-radius:12px;border:none;cursor:pointer;font-weight:800;background:#2563eb;color:#fff}
    button:hover{background:#1d4ed8}
    button.secondary{background:rgba(255,255,255,.10);border:1px solid rgba(255,255,255,.18)}
    button.secondary:hover{background:rgba(255,255,255,.14)}
    table{width:100%;border-collapse:collapse;margin-top:10px}
    th,td{border:1px solid #334155;padding:8px;text-align:center}
    th{background:#0b1220}
    tr.good{background:rgba(16,185,129,.18)}
    a{color:#93c5fd;text-decoration:none}
    a:hover{text-decoration:underline}
    .log{font-family:ui-monospace,SFMono-Regular,Menlo,monospace;font-size:12px;line-height:1.55;background:#0b1220;border:1px solid #243043;border-radius:12px;padding:10px;max-height:220px;overflow:auto;white-space:pre-wrap}
    .muted{opacity:.8}
  </style>
</head>
<body>
<div class="wrap">
  <h1>🏠 وسيط عقاري ذكي — تشغيل تلقائي</h1>
  <p class="sub">
    لا يوجد أي إدخال يدوي. النظام يعمل تلقائيًا:
    <br />
    <b>مصادر ثابتة:</b> haraj.com.sa + aqar.fm
    <br />
    <b>سلوك افتراضي:</b> يجلب <b>طلبات + عروض</b> في <b>الرياض</b> ويطابق بينها.
  </p>

  <div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap">
      <div class="pill">🔎 <span id="status">جاهز</span></div>
      <div class="pill">📦 <span id="counts">—</span></div>
    </div>

    <div class="btns">
      <button id="btnRun">🚀 تشغيل الآن</button>
      <button class="secondary" id="btnClear">🧹 مسح</button>
    </div>

    <div class="hr"></div>

    <div class="row" style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
      <div>
        <h3 style="margin:0 0 8px">الطلبات (Demands)</h3>
        <table>
          <thead><tr><th>النوع</th><th>المدينة</th><th>الحي</th><th>سعر/ميزانية</th><th>رابط</th></tr></thead>
          <tbody id="demands"></tbody>
        </table>
      </div>
      <div>
        <h3 style="margin:0 0 8px">العروض (Listings)</h3>
        <table>
          <thead><tr><th>النوع</th><th>المدينة</th><th>الحي</th><th>السعر</th><th>رابط</th></tr></thead>
          <tbody id="listings"></tbody>
        </table>
      </div>
    </div>

    <div class="hr"></div>

    <h3 style="margin:0 0 8px">المطابقات (Matches)</h3>
    <table>
      <thead><tr><th>Score</th><th>عرض</th><th>طلب</th><th>سبب</th></tr></thead>
      <tbody id="matches"></tbody>
    </table>

    <div class="hr"></div>
    <h3 style="margin:0 0 8px">سجل التنفيذ</h3>
    <div id="log" class="log"></div>
    <p class="muted" style="margin:10px 0 0;line-height:1.7">
      هذا الإصدار يستخدم <b>Google Custom Search JSON API</b> (للاختبار فقط).
      <br />
      للإنتاج: انقلي المفتاح للـ<b>Backend</b> ولا تتركيه في الواجهة.
    </p>
  </div>
</div>

<script>
  function $(id){ return document.getElementById(id); }

  var state = { items: [], demands: [], listings: [], matches: [] };

  var DEFAULT_QUERY = '((site:haraj.com.sa OR site:aqar.fm) (مطلوب OR أبحث OR ابحث OR للبيع OR للايجار OR للإيجار) (شقة OR أرض OR فيلا) الرياض)';
  var DEFAULT_LIMIT = 10;

  // 🔑 مفتاحك (يفضل تغييره/تدويره لاحقًا)
  var CSE_KEY = 'AIzaSyCRhVloKESDZRHDiOUCbYhZE4WjCSCjoY4';
  

  // ✅ CX الصحيح من صفحة Programmable Search Engine > Overview > Search engine ID
  var CSE_CX  = '548f34596a7d2445a';

  function nowTime(){
    try { return new Date().toLocaleTimeString('ar-SA'); }
    catch(_e){ return new Date().toLocaleTimeString(); }
  }

  function setStatus(s){ $('status').textContent = s; }

  function setCounts(){
    $('counts').textContent =
      'items=' + state.items.length +
      ' | demands=' + state.demands.length +
      ' | listings=' + state.listings.length +
      ' | matches=' + state.matches.length;
  }

  function log(msg){
    var el = $('log');
    var line = '[' + nowTime() + '] ' + msg;
    el.textContent = el.textContent ? (line + '\n' + el.textContent) : line;
  }

  function escapeHtml(s){
    var str = String(s === undefined || s === null ? '' : s);
    return str.replace(/[&<>"']/g, function(c){
      return ({ '&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;', "'":'&#39;' }[c]);
    });
  }

  function parseGoogleError(json){
    try{
      if (json && json.error && json.error.message) return json.error.message;
    }catch(_e){}
    return null;
  }

  function cseSearch(query, limit){
    if (!CSE_KEY){
      return Promise.reject(new Error('CSE key is not set'));
    }

    var url =
      'https://www.googleapis.com/customsearch/v1' +
      '?key=' + encodeURIComponent(CSE_KEY) +
      '&cx=' + encodeURIComponent(CSE_CX) +
      '&num=' + encodeURIComponent(String(Math.min(limit, 10))) +
      '&q=' + encodeURIComponent(query);

    return fetch(url).then(function(r){
      if (!r.ok){
        // حاول قراءة رسالة الخطأ التفصيلية من Google
        return r.json().then(function(errJson){
          var msg = parseGoogleError(errJson) || ('CSE HTTP ' + r.status);
          throw new Error(msg + ' (status=' + r.status + ')');
        }).catch(function(){
          throw new Error('CSE HTTP ' + r.status);
        });
      }
      return r.json();
    }).then(function(data){
      var items = (data && data.items) ? data.items : [];
      return items.map(function(x){
        return { title: x.title || '', snippet: x.snippet || '', link: x.link || '' };
      });
    });
  }

  function processItems(items){
    state.items = items || [];
    state.demands = [];
    state.listings = [];

    for (var i=0; i<state.items.length; i++){
      var it = state.items[i] || {};
      var text = (it.title || '') + ' ' + (it.snippet || '');
      var kind = text.indexOf('مطلوب') !== -1 ? 'demand' : 'listing';

      var row = {
        kind: kind,
        title: it.title || '',
        snippet: it.snippet || '',
        link: it.link || '',
        city: 'الرياض',
        district: null,
        price: null
      };

      if (kind === 'demand') state.demands.push(row);
      else state.listings.push(row);
    }

    state.matches = [];
    for (var a=0; a<state.listings.length; a++){
      for (var b=0; b<state.demands.length; b++){
        state.matches.push({
          score: 50,
          listing: state.listings[a],
          demand: state.demands[b],
          reason: 'مطابقة مبدئية'
        });
      }
    }
  }

  function linkCell(url){
    if (!url) return '—';
    var safe = escapeHtml(url);
    return '<a href="' + safe + '" target="_blank" rel="noopener">رابط</a>';
  }

  function renderTables(){
    var dT = $('demands');
    var lT = $('listings');
    var mT = $('matches');

    dT.innerHTML = '';
    lT.innerHTML = '';
    mT.innerHTML = '';

    if (!state.demands.length) dT.innerHTML = '<tr><td colspan="5">—</td></tr>';
    if (!state.listings.length) lT.innerHTML = '<tr><td colspan="5">—</td></tr>';
    if (!state.matches.length) mT.innerHTML = '<tr><td colspan="4">—</td></tr>';

    state.demands.forEach(function(d){
      dT.innerHTML += '<tr><td>طلب</td><td>'+escapeHtml(d.city)+'</td><td>—</td><td>—</td><td>'+linkCell(d.link)+'</td></tr>';
    });

    state.listings.forEach(function(l){
      lT.innerHTML += '<tr><td>عرض</td><td>'+escapeHtml(l.city)+'</td><td>—</td><td>—</td><td>'+linkCell(l.link)+'</td></tr>';
    });

    state.matches.forEach(function(m){
      mT.innerHTML += '<tr><td>'+m.score+'</td><td>'+escapeHtml(m.listing.title)+'</td><td>'+escapeHtml(m.demand.title)+'</td><td>'+escapeHtml(m.reason)+'</td></tr>';
    });

    setCounts();
  }

  function run(){
    setStatus('جاري التنفيذ…');
    log('Run: ' + DEFAULT_QUERY);
    log('Using cx=' + CSE_CX);

    cseSearch(DEFAULT_QUERY, DEFAULT_LIMIT)
      .then(function(items){
        log('Fetched: ' + items.length);
        processItems(items);
        renderTables();
        setStatus('تم');
      })
      .catch(function(e){
        setStatus('خطأ');
        log('ERROR: ' + (e && e.message ? e.message : e));

        log('حلول سريعة لو بقي 403:');
        log('1) تأكدي Custom Search API Enabled في نفس مشروع المفتاح.');
        log('2) مؤقتًا: API Key > Application restrictions = None.');
        log('3) جرّبي الرابط مباشرة: https://www.googleapis.com/customsearch/v1?key=YOUR_KEY&cx=' + CSE_CX + '&q=شقة الرياض');
      });
  }

  $('btnRun').addEventListener('click', run);
  $('btnClear').addEventListener('click', function(){
    state.items = []; state.demands = []; state.listings = []; state.matches = [];
    $('log').textContent = '';
    renderTables();
    setStatus('تم المسح');
  });

  setCounts();
  renderTables();
  log('تشغيل تلقائي…');
  run();
</script>
</body>
</html>
