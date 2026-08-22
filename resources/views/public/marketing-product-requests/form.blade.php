@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-indigo-50 dark:from-slate-900 dark:via-slate-950 dark:to-slate-900 py-12 px-4">
    <div class="max-w-4xl mx-auto">
        <!-- Hero -->
        <div class="text-center mb-10">
            <h1 class="text-3xl md:text-5xl font-extrabold text-gray-900 dark:text-white mb-3">
                سوق عقارك معنا
            </h1>
            <p class="text-lg text-gray-600 dark:text-gray-300">
                اكتب أو تكلم بصوتك، وسنساعدك في تسويق عقارك والوصول للعملاء.
            </p>
        </div>

        <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/20 p-6 md:p-10">
            <!-- Voice controls -->
            <div class="flex flex-wrap items-center gap-4 mb-6">
                <button id="voice-start" type="button" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-xl shadow transition">
                    <span class="text-xl">🎙️</span> ابدأ التسجيل
                </button>
                <button id="voice-stop" type="button" disabled class="inline-flex items-center gap-2 bg-gray-200 dark:bg-slate-700 text-gray-500 font-semibold py-3 px-6 rounded-xl cursor-not-allowed">
                    ■ أوقف
                </button>
                <span id="voice-status" class="text-sm text-gray-500 dark:text-gray-400"></span>
            </div>

            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">وصف العقار المراد تسويقه</label>
            <textarea id="description" rows="4" class="w-full p-4 rounded-2xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none" placeholder="مثال: عندي شقة 3 غرف في شمال الرياض أبي أأجرها، أو أرض استثمارية في جدة أبي أبيعها..."></textarea>

            <!-- Extracted preview -->
            <div id="extracted-preview" class="hidden mb-8">
                <h3 class="font-bold text-gray-800 dark:text-white mb-3">المعلومات المستخرجة</h3>
                <div id="extracted-chips" class="flex flex-wrap gap-2"></div>
            </div>

            <!-- Contact fields -->
            <div class="grid md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">الاسم *</label>
                    <input id="name" type="text" required class="w-full p-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">رقم الجوال *</label>
                    <input id="phone" type="tel" required class="w-full p-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <button id="submit-btn" type="button" class="w-full bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-bold py-4 rounded-2xl shadow-lg transition transform hover:-translate-y-1">
                أرسل طلب التسويق
            </button>

            <!-- Results -->
            <div id="results" class="hidden mt-10">
                <div class="p-4 rounded-2xl bg-indigo-50 dark:bg-indigo-900/20 text-indigo-800 dark:text-indigo-200 mb-6" id="result-message"></div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">عقارات مشابهة</h3>
                <div id="matches-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5"></div>
            </div>
        </div>
    </div>
</div>

