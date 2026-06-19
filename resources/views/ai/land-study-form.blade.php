<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>محلل الاستثمار المشاريعي السعودي (متصل بـ AI)</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Configure Tailwind to use Arabic font and custom colors -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'], // Fallback font for English
                        arabic: ['Noto Sans Arabic', 'sans-serif'], // Using a modern Arabic font
                    },
                    colors: {
                        'primary-blue': '#10B981', // Emerald Green for primary actions
                        'primary-light': '#F0FDF4', // Lightest green for background
                        'ai-bg': '#E5E7EB', // Light gray for AI messages
                        'user-bg': '#DBF9EA', // Very light green for user messages
                    },
                }
            }
        }
    </script>
    <style>
        /* Import a clean Arabic font */
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@400;600;700&display=swap');
        
        body {
            font-family: 'Noto Sans Arabic', sans-serif;
            background-color: #f7f8f9; /* Off-white background */
            display: flex;
            height: 100vh;
            overflow: hidden;
        }
        /* Custom scrollbar for chat area */
        .chat-area::-webkit-scrollbar {
            width: 8px;
        }
        .chat-area::-webkit-scrollbar-track {
            background: transparent;
        }
        .chat-area::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        .chat-area::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Animation for the AI typing indicator */
        .dot {
            animation: dot-flicker 1.4s infinite ease-in-out;
            margin: 0 1px;
            display: inline-block;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: #4B5563; /* Gray-600 */
        }
        .dot:nth-child(2) {
            animation-delay: 0.2s;
        }
        .dot:nth-child(3) {
            animation-delay: 0.4s;
        }
        @keyframes dot-flicker {
            0%, 80%, 100% { transform: scale(0); }
            40% { transform: scale(1); }
        }
    </style>
    <!-- Firebase SDK (Mandatory Setup) -->
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-app.js";
        import { getAuth, signInAnonymously, signInWithCustomToken, onAuthStateChanged } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-auth.js";
        import { getFirestore, doc, onSnapshot, collection, addDoc, setDoc } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-firestore.js";

        // Global variables provided by the Canvas environment
        const appId = typeof __app_id !== 'undefined' ? __app_id : 'default-app-id';
        const firebaseConfig = typeof __firebase_config !== 'undefined' ? JSON.parse(__firebase_config) : { /* Mock Config */ };
        const initialAuthToken = typeof __initial_auth_token !== 'undefined' ? __initial_auth_token : null;

        let db, auth, userId = null;

        // Function to initialize Firebase and sign in
        const initializeFirebase = async () => {
            try {
                if (!firebaseConfig.projectId) {
                    console.warn("Firebase configuration is missing or invalid. Using mock data.");
                    return; // Skip actual Firebase connection if config is missing
                }

                const app = initializeApp(firebaseConfig);
                db = getFirestore(app);
                auth = getAuth(app);

                // Sign in using the provided custom token or anonymously
                if (initialAuthToken) {
                    await signInWithCustomToken(auth, initialAuthToken);
                } else {
                    await signInAnonymously(auth);
                }

                onAuthStateChanged(auth, (user) => {
                    if (user) {
                        userId = user.uid;
                        console.log("Firebase initialized and user signed in:", userId);
                        // Here you would typically start listening to chat data from Firestore
                        // Example: listenToChats(userId);
                    } else {
                        console.log("No user signed in.");
                    }
                });

            } catch (error) {
                console.error("Error initializing Firebase:", error);
            }
        };

        // Call initialization function
        // initializeFirebase();

        // Add a mock function for adding a chat message (for UI demo)
        window.saveMessageToFirestore = async (message) => {
             if (db && userId) {
                 const collectionPath = `/artifacts/${appId}/users/${userId}/messages`;
                 await addDoc(collection(db, collectionPath), {
                     ...message,
                     timestamp: new Date(),
                 });
                 console.log("Message saved to Firestore (Simulated):", message);
             } else {
                 console.log("Firestore not fully initialized. Skipping save.");
             }
        }
    </script>
</head>

