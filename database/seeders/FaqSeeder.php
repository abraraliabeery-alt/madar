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
                'question' => 'كيف يمكنني البحث عن مشروع؟',
                'answer' => 'يمكنك استخدام صفحة البحث المتقدم أو تصفح المشاريع المتاحة حسب الفئة أو المنطقة. يمكنك أيضاً تحديد الميزانية والمساحة والموقع للحصول على نتائج أكثر دقة.',
                'category' => 'عام',
                'order' => 1,
                'is_active' => true,
                'locale' => 'ar'
            ],
            [
                'question' => 'كيف يمكنني التواصل مع صاحب المشروع؟',
                'answer' => 'يمكنك إرسال رسالة مباشرة من صفحة المشروع أو الاتصال بالرقم المرفق. كما يمكنك حجز موعد لمعاينة الموقع من خلال النموذج المخصص.',
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
                'question' => 'كيف يمكنني إضافة مشروعي للموقع؟',
                'answer' => 'يمكنك التسجيل كمالك منشأة وإضافة مشاريعك من خلال لوحة التحكم الخاصة بك. ستحتاج إلى تقديم الوثائق المطلوبة للحصول على الموافقة.',
                'category' => 'إضافة مشاريع',
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
                'question' => 'كيف يمكنني حجز موعد لمعاينة مشروع؟',
                'answer' => 'يمكنك حجز موعد من خلال صفحة المشروع أو التواصل معنا مباشرة عبر الهاتف أو البريد الإلكتروني. سنقوم بتأكيد الموعد وإرسال التفاصيل.',
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
                'answer' => 'تختلف الرسوم حسب نوع الخدمة وحجم المشروع. يمكنك التواصل معنا للحصول على عرض سعر مفصل. نقدم عروض خاصة للعملاء الجدد.',
                'category' => 'أسعار',
                'order' => 8,
                'is_active' => true,
                'locale' => 'ar'
            ],

            // English FAQs
            [
                'question' => 'How can I find a contractor for my project?',
                'answer' => 'You can browse contractors by category and location, or submit an execution request with your project details to receive proposals.',
                'category' => 'General',
                'order' => 1,
                'is_active' => true,
                'locale' => 'en'
            ],
            [
                'question' => 'How can I contact a contractor?',
                'answer' => 'You can contact the contractor from their profile page via the provided phone number or send a message through the platform.',
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
                'question' => 'How can my company appear on the platform?',
                'answer' => 'You can register as a facility owner, complete your company profile, and publish your services, projects, and works from your dashboard.',
                'category' => 'Company Profile',
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
