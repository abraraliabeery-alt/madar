<?php

namespace App\Services\AI;

use App\Models\Facility;
use App\Models\FacilityTender;
use RuntimeException;

class TenderResponseService
{
    public function __construct(private StructuredAiService $ai)
    {
    }

    public function generate(FacilityTender $tender, Facility $facility): string
    {
        $content = $tender->data['content'] ?? '';

        if (trim($content) === '') {
            throw new RuntimeException('Tender content is empty.');
        }

        $payload = [
            'tender_content' => $content,
            'tender_requirements' => $tender->data['requirements'] ?? [],
            'facility_name' => $facility->name,
            'facility_category' => $facility->facilityCategory?->name ?? '',
            'facility_description' => $facility->description ?? '',
            'facility_address' => $facility->address ?? '',
            'facility_phone' => $facility->phone ?? '',
            'facility_email' => $facility->email ?? '',
            'facility_license' => $facility->license_number ?? '',
        ];

        $systemPrompt = <<<'PROMPT'
You are a Saudi tender response specialist.
Write a professional, formal, and persuasive tender response in Arabic.
Use the tender content and requirements, along with the facility profile, to produce the response.
Structure the response into clear sections:
1. Company profile and relevant experience.
2. Understanding of the tender and its requirements.
3. Compliance and capability statement, addressing each requirement.
4. Proposed approach, methodology, and timeline.
5. Quality assurance and commitments.
6. Contact and follow-up.
Keep it concise, professional, and ready to be copied into a formal proposal document.
Return the response as plain Arabic text; do not include markdown formatting or JSON.
PROMPT;

        return $this->ai->text($systemPrompt, $payload, 0.5);
    }
}
