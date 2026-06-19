/*
0556687131
  سكربت مبسّط ومنظّم لصفحة إنشاء المنتج (Facility Product Create)
  الهدف: تسهيل الصيانة ووضع التعليقات العربية لتوضيح المهام.
  يتضمن:
  - إدارة الترجمات: إظهار الحقول للغة الجديدة ونسخ العنوان/الوصف من الأساس.
  - الموقع والخريطة: خريطة مصغّرة، استخراج/مسح/نسخ/بناء رابط، استخدام موقعي، مودال اختيار على الخريطة، سلاسل مدينة→حي→شارع.
  - الوسائط: معرض صور بسيط ومعاينة فيديو.
  - المساعد الصوتي: استماع مستمر، تحليل عربي للنص، تعبئة تلقائية وإبراز وتراجع.
*/
(function(){
  const cfg = window.ProductCreateConfig || { strings:{}, endpoints:{}, dict:{}, flags:{} };
  const t = (k,d)=> (cfg.strings && cfg.strings[k]) || d || k;

  // Toast
  function toast(msg,type){ const el=document.createElement('div'); el.className=`fixed top-4 left-1/2 -translate-x-1/2 z-[9999] px-3 py-2 rounded-md text-white text-sm shadow ${type==='error'?'bg-red-600':type==='success'?'bg-emerald-600':'bg-gray-800'}`; el.textContent=msg; document.body.appendChild(el); setTimeout(()=>el.remove(),2000); }

  document.addEventListener('DOMContentLoaded',()=>{
    // =========================================================
    // الترجمات: عند اختيار لغة جديدة، نُظهر الحقول وننسخ من الترجمة الأساسية
    // =========================================================
    (function(){
      const c = document.getElementById('translations-repeater');
      const addBtn = document.getElementById('add-translation');
      if (!c) return;

      function getLocaleSelects(){
        return Array.from(c.querySelectorAll('select[name$="[locale]"]'));
      }

      function getSelectedLocales(){
        return getLocaleSelects().map(s => (s.value || '').trim()).filter(Boolean);
      }

      function syncLocaleOptions(){
        const selected = getSelectedLocales();
        const selects = getLocaleSelects();

        selects.forEach(sel => {
          const self = (sel.value || '').trim();
          Array.from(sel.options).forEach(opt => {
            if (!opt.value) return;
            opt.disabled = selected.includes(opt.value) && opt.value !== self;
          });
        });
      }

      function ensureNoDuplicate(changedSelect){
        const val = (changedSelect.value || '').trim();
        if (!val) return true;

        const others = getLocaleSelects().filter(s => s !== changedSelect);
        if (others.some(s => (s.value || '').trim() === val)) {
          changedSelect.value = '';
          syncLocaleOptions();
          toast(t('duplicate_locale', 'هذه اللغة مستخدمة بالفعل، اختر لغة أخرى'), 'error');
          return false;
        }

        return true;
      }

      function refreshRemoveButtons(){
        const items = Array.from(c.querySelectorAll('.translation-item'));
        items.forEach((item, idx) => {
          const btn = item.querySelector('.remove-translation');
          if (!btn) return;
          btn.classList.toggle('hidden', idx === 0);
        });
      }

      function renumberItems(){
        const items = Array.from(c.querySelectorAll('.translation-item'));
        items.forEach((item, idx) => {
          item.setAttribute('data-index', String(idx));
          item.querySelectorAll('input, textarea, select').forEach(el => {
            const name = el.getAttribute('name');
            if (!name) return;
            const newName = name.replace(/translations\[\d+\]/g, `translations[${idx}]`);
            el.setAttribute('name', newName);
          });
        });
      }

      function addTranslation(){
        const items = Array.from(c.querySelectorAll('.translation-item'));
        const last = items[items.length - 1];
        if (!last) return;

        const clone = last.cloneNode(true);
        c.appendChild(clone);

        renumberItems();
        refreshRemoveButtons();

        clone.querySelectorAll('input[type="text"], input[type="number"], input[type="url"], textarea').forEach(el => {
          el.value = '';
        });
        clone.querySelectorAll('select').forEach(sel => {
          sel.value = '';
        });

        syncLocaleOptions();
      }

      c.addEventListener('change', e => {
        if (!e.target.matches('select[name$="[locale]"]')) return;
        const ok = ensureNoDuplicate(e.target);
        if (!ok) return;

        const item = e.target.closest('.translation-item');
        if (!item) return;

        const baseTitle = document.querySelector('input[name="translations[0][title]"]')?.value || '';
        const baseDesc  = document.querySelector('textarea[name="translations[0][description]"]')?.value || '';
        const ti = item.querySelector('input[name$="[title]"]');
        const di = item.querySelector('textarea[name$="[description]"]');
        if (ti && !ti.value && baseTitle) ti.value = baseTitle;
        if (di && !di.value && baseDesc) di.value = baseDesc;

        syncLocaleOptions();
      });

      c.addEventListener('click', e => {
        const btn = e.target.closest ? e.target.closest('.remove-translation') : null;
        if (!btn) return;
        const item = btn.closest('.translation-item');
        if (!item) return;

        const items = Array.from(c.querySelectorAll('.translation-item'));
        if (items.length <= 1) return;

        item.remove();
        renumberItems();
        refreshRemoveButtons();
        syncLocaleOptions();
      });

      addBtn && addBtn.addEventListener('click', () => {
        addTranslation();
      });

      // Initial
      renumberItems();
      refreshRemoveButtons();
      syncLocaleOptions();
    })();

    // =========================================================
    // الموقع والخريطة: خريطة مصغّرة + أدوات الإحداثيات + اختيار متسلسل
    // =========================================================
    (function(){
      const lat=document.getElementById('latitude')||document.querySelector('input[name="latitude"]');
      const lng=document.getElementById('longitude')||document.querySelector('input[name="longitude"]');
      const url=document.getElementById('google_maps_url')||document.querySelector('input[name="google_maps_url"]');
      const btnExtract=document.getElementById('extract-coordinates');
      const btnClear=document.getElementById('clear-coordinates');
      const btnCopy=document.getElementById('copy-coordinates');
      const btnBuild=document.getElementById('build-maps-url');
      const btnUseMyLoc=document.getElementById('use-my-location');
      const citySel=document.getElementById('city_id')||document.querySelector('select[name="city_id"]');
      const hoodSel=document.getElementById('neighborhood_id')||document.querySelector('select[name="neighborhood_id"]');
      const streetSel=document.getElementById('street_id')||document.querySelector('select[name="street_id"]');

      function latlng(){ const a=parseFloat(lat?.value), b=parseFloat(lng?.value); return (!isNaN(a)&&!isNaN(b))?{lat:a,lng:b}:{lat:24.7136,lng:46.6753}; }
      // إنشاء/تحديث الخريطة المصغّرة بناءً على الإحداثيات الحالية
      let miniMap, miniMarker; function ensureMini(){ const el=document.getElementById('mini-map'); if (!el) return; if (!miniMap){ const p=latlng(); miniMap=L.map('mini-map',{zoomControl:false,attributionControl:false}).setView([p.lat,p.lng],13); L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{maxZoom:19}).addTo(miniMap); miniMarker=L.marker([p.lat,p.lng]).addTo(miniMap); setTimeout(()=>miniMap.invalidateSize(),0);} else setTimeout(()=>miniMap.invalidateSize(),0);} 
      function refreshMini(){ if (!miniMap||!miniMarker) return; const p=latlng(); if (p.lat<-90||p.lat>90||p.lng<-180||p.lng>180){ toast(t('invalid_coordinates','Invalid coordinates'),'error'); return; } miniMarker.setLatLng([p.lat,p.lng]); miniMap.setView([p.lat,p.lng]); }
      ensureMini(); refreshMini();
      lat&&lat.addEventListener('input',refreshMini); lng&&lng.addEventListener('input',refreshMini);
      // استخراج إحداثيات من رابط خرائط جوجل بعدة صيغ (@lat,lng أو q= أو ll=)
      function parseFromMaps(u){ if(!u) return null; try{ const x=new URL(u); const at=x.pathname.match(/@(-?\d+\.\d+),(-?\d+\.\d+)/); if(at) return {lat:parseFloat(at[1]),lng:parseFloat(at[2])}; const q=x.searchParams.get('q'); if(q){ const p=q.split(',').map(s=>parseFloat(s.trim())); if(p.length>=2&&!isNaN(p[0])&&!isNaN(p[1])) return {lat:p[0],lng:p[1]}; } const ll=x.searchParams.get('ll'); if(ll){ const p=ll.split(',').map(s=>parseFloat(s.trim())); if(p.length>=2&&!isNaN(p[0])&&!isNaN(p[1])) return {lat:p[0],lng:p[1]}; } }catch(e){} return null; }
      btnExtract&&btnExtract.addEventListener('click',()=>{ const c=parseFromMaps(url?.value||''); if(c&&lat&&lng){ lat.value=c.lat.toFixed(6); lng.value=c.lng.toFixed(6); refreshMini(); toast('OK','success'); }});
      btnClear&&btnClear.addEventListener('click',()=>{ if(lat) lat.value=''; if(lng) lng.value=''; refreshMini(); toast(t('cleared','Cleared'),'success'); });
      btnCopy&&btnCopy.addEventListener('click',async()=>{ const p=latlng(); try{ await navigator.clipboard.writeText(`${p.lat.toFixed(6)},${p.lng.toFixed(6)}`); toast(t('copied_to_clipboard','Copied'),'success'); }catch(_){}});
      btnBuild&&btnBuild.addEventListener('click',()=>{ const p=latlng(); if(url) url.value=`https://www.google.com/maps?q=${p.lat},${p.lng}`; });
      btnUseMyLoc&&btnUseMyLoc.addEventListener('click',()=>{ if(!navigator.geolocation){ toast(t('use_my_location_error','Location not supported'),'error'); return;} navigator.geolocation.getCurrentPosition((pos)=>{ if(lat) lat.value=pos.coords.latitude.toFixed(6); if(lng) lng.value=pos.coords.longitude.toFixed(6); refreshMini(); },()=>toast(t('use_my_location_error','Failed to get location'),'error'),{enableHighAccuracy:true,timeout:8000}); });

      // Cascading selects
      // تنظيف وإعادة تهيئة قائمة الاختيار مع عنصر افتراضي
      function clearSelect(sel,ph){ if(!sel) return; sel.innerHTML=''; const o=document.createElement('option'); o.value=''; o.textContent=ph||''; sel.appendChild(o); }
      async function loadHoods(cityId){ if(!hoodSel) return; clearSelect(hoodSel,t('select_neighborhood')); clearSelect(streetSel,t('select_street')); if(!cityId) return; try{ const r=await fetch(`${cfg.endpoints.neighborhoods}?city_id=${encodeURIComponent(cityId)}`); const j=await r.json(); (j.data||[]).forEach(d=>{ const o=document.createElement('option'); o.value=d.id; o.textContent=d.name; hoodSel.appendChild(o); }); }catch(_){ toast(t('failed_to_load','Failed to load'),'error'); } }
      async function loadStreets(hoodId){ if(!streetSel) return; clearSelect(streetSel,t('select_street')); if(!hoodId) return; try{ const r=await fetch(`${cfg.endpoints.streets}?neighborhood_id=${encodeURIComponent(hoodId)}`); const j=await r.json(); (j.data||[]).forEach(d=>{ const o=document.createElement('option'); o.value=d.id; o.textContent=d.name; streetSel.appendChild(o); }); }catch(_){ toast(t('failed_to_load','Failed to load'),'error'); } }
      citySel&&citySel.addEventListener('change',()=>loadHoods(citySel.value));
      hoodSel&&hoodSel.addEventListener('change',()=>loadStreets(hoodSel.value));

      // Map picker modal
      // مودال اختيار الإحداثيات من الخريطة الكبيرة
      const modal=document.getElementById('map-picker-modal'); const openBtn=document.getElementById('open-map-picker'); const closeBtn=document.getElementById('close-map-picker'); const cancelBtn=document.getElementById('cancel-map-picker'); const applyBtn=document.getElementById('apply-map-picker');
      let pickerMap, pickerMarker, picked=null; function initPicker(){ const el=document.getElementById('map-container'); if(!el) return; const p=latlng(); pickerMap=L.map('map-container').setView([p.lat,p.lng],12); L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{maxZoom:19,attribution:'&copy; OpenStreetMap'}).addTo(pickerMap); pickerMap.on('click',e=>{ picked=e.latlng; if(pickerMarker) pickerMarker.setLatLng(picked); else pickerMarker=L.marker(picked).addTo(pickerMap); }); }
      function open(){ if(!modal) return; modal.classList.remove('hidden'); setTimeout(()=>{ if(!pickerMap) initPicker(); else pickerMap.invalidateSize(); },0); }
      function close(){ modal&&modal.classList.add('hidden'); }
      function apply(){ if(picked&&lat&&lng){ lat.value=picked.lat.toFixed(6); lng.value=picked.lng.toFixed(6); refreshMini(); } }
      openBtn&&openBtn.addEventListener('click',open); closeBtn&&closeBtn.addEventListener('click',close); cancelBtn&&cancelBtn.addEventListener('click',close); applyBtn&&applyBtn.addEventListener('click',()=>{ apply(); close(); });
    })();

    // =========================================================
    // الوسائط: معرض صور بسيط + معاينة فيديو
    // =========================================================
    (function(){
      const grid=document.getElementById('gallery-grid'); const addBtn=document.getElementById('add-gallery-image'); const pasteBtn=document.getElementById('paste-multiple-images'); const urlInp=document.getElementById('gallery-image-url'); const hidden=document.getElementById('image_gallery'); if(!grid||!hidden) return; let items=[];
      const sync=()=> hidden.value=JSON.stringify(items);
      // عرض العناصر الحالية في المعرض مع زر حذف لكل صورة
      const render=()=>{ grid.innerHTML=''; if(!items.length){ const d=document.createElement('div'); d.className='col-span-full text-center text-sm text-gray-400 py-6 border border-dashed border-gray-200 rounded-md'; d.textContent=t('empty_gallery','No images'); grid.appendChild(d); return;} items.forEach((src,i)=>{ const card=document.createElement('div'); card.className='relative group border border-gray-200 rounded-md overflow-hidden bg-white'; const img=document.createElement('img'); img.src=src; img.className='w-full h-28 object-cover'; card.appendChild(img); const rm=document.createElement('button'); rm.type='button'; rm.className='absolute top-1 right-1 bg-white/90 border border-red-200 text-red-600 text-xs px-2 py-1 rounded-md opacity-0 group-hover:opacity-100'; rm.textContent=t('remove_image','Remove'); rm.onclick=()=>{ items.splice(i,1); render(); sync(); }; card.appendChild(rm); grid.appendChild(card); }); };
      function add(u){ const v=(u||'').trim(); if(!v) return; items.push(v); render(); sync(); }
      try{ const init=hidden.value?JSON.parse(hidden.value):[]; if(Array.isArray(init)) items=init.filter(Boolean).map(String);}catch(_){}
      render(); sync();
      addBtn&&addBtn.addEventListener('click',()=>{ add(urlInp&&urlInp.value); if(urlInp) urlInp.value=''; toast('OK','success'); });
      urlInp&&urlInp.addEventListener('keydown',e=>{ if(e.key==='Enter'){ e.preventDefault(); add(urlInp.value); urlInp.value=''; }});
      // لصق عدة روابط صور من الحافظة دفعة واحدة
      pasteBtn&&pasteBtn.addEventListener('click', async()=>{ let text=''; try{ text=await navigator.clipboard.readText(); }catch(_){} if(!text) return; text.split(/\r?\n|\s+/).map(s=>s.trim()).filter(Boolean).forEach(add); });
      const vInp=document.querySelector('input[name="video"]'); const vWrap=document.getElementById('video-preview'); const vBox=document.getElementById('video-preview-container'); function renderVideo(u){ if(!vBox) return; vBox.innerHTML=''; const s=(u||'').trim(); if(!s){ vWrap&&vWrap.classList.add('hidden'); return;} const yt=s.match(/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([\w-]{11})/); if(yt){ const ifr=document.createElement('iframe'); ifr.src=`https://www.youtube.com/embed/${yt[1]}`; ifr.className='w-full h-full'; vBox.appendChild(ifr); vWrap&&vWrap.classList.remove('hidden'); return;} const vm=s.match(/vimeo\.com\/(\d+)/); if(vm){ const ifr=document.createElement('iframe'); ifr.src=`https://player.vimeo.com/video/${vm[1]}`; ifr.className='w-full h-full'; vBox.appendChild(ifr); vWrap&&vWrap.classList.remove('hidden'); return;} if(/\.(mp4|webm|ogg)(\?.*)?$/i.test(s)){ const v=document.createElement('video'); v.src=s; v.controls=true; v.className='w-full h-full'; vBox.appendChild(v); vWrap&&vWrap.classList.remove('hidden'); return;} const a=document.createElement('a'); a.href=s; a.target='_blank'; a.textContent=s; vBox.appendChild(a); vWrap&&vWrap.classList.remove('hidden'); }
      if(vInp){ renderVideo(vInp.value); vInp.addEventListener('input',()=>renderVideo(vInp.value)); }
    })();

    // =========================================================
    // المساعد الصوتي: استماع مستمر + تحليل نص عربي + تعبئة الحقول
    // =========================================================
    (function(){
      const btnStart=document.getElementById('voice-start'); const btnStop=document.getElementById('voice-stop'); const status=document.getElementById('voice-status'); const transcript=document.getElementById('voice-transcript'); const btnAnalyze=document.getElementById('voice-analyze'); const btnClear=document.getElementById('voice-clear'); const btnUndo=document.getElementById('voice-undo');
      if(!btnStart || !transcript) return;
      const setStatus=(m)=>{ if(status) status.textContent=m||''; };
      let rec=null, listening=false, wantStop=false; let lastSnapshot=null;
      // فحص دعم واجهة الإملاء الصوتي في المتصفح
      function supported(){ return ('webkitSpeechRecognition' in window)||('SpeechRecognition' in window); }
      // إنشاء الكائن المسؤول عن الاستماع المستمر باللغة العربية السعودية
      function makeRec(){ const C=window.SpeechRecognition||window.webkitSpeechRecognition; if(!C) return null; const r=new C(); r.lang='ar-SA'; r.interimResults=true; r.continuous=true; if('maxAlternatives' in r) r.maxAlternatives=1; return r; }
      function start(){ if(!supported()){ toast('لا يدعم المتصفح الإملاء','error'); return;} if(listening) return; rec=makeRec(); if(!rec){ toast('لا يدعم المتصفح الإملاء','error'); return;} listening=true; wantStop=false; setStatus(t('listening','Listening...')); rec.onresult=(e)=>{ let final=''; for(let i=e.resultIndex;i<e.results.length;i++){ const rs=e.results[i]; if(rs.isFinal) final += rs[0].transcript + ' '; } if(final){ transcript.value = (transcript.value? transcript.value+' ': '') + final.trim(); setStatus(t('converted','Converted')); } }; rec.onerror=(ev)=>{ if(ev?.error!=='no-speech') toast('حدث خطأ أثناء الاستماع','error'); }; rec.onend=()=>{ if(wantStop){ listening=false; btnStart.disabled=false; btnStop.disabled=true; setStatus(''); if((transcript.value||'').trim()) btnAnalyze&&btnAnalyze.click(); } else if(listening){ try{ rec.start(); }catch(_){ listening=false; } } }; rec.start(); btnStart.disabled=true; btnStop.disabled=false; }
      function stop(){ wantStop=true; try{ rec&&rec.stop(); }catch(_){} }
      btnStart.addEventListener('click',start); btnStop&&btnStop.addEventListener('click',stop);

      // توابع مساعدة للتحليل: تطبيع عربي، أرقام، أفضل مطابقة
      const strip=(s)=> (s||'').replace(/[\u0610-\u061A\u064B-\u065F\u0670\u06D6-\u06ED]/g,'');
      const norm=(s)=> strip(s).replace(/[إأآ]/g,'ا').replace(/ى/g,'ي').replace(/ؤ/g,'و').replace(/ئ/g,'ي').toLowerCase();
      const normDigits=(s)=> strip(s).replace(/[٠-٩]/g, d=>'٠١٢٣٤٥٦٧٨٩'.indexOf(d));
      // تحويل الكلمات العددية العربية لأرقام (مدى صغير شائع)
      function numFromWords(word){ const m={ 'صفر':0,'واحد':1,'واحدة':1,'اثنين':2,'اثنتين':2,'غرفتين':2,'ثلاث':3,'ثلاثة':3,'أربع':4,'اربعة':4,'خمس':5,'خمسة':5,'ست':6,'ستة':6,'سبع':7,'سبعة':7,'ثمان':8,'ثمانية':8,'تسع':9,'تسعة':9,'عشر':10,'عشرة':10 }; return m[word]; }
      // استخراج أول نمط عددي من النص (أرقام أو كلمات)
      function firstNumberLike(s){ const m1=s.match(/(?:(?:\d+[\.,]?)+)/); if(m1) return parseFloat(m1[0].replace(/\./g,'').replace(/,/g,'')); const m2=s.match(/(?:صفر|واحد(?:ة)?|اثنين|اثنتين|غرفتين|ثلاث(?:ة)?|أربع(?:ة)?|اربعة|خمس(?:ة)?|ست(?:ة)?|سبع(?:ة)?|ثمان(?:ية)?|تسع(?:ة)?|عشر(?:ة)?)/); if(m2) return numFromWords(m2[0]); return null; }
      // تحويل نص إلى رقم مع دعم مضاعِفات شائعة (ألف/مليون/نص مليون...)
      function parseNumber(s){ const raw=normDigits(s||''); let base=firstNumberLike(raw); if(base==null) return null; let mult=1; if(/\bنص\s*مليون\b/.test(raw)){ base=0.5; mult=1_000_000; } else if(/\bمليونين\b|\bمليونان\b/.test(raw)){ base=2; mult=1_000_000; } else if(/\bمليون\b|\bملايين\b/.test(raw)){ mult=1_000_000; } else if(/\bألفين\b|\bالفين\b/.test(raw)){ base=2; mult=1000; } else if(/\bألف\b|\bالف\b|\bآلاف\b/.test(raw)){ mult=1000; } const n=Math.round(base*mult); return isNaN(n)?null:n; }
      // مطابقة تقريبية بين نص و قائمة أسماء (تطبيع عربي + مقارنة طول)
      function bestMatch(target,list){ if(!target||!Array.isArray(list)) return null; const t=norm(target); let best=null,score=0; list.forEach(name=>{ const n=norm(name||''); if(!n) return; if(n===t){ best=name; score=100; return;} if(t.includes(n)&&n.length>score){ best=name; score=n.length;} else if(n.includes(t)&&t.length>score){ best=name; score=t.length;} }); return best; }
      // بناء عنوان مختصر (2-3 كلمات): النوع + الغرف + (الحي/المدينة)
      function buildShortTitle(d){ const arr=[]; if(d.type) arr.push(d.type); if(d.rooms) arr.push(String(d.rooms)+' غرف'); if(d.neighborhood) arr.push(d.neighborhood); else if(d.city) arr.push(d.city); return arr.slice(0,3).join(' '); }
      // تحليل نص عربي لاستخراج الحقول المطلوبة (سعر/غرف/حمامات/مساحة/دور/مواقف/مدينة/حي/نوع)
      function analyze(text){ const out={}; const s=normDigits((text||'').trim()); const mPrice=s.match(/(?:بسعر|السعر|بـ|ب|بحوالي|قيمته|قيمتها)\s*([\d.,\s\p{L}]+?)(?:\s*(?:ريال|رس|sar))?/u); if(mPrice) out.price=parseNumber(mPrice[1]); const mRooms=s.match(/([\d\p{L}]+)\s*(?:غرف|غرفة|غرفه|نوم)/u); if(mRooms){ const n=parseNumber(mRooms[1]); if(n!=null) out.rooms=n; } const mBath=s.match(/([\d\p{L}]+)\s*(?:حمام|حمامين|حمامات|دورات?\s*المياه)/u); if(mBath){ const n=parseNumber(mBath[1]); if(n!=null) out.bathrooms=n; } const mArea=s.match(/([\d.,\s\p{L}]+)\s*(?:متر\s*مربع|متر|م²)/u); if(mArea){ const n=parseNumber(mArea[1]); if(n!=null) out.area=n; } const mFloor=s.match(/(?:الدور|الطابق)\s*([\d\p{L}]+)/u); if(mFloor){ const n=parseNumber(mFloor[1]); if(n!=null) out.floor_number=n; } const mPark=s.match(/([\d\p{L}]+)\s*(?:موقف|مواقف)/u); if(mPark){ const n=parseNumber(mPark[1]); if(n!=null) out.parking_spaces=n; } const mCity=s.match(/(?:بال|في)\s*([\p{L}]+?)(?:\s|$)/u); if(mCity){ const raw=mCity[1]; const dict=Array.isArray(cfg.dict?.cities)?cfg.dict.cities:[]; out.city=bestMatch(raw,dict)||raw; } const mHood=s.match(/(?:حي|حى)\s*([\p{L}]+)(?:\s|$)/u); if(mHood) out.neighborhood=mHood[1]; const types=['شقة','فيلا','أرض','استوديو','محل','عمارة','دوبلكس','تاونهاوس','بيت','شاليه']; const ft=types.find(w=> s.includes(w)); if(ft) out.type=ft; out.title=buildShortTitle(out) || s; return out; }

      // تعبئة الحقول من نتيجة التحليل + إبراز سريع
      const flash=(el)=>{ if(!el) return; el.classList.add('ring-2','ring-emerald-400'); setTimeout(()=>el.classList.remove('ring-2','ring-emerald-400'),900); };
      function getVal(sel){ const el=document.querySelector(sel); return el?el.value:undefined; }
      async function fill(data){ if(!data) return; lastSnapshot={ title:getVal('input[name="translations[0][title]"]'), price:getVal('input[name="price"]'), bathrooms:getVal('input[name="bathrooms"]'), area:getVal('input[name="area"]'), floor:getVal('input[name="floor_number"]'), parking:getVal('input[name="parking_spaces"]'), address:getVal('input[name="address"]'), city:getVal('select[name="city_id"]'), hood:getVal('select[name="neighborhood_id"]'), cat:getVal('select[name="category_id"]') };
        const set=(sel,val)=>{ const el=document.querySelector(sel); if(el && val!=null && val!==''){ el.value=val; flash(el);} };
        set('input[name="translations[0][title]"]', data.title);
        set('input[name="price"]', data.price);
        set('input[name="bathrooms"]', data.bathrooms);
        set('input[name="area"]', data.area);
        set('input[name="floor_number"]', data.floor_number);
        set('input[name="parking_spaces"]', data.parking_spaces);
        const citySel=document.getElementById('city_id')||document.querySelector('select[name="city_id"]'); const hoodSel=document.getElementById('neighborhood_id')||document.querySelector('select[name="neighborhood_id"]');
        // اختيار خيار قائم على مطابقة نصية تقريبية للاسم الظاهر
        function tryOptionByText(sel,label){ if(!sel||!label) return false; const target=norm(label); let val=null,score=0; Array.from(sel.options).forEach(o=>{ const tx=norm(o.textContent||''); if(!tx) return; if(tx===target){ val=o.value; score=100; return;} if(target.includes(tx)&&tx.length>score){ val=o.value; score=tx.length;} else if(tx.includes(target)&&target.length>score){ val=o.value; score=target.length;} }); if(val!=null){ sel.value=val; sel.dispatchEvent(new Event('change')); return true;} return false; }
        if(citySel && data.city){ const ok=tryOptionByText(citySel,data.city); if(ok){ // load neighborhoods and try match
            const r=await fetch(`${cfg.endpoints.neighborhoods}?city_id=${encodeURIComponent(citySel.value)}`); const j=await r.json(); if(hoodSel){ hoodSel.innerHTML='<option value="">'+t('select_neighborhood')+'</option>'; (j.data||[]).forEach(d=>{ const o=document.createElement('option'); o.value=d.id; o.textContent=d.name; hoodSel.appendChild(o); }); if(data.neighborhood) tryOptionByText(hoodSel,data.neighborhood); }
        }} else if(hoodSel && data.neighborhood){ tryOptionByText(hoodSel,data.neighborhood); }
        const catSel=document.getElementById('category_id')||document.querySelector('select[name="category_id"]'); if(catSel && data.type) tryOptionByText(catSel,data.type);
        const addr=document.querySelector('input[name="address"]'); if(addr){ const parts=[data.city,data.neighborhood].filter(Boolean).join('، '); if(parts) addr.value = parts + (addr.value? (' - '+addr.value):''); }
        toast(t('filled_fields','Filled'),'success'); if(data.price==null) toast(t('could_not_parse_price','Could not parse price'),'error');
      }

      btnAnalyze&&btnAnalyze.addEventListener('click',()=>{ const txt=transcript.value||''; if(!txt.trim()) return; const st=document.getElementById('voice-analyze-status'); if(st) st.textContent=t('analyzing','Analyzing...'); const out=analyze(txt); fill(out); if(st) st.textContent=''; });
      btnClear&&btnClear.addEventListener('click',()=>{ transcript.value=''; toast(t('cleared','Cleared'),'success'); });
      btnUndo&&btnUndo.addEventListener('click',()=>{ if(!lastSnapshot) return toast(t('undone','Undone'),'info'); const set=(sel,val)=>{ const el=document.querySelector(sel); if(el!=null) el.value=val||''; }; set('input[name="translations[0][title]"]',lastSnapshot.title); set('input[name="price"]',lastSnapshot.price); set('input[name="bathrooms"]',lastSnapshot.bathrooms); set('input[name="area"]',lastSnapshot.area); set('input[name="floor_number"]',lastSnapshot.floor); set('input[name="parking_spaces"]',lastSnapshot.parking); set('input[name="address"]',lastSnapshot.address); const c=document.querySelector('select[name="city_id"]'); if(c){ c.value=lastSnapshot.city||''; c.dispatchEvent(new Event('change')); } const h=document.querySelector('select[name="neighborhood_id"]'); if(h) h.value=lastSnapshot.hood||''; const cat=document.querySelector('select[name="category_id"]'); if(cat) cat.value=lastSnapshot.cat||''; lastSnapshot=null; toast(t('undone','Undone'),'success'); });
    })();
  });
})();
                                                                                                            