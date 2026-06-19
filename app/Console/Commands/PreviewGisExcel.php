<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

class PreviewGisExcel extends Command
{
    protected $signature = 'gis:excel-preview
        {path=storage/app/gis/e6198f91-68be-4d5c-b5b9-01ad44f56b4e.xlsx : Excel path relative to project root}
        {--sheet=0 : Sheet index (0-based)}
        {--rows=10 : Number of rows to preview}';

    protected $description = 'Preview a GIS Excel file (sheet headings + first rows) using maatwebsite/excel.';

    public function handle(): int
    {
        $relativePath = (string) $this->argument('path');
        $sheetIndex = (int) $this->option('sheet');
        $rows = max(1, (int) $this->option('rows'));

        $fullPath = base_path($relativePath);

        if (!File::exists($fullPath)) {
            $this->error('File not found: ' . $fullPath);
            return self::FAILURE;
        }

        try {
            $sheets = Excel::toArray(null, $fullPath);
        } catch (\Throwable $e) {
            $this->error('Failed reading Excel: ' . $e->getMessage());
            return self::FAILURE;
        }

        if (!isset($sheets[$sheetIndex]) || !is_array($sheets[$sheetIndex])) {
            $this->error('Sheet index not found: ' . $sheetIndex);
            $this->line('Available sheets: ' . count($sheets));
            return self::FAILURE;
        }

        $sheet = $sheets[$sheetIndex];

        if (count($sheet) === 0) {
            $this->warn('Sheet is empty.');
            return self::SUCCESS;
        }

        $headings = $sheet[0] ?? [];
        if (is_array($headings) && count($headings) > 0) {
            $this->info('Headings (row 1):');
            $this->line(implode(' | ', array_map(static fn ($v) => (string) $v, $headings)));

            $this->newLine();
            $this->info('Headings (with indices):');
            foreach ($headings as $idx => $name) {
                $this->line('[' . $idx . '] ' . (string) $name);
            }
            $this->line('Columns: ' . count($headings));
        }

        $preview = array_slice($sheet, 0, $rows);

        $this->newLine();
        $this->info('Preview (first ' . count($preview) . ' rows):');

        foreach ($preview as $i => $row) {
            if (!is_array($row)) {
                $this->line('#' . ($i + 1) . ': ' . (string) $row);
                continue;
            }

            $normalized = array_map(static fn ($v) => is_scalar($v) || $v === null ? (string) ($v ?? '') : json_encode($v, JSON_UNESCAPED_UNICODE), $row);
            $this->line('#' . ($i + 1) . ': ' . implode(' | ', $normalized));
        }

        $this->newLine();
        $this->line('Sheets: ' . count($sheets) . ', SheetIndex: ' . $sheetIndex . ', TotalRowsInSheet: ' . count($sheet));

        return self::SUCCESS;
    }
}
