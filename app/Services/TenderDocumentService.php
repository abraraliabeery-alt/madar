<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use RuntimeException;
use ZipArchive;

class TenderDocumentService
{
    public function extractText(UploadedFile $file): string
    {
        $extension = strtolower($file->getClientOriginalExtension());

        return match ($extension) {
            'txt' => $this->extractPlainText($file),
            'docx' => $this->extractDocx($file),
            'xlsx', 'xls' => $this->extractExcel($file),
            'pdf' => $this->extractPdf($file),
            default => throw new RuntimeException('نوع الملف غير مدعوم. الصيغ المدعومة: txt, docx, xlsx, xls, pdf.'),
        };
    }

    private function extractPlainText(UploadedFile $file): string
    {
        $text = file_get_contents($file->getRealPath());

        return is_string($text) ? trim($text) : '';
    }

    private function extractDocx(UploadedFile $file): string
    {
        $zip = new ZipArchive();

        if ($zip->open($file->getRealPath()) !== true) {
            throw new RuntimeException('Unable to open DOCX file.');
        }

        $xml = $zip->getFromName('word/document.xml');
        $zip->close();

        if ($xml === false) {
            throw new RuntimeException('DOCX document content not found.');
        }

        $text = strip_tags($xml);
        $text = str_replace(["\r\n", "\r", "\n"], ' ', $text);

        return trim(preg_replace('/\s+/', ' ', $text));
    }

    private function extractExcel(UploadedFile $file): string
    {
        try {
            $import = new class implements \Maatwebsite\Excel\Concerns\ToArray {
                public function array(array $array)
                {
                    return $array;
                }
            };

            $rows = Excel::toArray($import, $file->getRealPath());
        } catch (\Throwable $e) {
            throw new RuntimeException('Unable to read Excel file: '.$e->getMessage());
        }

        $lines = [];

        foreach ($rows as $sheet) {
            foreach ($sheet as $row) {
                $line = implode(' ', array_map(fn ($cell) => trim((string) $cell), $row));
                if ($line !== '') {
                    $lines[] = $line;
                }
            }
        }

        return implode("\n", $lines);
    }

    private function extractPdf(UploadedFile $file): string
    {
        if (! class_exists('Smalot\PdfParser\Parser')) {
            throw new RuntimeException('PDF extraction needs smalot/pdfparser. Run: composer require smalot/pdfparser');
        }

        try {
            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile($file->getRealPath());

            return trim($pdf->getText());
        } catch (\Throwable $e) {
            throw new RuntimeException('Unable to extract text from PDF: '.$e->getMessage());
        }
    }
}
