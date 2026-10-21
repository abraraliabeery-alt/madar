<?php

namespace App\Services;

use App\Models\Quote;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

class QuotePdfService
{
    public function download(Quote $quote): StreamedResponse
    {
        $quote->load(['items', 'terms', 'discounts', 'facility']);

        $facility = $quote->facility;
        $setting = $facility->setting;
        $customization = $facility->customization;

        return Pdf::loadView('facility.quotes.pdf', compact('quote', 'facility', 'setting', 'customization'))
            ->stream("quote-{$quote->quote_number}.pdf");
    }
}