<script>
    const storeUrl = "{{ route('public.marketing-product-requests.store') }}";
    const csrf = "{{ csrf_token() }}";

    const description = document.getElementById('description');
    const voiceStart = document.getElementById('voice-start');
    const voiceStop = document.getElementById('voice-stop');
    const voiceStatus = document.getElementById('voice-status');
    const submitBtn = document.getElementById('submit-btn');
    const results = document.getElementById('results');
    const resultMessage = document.getElementById('result-message');
    const matchesGrid = document.getElementById('matches-grid');
    const extractedPreview = document.getElementById('extracted-preview');
    const extractedChips = document.getElementById('extracted-chips');

    let extracted = {};
    let rec = null, listening = false;

    function supportsVoice() { return 'webkitSpeechRecognition' in window || 'SpeechRecognition' in window; }

    function makeRec() {
        const C = window.SpeechRecognition || window.webkitSpeechRecognition;
        if (!C) return null;
        const r = new C();
        r.lang = 'ar-SA';
        r.interimResults = true;
        r.continuous = true;
        return r;
    }

    if (supportsVoice()) {
        voiceStart.addEventListener('click', () => {
            if (listening) return;
            rec = makeRec();
            if (!rec) return;
            rec.onresult = (e) => {
                const transcript = Array.from(e.results).map(r => r[0].transcript).join('');
                description.value = transcript;
            };
            rec.onerror = () => { voiceStatus.textContent = 'حدث خطأ في المايك'; stopVoice(); };
            rec.onend = () => { listening = false; voiceStart.disabled = false; voiceStatus.textContent = ''; voiceStop.disabled = true; };
            rec.start();
            listening = true;
            voiceStart.disabled = true;
            voiceStop.disabled = false;
            voiceStatus.textContent = 'جاري الاستماع...';
        });

        voiceStop.addEventListener('click', stopVoice);
    } else {
        voiceStart.disabled = true;
        voiceStart.textContent = 'المتصفح لا يدعم الصوت';
    }

    function stopVoice() {
        try { rec && rec.stop(); } catch (e) {}
    }

    function renderExtracted(obj) {
        extractedChips.innerHTML = '';
        const labels = {
            product_type: 'نوع العقار', city: 'المدينة', neighborhood: 'الحي',
            price: 'السعر', rooms: 'الغرف', bathrooms: 'الحمامات',
            area: 'المساحة', purpose: 'الغرض', marketing_channels: 'قنوات التسويق',
            target_audience: 'الجمهور المستهدف', notes: 'ملاحظات'
        };
        for (const [k, v] of Object.entries(obj)) {
            if (k === 'notes' || v === null || v === '') continue;
            const chip = document.createElement('span');
            chip.className = 'inline-flex items-center gap-1 bg-indigo-100 dark:bg-indigo-900/40 text-indigo-800 dark:text-indigo-200 px-3 py-1.5 rounded-full text-sm';
            chip.textContent = (labels[k] || k) + ': ' + (Array.isArray(v) ? v.join(', ') : v);
            extractedChips.appendChild(chip);
        }
        extractedPreview.classList.toggle('hidden', extractedChips.innerHTML === '');
    }

    submitBtn.addEventListener('click', async () => {
        const nameValue = document.getElementById('name').value.trim();
        const phoneValue = document.getElementById('phone').value.trim();

        if (!nameValue || !phoneValue) {
            resultMessage.textContent = 'الرجاء تعبئة الاسم ورقم الجوال.';
            results.classList.remove('hidden');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.textContent = 'جاري الإرسال...';
        try {
            const res = await fetch(storeUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({
                    name: document.getElementById('name').value,
                    phone: document.getElementById('phone').value,
                    description: description.value,
                    extracted: extracted
                })
            });
            const data = await res.json();
            resultMessage.textContent = data.message;
            renderMatches(data.matches || []);
            results.classList.remove('hidden');
            results.scrollIntoView({ behavior: 'smooth' });
        } catch (e) {
            resultMessage.textContent = 'حدث خطأ أثناء الإرسال. حاول مرة أخرى.';
            results.classList.remove('hidden');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'أرسل طلب التسويق';
        }
    });

    function renderMatches(matches) {
        matchesGrid.innerHTML = '';
        if (matches.length === 0) {
            matchesGrid.innerHTML = '<p class="col-span-full text-center text-gray-500 dark:text-gray-400">لا توجد عقارات مشابهة حالياً.</p>';
            return;
        }
        matches.forEach(m => {
            const card = document.createElement('a');
            card.href = m.url;
            card.className = 'block bg-white dark:bg-slate-900 rounded-2xl shadow hover:shadow-lg transition overflow-hidden border border-gray-100 dark:border-slate-800';
            card.innerHTML = `
                <img src="${m.image_url}" alt="${m.title}" class="w-full h-40 object-cover">
                <div class="p-4">
                    <h4 class="font-bold text-gray-900 dark:text-white mb-1 line-clamp-1">${m.title}</h4>
                    <p class="text-indigo-600 font-semibold mb-1">${m.price || 'السعر عند الطلب'}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-1">${m.address || ''}</p>
                    <span class="inline-block mt-2 text-xs bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-gray-300 px-2 py-1 rounded">${m.category || ''}</span>
                </div>
            `;
            matchesGrid.appendChild(card);
        });
    }
</script>
@endsection
