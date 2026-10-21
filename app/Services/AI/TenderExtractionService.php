<?php

namespace App\Services\AI;

use RuntimeException;

class TenderExtractionService
{
    public function __construct(private StructuredAiService $ai)
    {
    }

    public function analyze(string $tenderText): array
    {
        if (trim($tenderText) === '') {
            throw new RuntimeException('Tender text is empty.');
        }

        $systemPrompt = <<<'PROMPT'
You are a Saudi government tender analysis assistant.
Analyze the tender text provided and extract a structured compliance matrix.
Return only a valid JSON object with this structure:
{
  "summary": "A concise 2-3 sentence overview of the tender and its main expectations.",
  "requirements": [
    {
      "id": "REQ-1",
      "title": "Short requirement title",
      "description": "Clear description of what is required",
      "priority": "high|medium|low",
      "compliance_status": "compliant|partial|non_compliant|unknown"
    }
  ]
}
Rules:
- Use Arabic for all text content.
- If the text is not a tender, return empty requirements and a warning summary.
- Set compliance_status to "unknown" when the tender does not give enough evidence.
- Be concise; each description must not exceed two sentences.
PROMPT;

        return $this->ai->json($systemPrompt, ['text' => $tenderText], 0.2);
    }
}
