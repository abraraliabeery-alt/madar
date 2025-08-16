<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Faq;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            // Arabic FAQs
            [
                'question' => 'كيف يمكنني البحث عن عقار؟',
                'answer' => 'يمكنك استخدام صفحة البحث المتقدم أو تصفح العقارات المتاحة حسب الفئة أو المنطقة. يمكنك أيضاً تحديد السعر والمساحة والموقع للحصول على نتائج أكثر دقة.',
                'category' => 'عام',
                'order' => 1,
                'is_active' => true,
                'locale' => 'ar'
            ],
            [
                'question' => 'كيف يمكنني التواصل مع مالك العقار؟',
                'answer' => 'يمكنك إرسال رسالة مباشرة من صفحة العقار أو الاتصال بالرقم المرفق. كما يمكنك حجز موعد لزيارة العقار من خلال النموذج المخصص.',
                'category' => 'تواصل',
                'order' => 2,
                'is_active' => true,
                'locale' => 'ar'
            ],
            [
                'question' => 'هل الخدمة مجانية؟',
                'answer' => 'نعم، خدمات البحث والتصفح مجانية. بعض الخدمات المتقدمة مثل الحجز المباشر أو الحصول على عروض خاصة قد تتطلب اشتراك.',
                'category' => 'أسعار',
                'order' => 3,
                'is_active' => true,
                'locale' => 'ar'
            ],
            [
                'question' => 'كيف يمكنني إضافة عقاري للموقع؟',
                'answer' => 'يمكنك التسجيل كمالك منشأة وإضافة عقاراتك من خلال لوحة التحكم الخاصة بك. ستحتاج إلى تقديم الوثائق المطلوبة للحصول على الموافقة.',
                'category' => 'إضافة عقارات',
                'order' => 4,
                'is_active' => true,
                'locale' => 'ar'
            ],
            [
                'question' => 'ما هي ضمانات الخدمة؟',
                'answer' => 'نقدم ضمانات شاملة لجميع خدماتنا ونتابع مع عملائنا حتى اكتمال العملية بنجاح. نضمن جودة المعلومات المقدمة ودقة التفاصيل.',
                'category' => 'ضمانات',
                'order' => 5,
                'is_active' => true,
                'locale' => 'ar'
            ],
            [
                'question' => 'كيف يمكنني حجز موعد لزيارة عقار؟',
                'answer' => 'يمكنك حجز موعد من خلال صفحة العقار أو التواصل معنا مباشرة عبر الهاتف أو البريد الإلكتروني. سنقوم بتأكيد الموعد وإرسال التفاصيل.',
                'category' => 'حجوزات',
                'order' => 6,
                'is_active' => true,
                'locale' => 'ar'
            ],
            [
                'question' => 'هل تقدمون خدمات التمويل؟',
                'answer' => 'نعم، نتعاون مع عدة بنوك ومؤسسات مالية لتقديم أفضل عروض التمويل لعملائنا. يمكنك التواصل معنا للحصول على عرض مفصل.',
                'category' => 'تمويل',
                'order' => 7,
                'is_active' => true,
                'locale' => 'ar'
            ],
            [
                'question' => 'ما هي رسوم الخدمة؟',
                'answer' => 'تختلف الرسوم حسب نوع الخدمة وحجم العقار. يمكنك التواصل معنا للحصول على عرض سعر مفصل. نقدم عروض خاصة للعملاء الجدد.',
                'category' => 'أسعار',
                'order' => 8,
                'is_active' => true,
                'locale' => 'ar'
            ],

            // English FAQs
            [
                'question' => 'How can I search for a property?',
                'answer' => 'You can use the advanced search page or browse available properties by category or area. You can also specify price, area, and location to get more accurate results.',
                'category' => 'General',
                'order' => 1,
                'is_active' => true,
                'locale' => 'en'
            ],
            [
                'question' => 'How can I contact the property owner?',
                'answer' => 'You can send a direct message from the property page or call the provided number. You can also book an appointment to visit the property through the dedicated form.',
                'category' => 'Communication',
                'order' => 2,
                'is_active' => true,
                'locale' => 'en'
            ],
            [
                'question' => 'Is the service free?',
                'answer' => 'Yes, search and browsing services are free. Some advanced services like direct booking or getting special offers may require a subscription.',
                'category' => 'Pricing',
                'order' => 3,
                'is_active' => true,
                'locale' => 'en'
            ],
            [
                'question' => 'How can I add my property to the site?',
                'answer' => 'You can register as a facility owner and add your properties through your dashboard. You will need to submit the required documents for approval.',
                'category' => 'Adding Properties',
                'order' => 4,
                'is_active' => true,
                'locale' => 'en'
            ],
            [
                'question' => 'What are the service guarantees?',
                'answer' => 'We provide comprehensive guarantees for all our services and follow up with our clients until the process is completed successfully. We ensure the quality of information provided and accuracy of details.',
                'category' => 'Guarantees',
                'order' => 5,
                'is_active' => true,
                'locale' => 'en'
            ]
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }
}