<body>

    <!-- Main Container -->
    <div class="flex flex-col md:flex-row w-full h-full bg-primary-light">

        <!-- 1. Sidebar (New Chat/History) -->
        <aside id="sidebar" class="w-full md:w-64 bg-white p-4 flex-shrink-0 border-l border-gray-200 shadow-xl md:shadow-none transition-transform duration-300 ease-in-out transform -translate-x-full md:translate-x-0 absolute md:relative z-20 h-full">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-800">محلل الاستثمار المشاريعي السعودي</h2>
                <button id="close-sidebar-btn" class="md:hidden p-2 text-gray-600 hover:text-gray-800">
                    <i data-lucide="x"></i>
                </button>
            </div>

            <!-- New Chat Button -->
            <button class="w-full flex items-center justify-center p-3 mb-4 rounded-xl bg-primary-blue text-white font-semibold hover:bg-emerald-600 transition duration-150 shadow-md">
                <i data-lucide="plus" class="w-5 h-5 ml-2"></i>
                دراسة جديدة
            </button>

            <!-- Recent Chats (Mock) -->
            <div class="space-y-2">
                <p class="text-sm font-semibold text-gray-500 mb-2 mt-4">اليوم</p>
                <a href="#" class="block p-3 rounded-xl bg-user-bg text-gray-800 hover:bg-gray-100 transition duration-150 truncate border border-primary-blue">
                    تحليل جدوى أرض تجارية في الرياض
                </a>
                <a href="#" class="block p-3 rounded-xl hover:bg-gray-100 transition duration-150 truncate">
                    مقترحات تطوير أرض سكنية
                </a>
            </div>
            
            <div class="absolute bottom-4 left-4 right-4">
                <div class="flex items-center p-3 bg-gray-50 rounded-xl">
                    <div class="w-8 h-8 rounded-full bg-primary-blue flex items-center justify-center text-white font-bold text-sm">أ.ك</div>
                    <div class="mr-3">
                        <p class="font-semibold text-sm text-gray-800">أحمد الكودر</p>
                        <p class="text-xs text-gray-500">الحساب الأساسي</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- 2. Chat Window -->
        <main class="flex-1 flex flex-col min-w-0 bg-white">
            
            <!-- Header (Mobile Only) -->
            <header class="md:hidden flex items-center p-4 border-b border-gray-200 bg-white shadow-sm">
                <button id="open-sidebar-btn" class="p-2 text-gray-600 hover:text-gray-800">
                    <i data-lucide="menu"></i>
                </button>
                <h1 class="text-lg font-semibold text-gray-800 mx-auto">محلل الاستثمار السعودي</h1>
            </header>

            <!-- Chat Messages Area -->
            <div id="chat-area" class="chat-area flex-1 overflow-y-auto p-4 md:p-8 space-y-6">
                <!-- Welcome/Initial Prompts -->
                <div class="flex flex-col items-center justify-center h-full text-center p-4">
                    <i data-lucide="building-2" class="w-12 h-12 text-primary-blue mb-4"></i>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">كيف يمكنني مساعدتك في مشروعك المشاريعي في السعودية؟</h2>
                    <p class="text-gray-500 mb-8">أنا متخصص في دراسات الجدوى، تحليل الأراضي، ومقترحات التطوير وفقاً **للكود السعودي للبناء (SBC)**.</p>
                    
                    <!-- Quick Prompt Suggestions (UX Feature) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full max-w-2xl">
                        <button class="quick-prompt p-4 rounded-xl bg-white border border-gray-200 text-gray-700 hover:bg-gray-100 transition duration-150 shadow-sm text-right" data-prompt="تحليل جدوى أرض تجارية بمساحة 1000م في الرياض.">
                            <i data-lucide="bar-chart-3" class="w-5 h-5 mb-2 text-primary-blue"></i>
                            <p class="font-semibold">تحليل جدوى أرض تجارية بمساحة 1000م في الرياض.</p>
                        </button>
                        <button class="quick-prompt p-4 rounded-xl bg-white border border-gray-200 text-gray-700 hover:bg-gray-100 transition duration-150 shadow-sm text-right" data-prompt="مقترحات استثمارية لأرض سكنية في جدة مع التركيز على وحدات الإيجار.">
                            <i data-lucide="map-pin" class="w-5 h-5 mb-2 text-primary-blue"></i>
                            <p class="font-semibold">مقترحات استثمارية لأرض سكنية في جدة مع التركيز على وحدات الإيجار.</p>
                        </button>
                        <button class="quick-prompt p-4 rounded-xl bg-white border border-gray-200 text-gray-700 hover:bg-gray-100 transition duration-150 shadow-sm text-right" data-prompt="مقارنة بين تطوير فيلا فاخرة وبناء وحدات صغيرة في الخبر.">
                            <i data-lucide="home" class="w-5 h-5 mb-2 text-primary-blue"></i>
                            <p class="font-semibold">مقارنة بين تطوير فيلا فاخرة وبناء وحدات صغيرة في الخبر.</p>
                        </button>
                        <button class="quick-prompt p-4 rounded-xl bg-white border border-gray-200 text-gray-700 hover:bg-gray-100 transition duration-150 shadow-sm text-right" data-prompt="ما هي المتطلبات التنظيمية للبناء الصناعي وفقاً للكود السعودي؟">
                            <i data-lucide="scroll-text" class="w-5 h-5 mb-2 text-primary-blue"></i>
                            <p class="font-semibold">ما هي المتطلبات التنظيمية للبناء الصناعي وفقاً للكود السعودي؟</p>
                        </button>
                    </div>
                </div>

                <!-- Messages will be injected here -->

                <!-- AI Typing Indicator (Hidden by default) -->
                <div id="typing-indicator" class="hidden flex items-start space-x-3 space-x-reverse">
                    <div class="w-8 h-8 rounded-full bg-primary-blue flex items-center justify-center text-white flex-shrink-0">
                        <i data-lucide="bot" class="w-4 h-4"></i>
                    </div>
                    <div class="bg-ai-bg text-gray-800 p-3 rounded-2xl rounded-tr-none max-w-4xl shadow-md flex items-center">
                        <div class="dot"></div>
                        <div class="dot"></div>
                        <div class="dot"></div>
                        <span class="sr-only">الذكاء الاصطناعي يكتب...</span>
                    </div>
                </div>
            </div>

            <!-- Input Footer -->
            <footer class="p-4 md:p-6 border-t border-gray-200 bg-white space-y-3">
                <!-- Structured Prompt Helper -->
                <div class="max-w-4xl mx-auto mb-1 bg-gray-50 rounded-2xl border border-dashed border-gray-300 p-3 md:p-4">
                    <div class="flex flex-col md:flex-row md:items-end gap-3">
                        <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">نوع المستخدم</label>
                                <select id="role-select" class="w-full text-xs rounded-xl border-gray-300 focus:ring-primary-blue focus:border-primary-blue">
                                    <option value="investor">مستثمر مشاريعي</option>
                                    <option value="builder">شخص يريد البناء</option>
                                    <option value="contractor">مقاول</option>
                                    <option value="architect">مهندس معماري</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">رابط الأرض (Google Maps)</label>
                                <input id="gmaps-url" type="text" class="w-full text-xs rounded-xl border-gray-300 focus:ring-primary-blue focus:border-primary-blue" placeholder="مثال: https://maps.app.goo.gl/...">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">المساحة م²</label>
                                <input id="land-area" type="number" min="1" class="w-full text-xs rounded-xl border-gray-300 focus:ring-primary-blue focus:border-primary-blue" placeholder="مثال: 1500">
                            </div>
                        </div>
                        <div class="flex-shrink-0 flex flex-col items-stretch gap-2 md:w-40">
                            <button id="build-prompt-btn" type="button" class="inline-flex items-center justify-center px-3 py-2 rounded-xl bg-primary-blue text-white text-xs font-semibold hover:bg-emerald-600 shadow">
                                <i data-lucide="sparkles" class="w-4 h-4 ml-1"></i>
                                توليد أفضل صياغة
                            </button>
                            <span class="text-[10px] text-gray-400 text-center">اختر النوع وأضف الرابط والمساحة ثم اضغط التوليد، بعدها عدّل النص وأرسله.</span>
                        </div>
                    </div>
                </div>

                <div class="max-w-4xl mx-auto flex items-end bg-gray-50 rounded-3xl shadow-lg border border-gray-200">
                    <textarea id="user-input" rows="1" class="flex-1 w-full p-4 resize-none bg-transparent focus:outline-none placeholder-gray-400 text-gray-800" placeholder="أدخل معطيات الأرض (المساحة، الموقع، الاستخدام)... (اضغط Enter للإرسال)" oninput="adjustTextareaHeight(this)"></textarea>
                    
                    <!-- Send Button -->
                    <button id="send-btn" class="flex-shrink-0 p-3 m-2 rounded-full bg-primary-blue text-white disabled:bg-gray-400 hover:bg-emerald-600 transition duration-150 shadow-lg" disabled>
                        <i data-lucide="send" class="w-5 h-5"></i>
                    </button>
                </div>
                <p class="text-xs text-center text-gray-400 mt-2">يمكن أن يرتكب الذكاء الاصطناعي أخطاءً. تحقق من المعلومات الهامة.</p>
            </footer>

        </main>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        const chatArea = document.getElementById('chat-area');
        const userInput = document.getElementById('user-input');
        const sendBtn = document.getElementById('send-btn');
        const typingIndicator = document.getElementById('typing-indicator');
        const quickPrompts = document.querySelectorAll('.quick-prompt');
        const sidebar = document.getElementById('sidebar');
        const openSidebarBtn = document.getElementById('open-sidebar-btn');
        const closeSidebarBtn = document.getElementById('close-sidebar-btn');
        const roleSelect = document.getElementById('role-select');
        const gmapsInput = document.getElementById('gmaps-url');
        const landAreaInput = document.getElementById('land-area');
        const buildPromptBtn = document.getElementById('build-prompt-btn');

        let isAiThinking = false;
        // Use Laravel backend endpoint instead of calling Gemini directly from the browser
        const apiUrl = "{{ route('ai.investment.chat') }}";

        // --- Firebase/Auth/App Globals (Placeholder for persistence integration) ---
        // Assume db and userId are defined in the module script block above.
        
        // --- UX/UI Utility Functions ---

        // Function to adjust textarea height dynamically
        function adjustTextareaHeight(textarea) {
            textarea.style.height = 'auto';
            textarea.style.height = (textarea.scrollHeight) + 'px';
            sendBtn.disabled = textarea.value.trim() === '';
        }

        // Function to scroll chat to bottom
        function scrollToBottom() {
            chatArea.scrollTop = chatArea.scrollHeight;
        }

        // Function to create a message element
        function createMessageElement(text, role) {
            const isUser = role === 'user';
            const messageContainer = document.createElement('div');
            messageContainer.className = `flex items-start space-x-3 space-x-reverse ${isUser ? 'justify-end' : ''}`;
            
            const contentDiv = document.createElement('div');
            // Use 'white-space: pre-wrap;' to respect line breaks and preserve formatting
            contentDiv.style.whiteSpace = 'pre-wrap'; 
            contentDiv.className = `${isUser ? 'bg-user-bg text-gray-800 rounded-2xl rounded-bl-none' : 'bg-ai-bg text-gray-800 rounded-2xl rounded-tr-none'} p-3 max-w-3xl shadow-sm`;
            contentDiv.innerHTML = text.trim();

            const avatarDiv = document.createElement('div');
            avatarDiv.className = 'w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 font-bold text-sm';
            
            if (isUser) {
                avatarDiv.className += ' bg-primary-blue text-white';
                avatarDiv.innerHTML = '<i data-lucide="user" class="w-4 h-4"></i>';
            } else {
                avatarDiv.className += ' bg-primary-blue text-white';
                avatarDiv.innerHTML = '<i data-lucide="bot" class="w-4 h-4"></i>';

                // Add Copy Button for AI response
                const copyBtn = document.createElement('button');
                copyBtn.className = 'absolute top-2 left-2 p-1 text-gray-500 hover:text-primary-blue opacity-0 group-hover:opacity-100 transition';
                copyBtn.innerHTML = '<i data-lucide="copy" class="w-4 h-4"></i>';
                copyBtn.title = "نسخ الرد";
                copyBtn.onclick = () => copyToClipboard(text);

                contentDiv.classList.add('relative');
                messageContainer.classList.add('group');
                contentDiv.appendChild(copyBtn);
            }
            
            // Append Avatar and Content in the correct order for RTL (Avatar, then Content)
            messageContainer.appendChild(avatarDiv);
            messageContainer.appendChild(contentDiv);
            
            // Re-render Lucide icons for the copy button
            setTimeout(() => lucide.createIcons(), 0); 
            
            return messageContainer;
        }

        // Function to handle the actual AI response from Gemini API
        async function handleRealAiResponse(userMessage) {
            isAiThinking = true;
            typingIndicator.classList.remove('hidden');
            scrollToBottom();

            // The backend will handle provider selection, prompts, and tools.
            const payload = {
                message: userMessage,
            };

            const aiMessageContainer = document.createElement('div');
            aiMessageContainer.className = 'flex items-start space-x-3 space-x-reverse';
            
            const aiAvatarDiv = document.createElement('div');
            aiAvatarDiv.className = 'w-8 h-8 rounded-full bg-primary-blue flex items-center justify-center text-white flex-shrink-0 font-bold text-sm';
            aiAvatarDiv.innerHTML = '<i data-lucide="bot" class="w-4 h-4"></i>';
            aiMessageContainer.appendChild(aiAvatarDiv);

            const aiContentDiv = document.createElement('div');
            aiContentDiv.style.whiteSpace = 'pre-wrap';
            aiContentDiv.className = 'bg-ai-bg text-gray-800 p-3 rounded-2xl rounded-tr-none max-w-4xl shadow-sm relative group';
            
            const copyBtn = document.createElement('button');
            copyBtn.className = 'absolute top-2 left-2 p-1 text-gray-500 hover:text-primary-blue opacity-0 group-hover:opacity-100 transition';
            copyBtn.innerHTML = '<i data-lucide="copy" class="w-4 h-4"></i>';
            copyBtn.title = "نسخ الرد";
            aiContentDiv.appendChild(copyBtn);
            
            aiMessageContainer.appendChild(aiContentDiv);
            chatArea.appendChild(aiMessageContainer);

            try {
                let response;
                let retryCount = 0;
                const maxRetries = 3;

                // Exponential backoff for API call
                while (retryCount < maxRetries) {
                    try {
                        response = await fetch(apiUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify(payload)
                        });

                        if (response.status === 429) {
                            // Rate limit exceeded, wait and retry
                            const delay = Math.pow(2, retryCount) * 1000;
                            await new Promise(resolve => setTimeout(resolve, delay));
                            retryCount++;
                            continue;
                        }

                        const result = await response.json().catch(() => ({}));

                        if (!response.ok) {
                            let serverMessage = result.message || "حدث خطأ أثناء معالجة طلبك. يرجى المحاولة مرة أخرى.";

                            if (response.status === 503) {
                                if (result.error === 'AI_CONFIG_MISSING') {
                                    serverMessage = result.message || "خدمة الذكاء الاصطناعي غير مفعّلة حالياً بسبب نقص الإعدادات.";
                                } else {
                                    serverMessage = result.message || "خدمة الذكاء الاصطناعي غير متاحة حالياً. يرجى المحاولة لاحقاً.";
                                }
                            }

                            if (response.status === 429) {
                                serverMessage = result.message || "تم تجاوز حد الطلبات. انتظر قليلاً ثم أعد المحاولة.";
                            }

                            aiContentDiv.innerHTML = serverMessage;
                            break;
                        }

                        let generatedText = result.reply || "لم يتمكن الذكاء الاصطناعي من توليد استجابة واضحة.";

                        // Update the content and stop thinking state
                        aiContentDiv.innerHTML = generatedText;
                        copyBtn.onclick = () => copyToClipboard(generatedText);
                        break; // Exit the retry loop

                    } catch (error) {
                        console.error("API Call Error:", error);
                        aiContentDiv.innerHTML = "تعذر الاتصال بالخدمة حالياً. تحقق من اتصال السيرفر ثم أعد المحاولة.";
                        break; // Stop retrying on non-rate limit errors
                    }
                }

            } finally {
                typingIndicator.classList.add('hidden');
                isAiThinking = false;
                userInput.disabled = false;
                sendBtn.disabled = userInput.value.trim() === '';
                userInput.focus();
                scrollToBottom();
                lucide.createIcons(); // Re-render icons after content update
                
                // Save the final AI message (simulated)
                // window.saveMessageToFirestore({ role: 'ai', text: aiContentDiv.innerHTML });
            }
        }
        
        // --- Core Chat Logic ---

        function sendMessage() {
            if (isAiThinking) return;

            const userMessage = userInput.value.trim();
            if (userMessage === '') return;

            // 1. Remove initial prompts area if it exists
            const initialPrompts = chatArea.querySelector('.h-full.text-center');
            if (initialPrompts) {
                initialPrompts.remove();
            }

            // 2. Display user message
            const userMsgElement = createMessageElement(userMessage, 'user');
            chatArea.appendChild(userMsgElement);

            // 3. Clear input and disable controls
            userInput.value = '';
            adjustTextareaHeight(userInput);
            userInput.disabled = true;
            sendBtn.disabled = true;

            // 4. Show typing indicator and scroll
            scrollToBottom();
            
            // 5. Initiate real AI response
            handleRealAiResponse(userMessage);

            // 6. Save the user message (simulated)
            // window.saveMessageToFirestore({ role: 'user', text: userMessage });
        }

        // --- Event Listeners ---

        // Send button click
        sendBtn.addEventListener('click', sendMessage);

        // Enter key press in input field
        userInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });

        // Quick prompt clicks
        quickPrompts.forEach(btn => {
            btn.addEventListener('click', () => {
                userInput.value = btn.getAttribute('data-prompt');
                adjustTextareaHeight(userInput);
                sendMessage();
            });
        });

        // Structured helper: build best-practice prompt from role + map URL + area
        if (buildPromptBtn) {
            buildPromptBtn.addEventListener('click', () => {
                const role = roleSelect ? roleSelect.value : 'investor';
                const url = (gmapsInput?.value || '').trim();
                const area = (landAreaInput?.value || '').trim();

                let roleLine = '';
                switch (role) {
                    case 'builder':
                        roleLine = 'أنا شخص أريد البناء على أرض في السعودية.';
                        break;
                    case 'contractor':
                        roleLine = 'أنا مقاول في السعودية وأريد تقييم مشروع على هذه الأرض.';
                        break;
                    case 'architect':
                        roleLine = 'أنا مهندس معماري في السعودية وأريد تصور معماري واستثماري لهذه الأرض.';
                        break;
                    default:
                        roleLine = 'أنا مستثمر مشاريعي في السعودية.';
                }

                let text = roleLine + '\n';

                if (url) {
                    text += 'رابط موقع الأرض على خرائط قوقل: ' + url + "\n";
                }

                if (area) {
                    text += 'مساحة الأرض تقريباً: ' + area + ' متر مربع.\n';
                }

                text += '\nأريد تحليلاً احترافياً يتضمن:\n';

                if (role === 'contractor') {
                    text += '- تقييم المخاطر التنفيذية والإنشائية وفقاً للكود السعودي للبناء.\n';
                    text += '- ملاحظات على بنود عقد المقاولة وأهم ما يجب التنبه له.\n';
                    text += '- نطاق أعمال مقترح وتقدير تقريبي للتكاليف.\n';
                } else if (role === 'architect') {
                    text += '- أفكار Concept Design وتوزيع كتل مبدئي متوافق مع الكود السعودي.\n';
                    text += '- اقتراح استخدامات مناسبة للأرض (سكني / تجاري / مختلط) مع شرح مبسط.\n';
                    text += '- نقاط رئيسية يجب الانتباه لها في الارتدادات والارتفاعات المتوقعة.\n';
                } else {
                    text += '- تحليل للموقع والسوق بناءً على موقع الأرض.\n';
                    text += '- أفضل 3 سيناريوهات استثمارية مع CAPEX تقريبي والعائد المتوقع.\n';
                    text += '- ملاحظات على المخاطر المحتملة وكيفية تخفيفها.\n';
                }

                userInput.value = text.trim();
                adjustTextareaHeight(userInput);
                sendBtn.disabled = userInput.value.trim() === '';
                userInput.focus();
            });
        }

        // Sidebar Toggles (for mobile responsiveness)
        openSidebarBtn.addEventListener('click', () => {
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('shadow-2xl');
        });

        closeSidebarBtn.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            sidebar.classList.remove('shadow-2xl');
        });
        
        // --- Clipboard Utility ---
        function copyToClipboard(text) {
            const tempInput = document.createElement("textarea");
            tempInput.value = text; // Copy the raw text
            document.body.appendChild(tempInput);
            tempInput.select();
            
            try {
                const successful = document.execCommand('copy');
                // Optional: Show a temporary success message
                showCopyMessage(successful ? "تم النسخ!" : "فشل النسخ.");
            } catch (err) {
                showCopyMessage("فشل النسخ.");
            }
            document.body.removeChild(tempInput);
        }
        
        function showCopyMessage(message) {
            let msgElement = document.getElementById('copy-status');
            if (!msgElement) {
                msgElement = document.createElement('div');
                msgElement.id = 'copy-status';
                msgElement.className = 'fixed bottom-20 left-1/2 transform -translate-x-1/2 px-4 py-2 bg-gray-800 text-white text-sm rounded-full shadow-lg opacity-0 transition-opacity duration-300 z-50';
                document.body.appendChild(msgElement);
            }
            msgElement.textContent = message;
            msgElement.classList.remove('opacity-0');
            msgElement.classList.add('opacity-100');
            
            setTimeout(() => {
                msgElement.classList.remove('opacity-100');
                msgElement.classList.add('opacity-0');
            }, 2000);
        }


        // Ensure icons are created after the DOM loads
        window.onload = function() {
            lucide.createIcons();
            // initializeFirebase(); // Uncomment this line if you want to initialize Firebase on load
        }
    </script>
</body>
</html>