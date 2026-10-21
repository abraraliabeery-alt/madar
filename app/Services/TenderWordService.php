<?php

namespace App\Services;

use App\Models\FacilityTender;
use Illuminate\Support\Str;
use RuntimeException;
use ZipArchive;

class TenderWordService
{
    public function generateDocx(FacilityTender $tender): string
    {
        $tempPath = tempnam(sys_get_temp_dir(), 'tender_').'.docx';

        $zip = new ZipArchive();
        if ($zip->open($tempPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new RuntimeException('Unable to create Word document.');
        }

        $zip->addFromString('[Content_Types].xml', $this->contentTypes());
        $zip->addEmptyDir('_rels');
        $zip->addFromString('_rels/.rels', $this->packageRels());
        $zip->addEmptyDir('word');
        $zip->addEmptyDir('word/_rels');
        $zip->addFromString('word/_rels/document.xml.rels', $this->documentRels());
        $zip->addFromString('word/document.xml', $this->documentXml($tender));

        $zip->close();

        return $tempPath;
    }

    private function documentXml(FacilityTender $tender): string
    {
        $facility = $tender->facility;
        $title = $this->escape($tender->title);
        $facilityName = $this->escape($facility->name);
        $reference = $this->escape($tender->reference ?: '');

        $response = $tender->data['response'] ?? '';

        $paragraphs = collect();

        $paragraphs->push($this->paragraph($facilityName, true, true));

        $heading = 'رد المناقصة: '.$title;
        if ($reference !== '') {
            $heading .= ' — المرجع: '.$reference;
        }
        $paragraphs->push($this->paragraph($heading, true, true));

        $paragraphs->push($this->paragraph('', false, true));

        foreach (explode("\n", $response) as $line) {
            $line = trim($line);
            if ($line !== '') {
                $paragraphs->push($this->paragraph($line, false, true));
            } else {
                $paragraphs->push($this->paragraph('', false, true));
            }
        }

        $content = $paragraphs->implode("\n");

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
            '<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'.
            '<w:body>'.$content.
            '<w:sectPr>'.
            '<w:pgSz w:w="11906" w:h="16838"/>'.
            '<w:pgMar w:top="1440" w:right="1440" w:bottom="1440" w:left="1440" w:header="720" w:footer="720" w:gutter="0"/>'.
            '<w:bidi/>'.
            '</w:sectPr>'.
            '</w:body></w:document>';
    }

    private function paragraph(string $text, bool $bold = false, bool $rtl = false): string
    {
        $escaped = $this->escape($text);
        $bidi = $rtl ? '<w:bidi/>' : '';
        $justify = $rtl ? '<w:jc w:val="right"/>' : '';

        $runProps = '';
        if ($bold) {
            $runProps .= '<w:b/>';
        }
        if ($rtl) {
            $runProps .= '<w:rtl/>';
        }
        if ($runProps !== '') {
            $runProps = '<w:rPr>'.$runProps.'</w:rPr>';
        }

        $spaceAttr = $text === '' ? '' : ' xml:space="preserve"';

        return '<w:p><w:pPr>'.$justify.$bidi.'</w:pPr><w:r>'.$runProps.'<w:t'.$spaceAttr.'>'.$escaped.'</w:t></w:r></w:p>';
    }

    private function escape(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }

    private function contentTypes(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
            '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'.
            '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'.
            '<Default Extension="xml" ContentType="application/xml"/>'.
            '<Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>'.
            '</Types>';
    }

    private function packageRels(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
            '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'.
            '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/>'.
            '</Relationships>';
    }

    private function documentRels(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
            '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"/>';
    }
}
