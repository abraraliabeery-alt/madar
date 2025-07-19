<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * عرض صفحة التواصل
     */
    public function index()
    {
        $contactInfo = [
            'phone' => '+966 50 123 4567',
            'email' => 'info@aqar.com',
            'address' => 'الرياض، المملكة العربية السعودية',
            'working_hours' => 'الأحد - الخميس: 8:00 ص - 6:00 م',
            'social_media' => [
                'facebook' => 'https://facebook.com/aqar',
                'twitter' => 'https://twitter.com/aqar',
                'instagram' => 'https://instagram.com/aqar',
                'linkedin' => 'https://linkedin.com/company/aqar',
                'youtube' => 'https://youtube.com/aqar',
            ]
        ];

        return view('public.contact', compact('contactInfo'));
    }

    /**
     * إرسال رسالة التواصل
     */
    public function sendMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'type' => 'required|in:general,inquiry,complaint,suggestion',
            'g-recaptcha-response' => 'required|recaptcha',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // حفظ الرسالة في قاعدة البيانات
        // يمكن إنشاء جدول contact_messages منفصل

        // إرسال إشعار بالبريد الإلكتروني
        try {
            Mail::send('emails.contact', [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'subject' => $request->subject,
                'message' => $request->message,
                'type' => $request->type,
            ], function ($message) use ($request) {
                $message->to('info@aqar.com', 'Aqar Support')
                    ->subject('رسالة جديدة: ' . $request->subject);
            });

            // إرسال تأكيد للعميل
            Mail::send('emails.contact-confirmation', [
                'name' => $request->name,
                'subject' => $request->subject,
            ], function ($message) use ($request) {
                $message->to($request->email, $request->name)
                    ->subject('تأكيد استلام رسالتك');
            });

        } catch (\Exception $e) {
            // تسجيل الخطأ في السجلات
            \Log::error('Contact form error: ' . $e->getMessage());
        }

        return redirect()->back()
            ->with('success', 'تم إرسال رسالتك بنجاح. سنتواصل معك قريباً.');
    }

    /**
     * عرض صفحة طلب عرض سعر
     */
    public function quote()
    {
        $services = [
            'property_valuation' => 'تقييم العقار',
            'property_management' => 'إدارة العقار',
            'marketing_services' => 'خدمات التسويق',
            'legal_services' => 'الخدمات القانونية',
            'consulting' => 'الاستشارات العقارية',
        ];

        return view('public.contact.quote', compact('services'));
    }

    /**
     * إرسال طلب عرض سعر
     */
    public function sendQuote(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'company' => 'nullable|string|max:255',
            'service_type' => 'required|array',
            'service_type.*' => 'in:property_valuation,property_management,marketing_services,legal_services,consulting',
            'property_type' => 'required|in:residential,commercial,industrial,land',
            'property_size' => 'required|numeric|min:1',
            'budget' => 'nullable|numeric|min:0',
            'timeline' => 'required|in:urgent,normal,flexible',
            'description' => 'required|string|max:1000',
            'g-recaptcha-response' => 'required|recaptcha',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // حفظ طلب عرض السعر
        // يمكن إنشاء جدول quote_requests منفصل

        // إرسال إشعار بالبريد الإلكتروني
        try {
            Mail::send('emails.quote-request', [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'company' => $request->company,
                'service_type' => $request->service_type,
                'property_type' => $request->property_type,
                'property_size' => $request->property_size,
                'budget' => $request->budget,
                'timeline' => $request->timeline,
                'description' => $request->description,
            ], function ($message) use ($request) {
                $message->to('sales@aqar.com', 'Aqar Sales')
                    ->subject('طلب عرض سعر جديد من ' . $request->name);
            });

            // إرسال تأكيد للعميل
            Mail::send('emails.quote-confirmation', [
                'name' => $request->name,
                'service_type' => $request->service_type,
            ], function ($message) use ($request) {
                $message->to($request->email, $request->name)
                    ->subject('تأكيد استلام طلب عرض السعر');
            });

        } catch (\Exception $e) {
            \Log::error('Quote request error: ' . $e->getMessage());
        }

        return redirect()->back()
            ->with('success', 'تم إرسال طلب عرض السعر بنجاح. سنتواصل معك خلال 24 ساعة.');
    }

    /**
     * عرض صفحة الشكاوى والاقتراحات
     */
    public function feedback()
    {
        $feedbackTypes = [
            'complaint' => 'شكوى',
            'suggestion' => 'اقتراح',
            'praise' => 'ثناء',
            'bug_report' => 'تقرير خطأ',
            'feature_request' => 'طلب ميزة جديدة',
        ];

        return view('public.contact.feedback', compact('feedbackTypes'));
    }

    /**
     * إرسال الشكوى أو الاقتراح
     */
    public function sendFeedback(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'type' => 'required|in:complaint,suggestion,praise,bug_report,feature_request',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'rating' => 'nullable|integer|between:1,5',
            'g-recaptcha-response' => 'required|recaptcha',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // حفظ الشكوى أو الاقتراح
        // يمكن إنشاء جدول feedback منفصل

        // إرسال إشعار بالبريد الإلكتروني
        try {
            Mail::send('emails.feedback', [
                'name' => $request->name,
                'email' => $request->email,
                'type' => $request->type,
                'subject' => $request->subject,
                'message' => $request->message,
                'rating' => $request->rating,
            ], function ($message) use ($request) {
                $message->to('feedback@aqar.com', 'Aqar Feedback')
                    ->subject('ملاحظة جديدة: ' . $request->subject);
            });

            // إرسال تأكيد للعميل
            Mail::send('emails.feedback-confirmation', [
                'name' => $request->name,
                'type' => $request->type,
            ], function ($message) use ($request) {
                $message->to($request->email, $request->name)
                    ->subject('تأكيد استلام ملاحظتك');
            });

        } catch (\Exception $e) {
            \Log::error('Feedback error: ' . $e->getMessage());
        }

        return redirect()->back()
            ->with('success', 'تم إرسال ملاحظتك بنجاح. شكراً لك على اهتمامك.');
    }

    /**
     * عرض صفحة الموقع
     */
    public function location()
    {
        $location = [
            'latitude' => 24.7136,
            'longitude' => 46.6753,
            'address' => 'الرياض، المملكة العربية السعودية',
            'zoom' => 15,
        ];

        return view('public.contact.location', compact('location'));
    }

    /**
     * عرض صفحة الفروع
     */
    public function branches()
    {
        $branches = [
            [
                'name' => 'الفرع الرئيسي - الرياض',
                'address' => 'شارع الملك فهد، الرياض',
                'phone' => '+966 11 123 4567',
                'email' => 'riyadh@aqar.com',
                'working_hours' => 'الأحد - الخميس: 8:00 ص - 6:00 م',
                'latitude' => 24.7136,
                'longitude' => 46.6753,
            ],
            [
                'name' => 'فرع جدة',
                'address' => 'شارع التحلية، جدة',
                'phone' => '+966 12 123 4567',
                'email' => 'jeddah@aqar.com',
                'working_hours' => 'الأحد - الخميس: 8:00 ص - 6:00 م',
                'latitude' => 21.4858,
                'longitude' => 39.1925,
            ],
            [
                'name' => 'فرع الدمام',
                'address' => 'شارع الملك خالد، الدمام',
                'phone' => '+966 13 123 4567',
                'email' => 'dammam@aqar.com',
                'working_hours' => 'الأحد - الخميس: 8:00 ص - 6:00 م',
                'latitude' => 26.4207,
                'longitude' => 50.0888,
            ],
        ];

        return view('public.contact.branches', compact('branches'));
    }

    /**
     * عرض صفحة الأسئلة الشائعة للتواصل
     */
    public function faq()
    {
        $faqs = [
            [
                'question' => 'كيف يمكنني التواصل معكم؟',
                'answer' => 'يمكنك التواصل معنا عبر الهاتف، البريد الإلكتروني، أو من خلال نموذج التواصل في الموقع.'
            ],
            [
                'question' => 'ما هي أوقات العمل؟',
                'answer' => 'نعمل من الأحد إلى الخميس من الساعة 8:00 صباحاً حتى 6:00 مساءً.'
            ],
            [
                'question' => 'كم تستغرق مدة الرد على الرسائل؟',
                'answer' => 'نرد على جميع الرسائل خلال 24 ساعة عمل.'
            ],
            [
                'question' => 'هل يمكنني طلب موعد شخصي؟',
                'answer' => 'نعم، يمكنك طلب موعد شخصي عبر الهاتف أو البريد الإلكتروني.'
            ],
            [
                'question' => 'هل تقدمون خدمات في جميع المدن؟',
                'answer' => 'نعم، نقدم خدماتنا في جميع مدن المملكة العربية السعودية.'
            ],
        ];

        return view('public.contact.faq', compact('faqs'));
    }
}
