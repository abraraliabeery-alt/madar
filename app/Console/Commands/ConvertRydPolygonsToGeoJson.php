<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Shapefile\Shapefile;
use Shapefile\ShapefileException;
use Shapefile\ShapefileReader;

class ConvertRydPolygonsToGeoJson extends Command
{
    protected $signature = 'gis:shp-to-geojson
        {--input=storage/app/gis/rydpolygons.shp : Input .shp path relative to project root}
        {--output=public/geojson/plan-boundary.geojson : Output GeoJSON path relative to project root}';

    protected $description = 'Convert rydpolygons.shp shapefile to GeoJSON FeatureCollection for Leaflet usage.';

    public function handle(): int
    {
        $inputRelative = (string) $this->option('input');
        $outputRelative = (string) $this->option('output');

        $inputPath = base_path($inputRelative);
        $outputPath = base_path($outputRelative);

        if (!File::exists($inputPath)) {
            $this->error("Input file not found: {$inputPath}");
            return self::FAILURE;
        }

        File::ensureDirectoryExists(dirname($outputPath));

        $features = [];
        $skipped = 0;
        $converted = 0;
        $total = 0;

        try {
            $shapefile = new ShapefileReader($inputPath, [
                Shapefile::OPTION_SUPPRESS_M => true,
                Shapefile::OPTION_SUPPRESS_Z => true,
                Shapefile::OPTION_ENFORCE_POLYGON_CLOSED_RINGS => true,
                Shapefile::OPTION_INVERT_POLYGONS_ORIENTATION => true,
                Shapefile::OPTION_ENFORCE_GEOMETRY_DATA_STRUCTURE => false,
                Shapefile::OPTION_DBF_CONVERT_TO_UTF8 => true,
            ]);

            $tot = $shapefile->getTotRecords();
            $total = $tot;
            for ($i = 1; $i <= $tot; $i++) {
                try {
                    $shapefile->setCurrentRecord($i);
                    $geometry = $shapefile->fetchRecord();

                    if (!$geometry || $geometry->isDeleted()) {
                        $skipped++;
                        continue;
                    }

                    $geometryJson = $geometry->getGeoJSON();
                    $geometryArr = json_decode($geometryJson, true);

                    if (!is_array($geometryArr) || !isset($geometryArr['type'])) {
                        $skipped++;
                        continue;
                    }

                    $features[] = [
                        'type' => 'Feature',
                        'properties' => $geometry->getDataArray(),
                        'geometry' => $geometryArr,
                    ];

                    $converted++;
                } catch (ShapefileException $e) {
                    $type = (string) $e->getErrorType();
                    if (in_array($type, [
                        'ERR_GEOM_RING_AREA_TOO_SMALL',
                        'ERR_GEOM_RING_NOT_ENOUGH_VERTICES',
                        'ERR_GEOM_POLYGON_NOT_VALID',
                    ], true)) {
                        $skipped++;
                        continue;
                    }

                    throw $e;
                }
            }
        } catch (ShapefileException $e) {
            $this->error('Shapefile error: ' . $e->getMessage());
            $this->line('Error Type: ' . $e->getErrorType());
            $this->line('Details: ' . $e->getDetails());
            return self::FAILURE;
        }

        if ($converted === 0) {
            $rawGeometry = $this->parseRawPolygonShp($inputPath);
            if ($rawGeometry !== null) {
                $features[] = [
                    'type' => 'Feature',
                    'properties' => [
                        'source' => 'raw_shp_fallback',
                    ],
                    'geometry' => $rawGeometry,
                ];

                $converted = 1;
            }
        }

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => $features,
        ];

        File::put(
            $outputPath,
            json_encode($geojson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );

        $decoded = json_decode(File::get($outputPath), true);
        if (!is_array($decoded) || ($decoded['type'] ?? null) !== 'FeatureCollection' || !is_array($decoded['features'] ?? null)) {
            $this->error('Generated file is not a valid GeoJSON FeatureCollection: ' . $outputPath);
            return self::FAILURE;
        }

        $this->info('GeoJSON generated successfully.');
        $this->line('Output: ' . $outputPath);
        $this->line('Features: ' . count($decoded['features']));
        $this->line('Stats: total=' . $total . ', converted=' . $converted . ', skipped=' . $skipped);

        return self::SUCCESS;
    }

    private function parseRawPolygonShp(string $inputPath): ?array
    {
        $h = @fopen($inputPath, 'rb');
        if ($h === false) {
            return null;
        }

        try {
            $header = fread($h, 100);
            if ($header === false || strlen($header) !== 100) {
                return null;
            }

            while (!feof($h)) {
                $recordHeader = fread($h, 8);
                if ($recordHeader === false || strlen($recordHeader) === 0) {
                    break;
                }
                if (strlen($recordHeader) !== 8) {
                    return null;
                }

                $rh = unpack('NrecordNumber/NcontentLength', $recordHeader);
                $contentBytes = ((int) ($rh['contentLength'] ?? 0)) * 2;
                if ($contentBytes <= 0) {
                    continue;
                }

                $content = fread($h, $contentBytes);
                if ($content === false || strlen($content) !== $contentBytes) {
                    return null;
                }

                $shapeType = unpack('Vtype', substr($content, 0, 4));
                $type = (int) ($shapeType['type'] ?? 0);
                if ($type === 0) {
                    continue;
                }
                if ($type !== 5) {
                    continue;
                }

                $offset = 4;
                $offset += 32;

                $numParts = unpack('Vn', substr($content, $offset, 4));
                $offset += 4;
                $numPoints = unpack('Vn', substr($content, $offset, 4));
                $offset += 4;

                $partsCount = (int) ($numParts['n'] ?? 0);
                $pointsCount = (int) ($numPoints['n'] ?? 0);
                if ($partsCount <= 0 || $pointsCount <= 0) {
                    continue;
                }

                $parts = [];
                for ($i = 0; $i < $partsCount; $i++) {
                    $parts[] = (int) (unpack('Vn', substr($content, $offset, 4))['n'] ?? 0);
                    $offset += 4;
                }

                $points = [];
                for ($i = 0; $i < $pointsCount; $i++) {
                    $x = unpack('e', substr($content, $offset, 8));
                    $offset += 8;
                    $y = unpack('e', substr($content, $offset, 8));
                    $offset += 8;
                    $points[] = [(float) ($x[1] ?? 0.0), (float) ($y[1] ?? 0.0)];
                }

                $polygons = [];
                for ($p = 0; $p < $partsCount; $p++) {
                    $start = $parts[$p];
                    $end = ($p + 1 < $partsCount) ? $parts[$p + 1] : $pointsCount;

                    if ($start < 0 || $end <= $start || $end > $pointsCount) {
                        continue;
                    }

                    $ring = array_slice($points, $start, $end - $start);
                    if (count($ring) < 4) {
                        continue;
                    }

                    $first = $ring[0];
                    $last = $ring[count($ring) - 1];
                    if ($first[0] !== $last[0] || $first[1] !== $last[1]) {
                        $ring[] = $first;
                    }

                    $polygons[] = [$ring];
                }

                if (count($polygons) === 0) {
                    continue;
                }

                return [
                    'type' => 'MultiPolygon',
                    'coordinates' => $polygons,
                ];
            }

            return null;
        } finally {
            fclose($h);
        }
    }
}
