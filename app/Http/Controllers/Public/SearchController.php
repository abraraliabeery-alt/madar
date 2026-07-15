<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Facility;
use App\Models\Category;
use App\Models\Status;
use App\Models\Feature;
use App\Models\Attribute;
use App\Models\Plan;
use App\Models\PlanLot;
use App\Models\ExecutionRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use JsonMachine\Items;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    /**
     * صفحة البحث الرئيسية
     */
    public function index(Request $request)
    {
        $categories = Category::where('is_active', true)->get();
        $features = Feature::where('is_active', true)->get();
        $statuses = Status::where('is_active', true)->get();
        
        return view('public.search.index', compact('categories', 'features', 'statuses'));
    }

    public function ajlanCadPoints(Request $request)
    {
        return $this->cadGeoJsonSubset(
            base_path('resources/New/points.geojson'),
            $request
        );
    }

    public function ajlanCadTexts(Request $request)
    {
        return $this->cadGeoJsonSubset(
            base_path('resources/New/texts.geojson'),
            $request
        );
    }

    public function ajlanCadLines(Request $request)
    {
        return $this->cadGeoJsonSubset(
            base_path('resources/New/lines.geojson'),
            $request,
            true
        );
    }

    public function ajlanCadPolylines(Request $request)
    {
        return $this->cadGeoJsonSubset(
            base_path('resources/New/polylines.geojson'),
            $request,
            true
        );
    }

    public function ajlanCadFile(Request $request, string $file)
    {
        $file = Str::lower(trim($file));
        $allowed = [
            'points',
            'texts',
            'lines',
            'polylines',
            'blocks',
            'inserts',
        ];
        if (!in_array($file, $allowed, true)) {
            abort(404);
        }

        $requireBbox = in_array($file, ['polylines', 'lines'], true);

        return $this->cadGeoJsonSubset(
            base_path('resources/New/' . $file . '.geojson'),
            $request,
            $requireBbox
        );
    }

    public function ajlanCadManifest(Request $request)
    {
        $files = [
            'points',
            'texts',
            'lines',
            'polylines',
            'blocks',
            'inserts',
        ];

        $out = [];
        foreach ($files as $f) {
            $path = base_path('resources/New/' . $f . '.geojson');
            $out[] = [
                'name' => $f,
                'exists' => is_file($path),
                'size' => is_file($path) ? filesize($path) : null,
                // known: inserts has geometry null, blocks often 0,0. Keep flags for UI.
                'hint' => match ($f) {
                    'inserts' => 'metadata_only',
                    'blocks' => 'mostly_metadata',
                    default => 'drawable',
                },
            ];
        }

        return response()->json([
            'ok' => true,
            'files' => $out,
        ]);
    }

    public function ajlanCadDxfFile(Request $request, string $file)
    {
        $file = trim($file);
        if ($file === '') {
            abort(404);
        }

        $known = [
            'taiba' => 'الموقع العام- طيبه.dxf',
        ];
        $key = Str::lower($file);
        if (array_key_exists($key, $known)) {
            $file = $known[$key];
        }

        $file = str_replace(['..', '\\', '/'], '', $file);
        if (!Str::endsWith(Str::lower($file), '.dxf')) {
            $file .= '.dxf';
        }

        $path = base_path('resources/New/' . $file);
        if (!is_file($path)) {
            abort(404);
        }

        $kind = Str::lower((string) $request->query('kind', 'all'));
        if (!in_array($kind, ['all', 'lines', 'polylines', 'texts'], true)) {
            $kind = 'all';
        }

        return $this->cadDxfSubset($path, $request, $kind);
    }

    public function ajlanPhase1GeoJson(Request $request, string $kind)
    {
        $kind = Str::lower(trim($kind));
        if (!in_array($kind, ['labels', 'boundaries'], true)) {
            abort(404);
        }

        $base = base_path('resources/New/ajlan_phase1_fix_package (1)/public/gis/taiba');
        $file = $kind === 'labels' ? 'phase1_labels.geojson' : 'phase1_boundaries.geojson';
        $path = $base . DIRECTORY_SEPARATOR . $file;
        if (!is_file($path)) {
            return response()->json([
                'type' => 'FeatureCollection',
                'features' => [],
            ]);
        }

        $raw = @file_get_contents($path);
        if (!is_string($raw) || $raw === '') {
            return response()->json([
                'type' => 'FeatureCollection',
                'features' => [],
            ]);
        }

        $json = json_decode($raw, true);
        if (!is_array($json) || ($json['type'] ?? null) !== 'FeatureCollection') {
            return response()->json([
                'type' => 'FeatureCollection',
                'features' => [],
            ]);
        }

        $json['features'] = array_values(array_filter($json['features'] ?? [], fn ($f) => is_array($f)));
        return response()->json($json);
    }

    private function cadGeoJsonSubset(string $path, Request $request, bool $requireBbox = false)
    {
        if (!is_file($path)) {
            return response()->json([
                'type' => 'FeatureCollection',
                'features' => [],
            ]);
        }

        $minX = $request->has('minX') ? (float) $request->query('minX') : null;
        $minY = $request->has('minY') ? (float) $request->query('minY') : null;
        $maxX = $request->has('maxX') ? (float) $request->query('maxX') : null;
        $maxY = $request->has('maxY') ? (float) $request->query('maxY') : null;
        $layer = $request->filled('layer') ? (string) $request->query('layer') : null;
        $q = $request->filled('q') ? mb_strtolower((string) $request->query('q')) : null;
        $utmOnly = $request->boolean('utmOnly', false);
        $limit = (int) $request->query('limit', 2000);
        if ($limit < 1) {
            $limit = 1;
        }
        if ($limit > 20000) {
            $limit = 20000;
        }

        $hasBbox = $minX !== null && $minY !== null && $maxX !== null && $maxY !== null;

        if ($requireBbox && !$hasBbox) {
            return response()->json([
                'type' => 'FeatureCollection',
                'features' => [],
                'error' => 'bbox_required',
            ], 400);
        }

        $items = Items::fromFile($path, ['pointer' => '/features']);
        $features = [];

        foreach ($items as $feature) {
            if (!is_array($feature)) {
                continue;
            }

            $props = is_array($feature['properties'] ?? null) ? $feature['properties'] : [];
            if ($layer !== null && (string) ($props['layer'] ?? '') !== $layer) {
                continue;
            }

            if ($q !== null) {
                $text = $props['text'] ?? ($props['fid'] ?? null);
                if (mb_stripos((string) $text, $q) === false) {
                    continue;
                }
            }

            $geom = $feature['geometry'] ?? null;
            $coords = is_array($geom) ? ($geom['coordinates'] ?? null) : null;

            if ($utmOnly) {
                if (!$this->geometryHasUtm38Point($coords)) {
                    continue;
                }
            }

            if ($hasBbox) {
                if (!$this->geometryHasPointInBbox($coords, $minX, $minY, $maxX, $maxY)) {
                    continue;
                }
            }

            $features[] = $feature;
            if (count($features) >= $limit) {
                break;
            }
        }

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features,
        ]);
    }

    private function cadDxfBlockTextTemplates(string $path): array
    {
        $out = [];

        try {
            $file = new \SplFileObject($path, 'rb');
            $file->setFlags(\SplFileObject::DROP_NEW_LINE);
        } catch (\Throwable $e) {
            return $out;
        }

        $readPair = function () use ($file): ?array {
            if ($file->eof()) {
                return null;
            }
            try {
                $code = $file->fgets();
            } catch (\RuntimeException $e) {
                return null;
            }
            if ($code === false) {
                return null;
            }
            try {
                $value = $file->fgets();
            } catch (\RuntimeException $e) {
                return null;
            }
            if ($value === false) {
                return null;
            }
            return [trim($code), rtrim($value, "\r\n")];
        };

        $inBlocks = false;
        $inBlock = false;
        $blockName = null;
        $currentType = null;
        $textX = null;
        $textY = null;
        $textValue = '';

        $flushText = function () use (&$out, &$blockName, &$textX, &$textY, &$textValue): void {
            $name = is_string($blockName) ? trim($blockName) : '';
            if ($name === '') {
                return;
            }
            if ($textX === null || $textY === null) {
                return;
            }
            $txt = trim((string) $textValue);
            if ($txt === '') {
                return;
            }
            if (!array_key_exists($name, $out)) {
                $out[$name] = [];
            }
            $out[$name][] = [
                'x' => (float) $textX,
                'y' => (float) $textY,
                'text' => $txt,
            ];
        };

        while (!$file->eof()) {
            $pair = $readPair();
            if ($pair === null) {
                break;
            }
            [$code, $value] = $pair;

            if ($code === '0') {
                if ($inBlocks && $inBlock && $currentType !== null) {
                    if (in_array($currentType, ['TEXT', 'MTEXT', 'ATTRIB', 'ATTDEF'], true)) {
                        $flushText();
                    }
                }

                $currentType = null;
                $textX = null;
                $textY = null;
                $textValue = '';

                if ($value === 'SECTION') {
                    continue;
                }
                if ($value === 'ENDSEC') {
                    if ($inBlocks) {
                        break;
                    }
                    $inBlocks = false;
                    $inBlock = false;
                    $blockName = null;
                    continue;
                }

                if ($inBlocks) {
                    if ($value === 'BLOCK') {
                        $inBlock = true;
                        $blockName = null;
                        continue;
                    }
                    if ($value === 'ENDBLK') {
                        $inBlock = false;
                        $blockName = null;
                        continue;
                    }
                    if ($inBlock && in_array($value, ['TEXT', 'MTEXT', 'ATTRIB', 'ATTDEF'], true)) {
                        $currentType = $value;
                        continue;
                    }
                }

                continue;
            }

            if (!$inBlocks) {
                if ($code === '2' && $value === 'BLOCKS') {
                    $inBlocks = true;
                }
                continue;
            }

            if ($inBlocks && $inBlock && $blockName === null) {
                if ($code === '2') {
                    $blockName = $value;
                }
            }

            if ($inBlocks && $inBlock && $currentType !== null) {
                if ($code === '10') {
                    $textX = (float) $value;
                } elseif ($code === '20') {
                    $textY = (float) $value;
                } elseif ($code === '1' || $code === '3') {
                    $textValue .= (string) $value;
                }
            }
        }

        return $out;
    }

    private function cadDxfSubset(string $path, Request $request, string $kind)
    {
        $minX = $request->has('minX') ? (float) $request->query('minX') : null;
        $minY = $request->has('minY') ? (float) $request->query('minY') : null;
        $maxX = $request->has('maxX') ? (float) $request->query('maxX') : null;
        $maxY = $request->has('maxY') ? (float) $request->query('maxY') : null;
        $layer = $request->filled('layer') ? (string) $request->query('layer') : null;
        $q = $request->filled('q') ? mb_strtolower((string) $request->query('q')) : null;
        $limit = (int) $request->query('limit', 2000);
        if ($limit < 1) {
            $limit = 1;
        }
        if ($limit > 20000) {
            $limit = 20000;
        }

        $hasBbox = $minX !== null && $minY !== null && $maxX !== null && $maxY !== null;

        $file = new \SplFileObject($path, 'rb');
        $file->setFlags(\SplFileObject::DROP_NEW_LINE);

        $features = [];
        $inEntities = false;

        $readPair = function () use ($file): ?array {
            if ($file->eof()) {
                return null;
            }

            try {
                $code = $file->fgets();
            } catch (\RuntimeException $e) {
                return null;
            }
            if ($code === false) {
                return null;
            }

            try {
                $value = $file->fgets();
            } catch (\RuntimeException $e) {
                return null;
            }
            if ($value === false) {
                return null;
            }

            return [trim($code), rtrim($value, "\r\n")];
        };

        $acceptPointInBbox = function (float $x, float $y) use ($hasBbox, $minX, $minY, $maxX, $maxY): bool {
            if (!$hasBbox) {
                return true;
            }
            return !($x < $minX || $x > $maxX || $y < $minY || $y > $maxY);
        };

        $geometryHasPointInBboxFast = function (array $coords) use ($acceptPointInBbox): bool {
            $stack = [$coords];
            $checks = 0;
            while ($stack) {
                $node = array_pop($stack);
                if (!is_array($node)) {
                    continue;
                }
                if (count($node) >= 2 && is_numeric($node[0]) && is_numeric($node[1])) {
                    $checks++;
                    if ($acceptPointInBbox((float) $node[0], (float) $node[1])) {
                        return true;
                    }
                    if ($checks >= 2000) {
                        return false;
                    }
                    continue;
                }
                foreach ($node as $child) {
                    if (is_array($child)) {
                        $stack[] = $child;
                    }
                }
            }
            return false;
        };

        $shouldKeepByLayer = function (?string $entityLayer) use ($layer): bool {
            if ($layer === null) {
                return true;
            }
            return (string) $entityLayer === $layer;
        };

        $shouldKeepTextByQ = function (?string $text) use ($q): bool {
            if ($q === null) {
                return true;
            }
            return mb_stripos((string) $text, $q) !== false;
        };

        $flushEntity = function (?string $type, array $props, array $geom) use (&$features, $limit, $kind, $shouldKeepByLayer, $shouldKeepTextByQ, $geometryHasPointInBboxFast): void {
            if ($type === null) {
                return;
            }

            $entityLayer = $props['layer'] ?? null;
            if (!$shouldKeepByLayer($entityLayer)) {
                return;
            }

            if ($kind === 'texts' && !in_array($type, ['TEXT', 'MTEXT', 'ATTRIB', 'ATTDEF'], true)) {
                return;
            }
            if ($kind === 'lines' && $type !== 'LINE') {
                return;
            }
            if ($kind === 'polylines' && !in_array($type, ['LWPOLYLINE', 'POLYLINE'], true)) {
                return;
            }

            $geoType = $geom['type'] ?? null;
            $coords = $geom['coordinates'] ?? null;
            if (!is_string($geoType) || !is_array($coords)) {
                return;
            }

            if (!$geometryHasPointInBboxFast($coords)) {
                return;
            }

            if (in_array($type, ['TEXT', 'MTEXT', 'ATTRIB', 'ATTDEF'], true)) {
                if (!$shouldKeepTextByQ($props['text'] ?? null)) {
                    return;
                }
            }

            $features[] = [
                'type' => 'Feature',
                'properties' => $props,
                'geometry' => $geom,
            ];
        };

        $currentType = null;
        $currentLayer = null;
        $lineX1 = null;
        $lineY1 = null;
        $lineX2 = null;
        $lineY2 = null;
        $polyPoints = [];
        $textX = null;
        $textY = null;
        $textValue = '';

        $insertName = null;
        $insertX = null;
        $insertY = null;
        $insertRotDeg = 0.0;
        $insertScaleX = 1.0;
        $insertScaleY = 1.0;

        while (!$file->eof()) {
            $pair = $readPair();
            if ($pair === null) {
                break;
            }
            [$code, $value] = $pair;

            if ($code === '0') {
                if ($inEntities && $currentType !== null) {
                    if ($currentType === 'LINE') {
                        $flushEntity('LINE', [
                            'layer' => $currentLayer,
                        ], [
                            'type' => 'LineString',
                            'coordinates' => (
                                $lineX1 !== null && $lineY1 !== null && $lineX2 !== null && $lineY2 !== null
                            ) ? [[(float) $lineX1, (float) $lineY1], [(float) $lineX2, (float) $lineY2]] : [],
                        ]);
                    } elseif (in_array($currentType, ['LWPOLYLINE', 'POLYLINE'], true)) {
                        $flushEntity($currentType, [
                            'layer' => $currentLayer,
                        ], [
                            'type' => 'LineString',
                            'coordinates' => $polyPoints,
                        ]);
                    } elseif (in_array($currentType, ['TEXT', 'MTEXT', 'ATTRIB', 'ATTDEF'], true)) {
                        $flushEntity($currentType, [
                            'layer' => $currentLayer,
                            'text' => $textValue,
                        ], [
                            'type' => 'Point',
                            'coordinates' => (
                                $textX !== null && $textY !== null
                            ) ? [(float) $textX, (float) $textY] : [],
                        ]);
                    } elseif ($currentType === 'INSERT') {
                        $name = is_string($insertName) ? trim($insertName) : '';
                        if ($name !== '' && $insertX !== null && $insertY !== null) {
                            $tpls = $blockTextTemplates[$name] ?? null;
                            if (is_array($tpls) && $tpls) {
                                $rad = deg2rad((float) $insertRotDeg);
                                $cos = cos($rad);
                                $sin = sin($rad);
                                $sx = (float) $insertScaleX;
                                $sy = (float) $insertScaleY;
                                $tx = (float) $insertX;
                                $ty = (float) $insertY;

                                foreach ($tpls as $t) {
                                    if (!is_array($t)) {
                                        continue;
                                    }
                                    $lx = isset($t['x']) ? (float) $t['x'] : null;
                                    $ly = isset($t['y']) ? (float) $t['y'] : null;
                                    $txt = isset($t['text']) ? (string) $t['text'] : '';
                                    if ($lx === null || $ly === null || $txt === '') {
                                        continue;
                                    }

                                    $px = $lx * $sx;
                                    $py = $ly * $sy;
                                    $wx = $tx + ($cos * $px - $sin * $py);
                                    $wy = $ty + ($sin * $px + $cos * $py);

                                    $flushEntity('ATTRIB', [
                                        'layer' => $currentLayer,
                                        'text' => $txt,
                                        'block' => $name,
                                    ], [
                                        'type' => 'Point',
                                        'coordinates' => [(float) $wx, (float) $wy],
                                    ]);

                                    if (count($features) >= $limit) {
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }

                $currentType = null;
                $currentLayer = null;
                $lineX1 = null;
                $lineY1 = null;
                $lineX2 = null;
                $lineY2 = null;
                $polyPoints = [];
                $textX = null;
                $textY = null;
                $textValue = '';

                $insertName = null;
                $insertX = null;
                $insertY = null;
                $insertRotDeg = 0.0;
                $insertScaleX = 1.0;
                $insertScaleY = 1.0;

                if ($inEntities && count($features) >= $limit) {
                    break;
                }

                if ($value === 'SECTION') {
                    continue;
                }
                if ($value === 'ENDSEC') {
                    $inEntities = false;
                    continue;
                }
                if ($value === 'EOF') {
                    break;
                }
                if ($value === 'LINE' || $value === 'LWPOLYLINE' || $value === 'POLYLINE' || $value === 'TEXT' || $value === 'MTEXT' || $value === 'ATTRIB' || $value === 'ATTDEF' || $value === 'INSERT') {
                    if ($inEntities) {
                        $currentType = $value;
                    }
                    continue;
                }
            }

            if (!$inEntities) {
                if ($code === '2' && $value === 'ENTITIES') {
                    $inEntities = true;
                }
                continue;
            }

            if ($currentType === null) {
                continue;
            }

            if ($code === '8') {
                $currentLayer = $value;
                continue;
            }

            if ($currentType === 'LINE') {
                if ($code === '10') {
                    $lineX1 = (float) $value;
                } elseif ($code === '20') {
                    $lineY1 = (float) $value;
                } elseif ($code === '11') {
                    $lineX2 = (float) $value;
                } elseif ($code === '21') {
                    $lineY2 = (float) $value;
                }
                continue;
            }

            if (in_array($currentType, ['LWPOLYLINE', 'POLYLINE'], true)) {
                if ($code === '10') {
                    $polyPoints[] = [(float) $value, null];
                } elseif ($code === '20') {
                    $idx = count($polyPoints) - 1;
                    if ($idx >= 0) {
                        $polyPoints[$idx][1] = (float) $value;
                    }
                }
                continue;
            }

            if (in_array($currentType, ['TEXT', 'MTEXT', 'ATTRIB', 'ATTDEF'], true)) {
                if ($code === '10') {
                    $textX = (float) $value;
                } elseif ($code === '20') {
                    $textY = (float) $value;
                } elseif ($code === '1' || $code === '3') {
                    $textValue .= (string) $value;
                }
                continue;
            }

            if ($currentType === 'INSERT') {
                if ($code === '2') {
                    $insertName = $value;
                } elseif ($code === '8') {
                    $currentLayer = $value;
                } elseif ($code === '10') {
                    $insertX = (float) $value;
                } elseif ($code === '20') {
                    $insertY = (float) $value;
                } elseif ($code === '41') {
                    $insertScaleX = (float) $value;
                } elseif ($code === '42') {
                    $insertScaleY = (float) $value;
                } elseif ($code === '50') {
                    $insertRotDeg = (float) $value;
                }
                continue;
            }
        }

        $features = array_values(array_filter($features, function ($f) {
            $coords = $f['geometry']['coordinates'] ?? null;
            if (!is_array($coords) || count($coords) === 0) {
                return false;
            }
            if (($f['geometry']['type'] ?? null) === 'LineString') {
                foreach ($coords as $pt) {
                    if (!is_array($pt) || count($pt) < 2 || $pt[0] === null || $pt[1] === null) {
                        return false;
                    }
                }
            }
            if (($f['geometry']['type'] ?? null) === 'Point') {
                if (count($coords) < 2) {
                    return false;
                }
            }
            return true;
        }));

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features,
        ]);
    }

    private function geometryHasUtm38Point($coords, int $maxChecks = 1600): bool
    {
        if (!is_array($coords)) {
            return false;
        }

        $checks = 0;
        $minX = 400000.0;
        $maxX = 800000.0;
        $minY = 2500000.0;
        $maxY = 3100000.0;

        $walk = function ($node) use (&$walk, $minX, $minY, $maxX, $maxY, &$checks, $maxChecks): bool {
            if ($checks >= $maxChecks) {
                return false;
            }
            if (!is_array($node)) {
                return false;
            }

            if (count($node) >= 2 && is_numeric($node[0]) && is_numeric($node[1])) {
                $checks++;
                $x = (float) $node[0];
                $y = (float) $node[1];
                return !($x < $minX || $x > $maxX || $y < $minY || $y > $maxY);
            }

            foreach ($node as $child) {
                if ($walk($child)) {
                    return true;
                }
                if ($checks >= $maxChecks) {
                    break;
                }
            }
            return false;
        };

        return $walk($coords);
    }

    private function extractFirstXY($coords): ?array
    {
        if (!is_array($coords)) {
            return null;
        }

        $current = $coords;
        while (is_array($current) && isset($current[0]) && is_array($current[0])) {
            $current = $current[0];
        }

        if (!is_array($current) || count($current) < 2) {
            return null;
        }

        $x = (float) ($current[0] ?? 0.0);
        $y = (float) ($current[1] ?? 0.0);
        return [$x, $y];
    }

    private function geometryHasPointInBbox($coords, float $minX, float $minY, float $maxX, float $maxY, int $maxChecks = 1200): bool
    {
        if (!is_array($coords)) {
            return false;
        }

        $checks = 0;

        $walk = function ($node) use (&$walk, $minX, $minY, $maxX, $maxY, &$checks, $maxChecks): bool {
            if ($checks >= $maxChecks) {
                return false;
            }

            if (!is_array($node)) {
                return false;
            }

            // Leaf point: [x,y] (GeoJSON coordinates)
            if (count($node) >= 2 && is_numeric($node[0]) && is_numeric($node[1])) {
                $checks++;
                $x = (float) $node[0];
                $y = (float) $node[1];
                return !($x < $minX || $x > $maxX || $y < $minY || $y > $maxY);
            }

            foreach ($node as $child) {
                if ($walk($child)) {
                    return true;
                }
                if ($checks >= $maxChecks) {
                    break;
                }
            }

            return false;
        };

        return $walk($coords);
    }

    public function ajlanLotShow(Request $request, PlanLot $lot)
    {
        $plan = Plan::query()->where('slug', 'ajlan')->first();
        if (!$plan || (int) $lot->plan_id !== (int) $plan->id) {
            abort(404);
        }

        $centroid = null;
        try {
            $ring = is_array($lot->geometry) ? ($lot->geometry['coordinates'][0] ?? null) : null;
            if (is_array($ring) && count($ring) > 0) {
                $sumLng = 0.0;
                $sumLat = 0.0;
                $n = 0;
                foreach ($ring as $pt) {
                    if (is_array($pt) && count($pt) >= 2) {
                        $sumLng += (float) $pt[0];
                        $sumLat += (float) $pt[1];
                        $n++;
                    }
                }
                if ($n > 0) {
                    $centroid = ['lat' => $sumLat / $n, 'lng' => $sumLng / $n];
                }
            }
        } catch (\Throwable $e) {
            $centroid = null;
        }

        return view('public.plans.lot_show', [
            'plan' => $plan,
            'lot' => $lot,
            'centroid' => $centroid,
            'whatsappNumber' => $request->string('whatsapp')->toString(),
        ]);
    }
 
    /**
     * البحث في المنتجات
     */
    public function products(Request $request)
    {
        $params = array_filter([
            'status' => 'open',
            'q' => $request->query('q'),
            'min_budget' => $request->query('min_price'),
            'max_budget' => $request->query('max_price'),
        ], static fn ($v) => $v !== null && $v !== '');

        return redirect()->route('public.execution.marketplace', $params);
    }

    /**
     * البحث في المنشآت
     */
    public function facilities(Request $request)
    {
        $query = Facility::with(['facilityCategory', 'owner'])
            ->where('is_active', true)
            ->where('is_verified', true);

        // البحث النصي
        if ($request->has('q') && $request->q) {
            $searchTerm = $request->q;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhere('address', 'like', '%' . $searchTerm . '%');
            });
        }

        // فلترة حسب الفئة
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // فلترة حسب الحالة
        if ($request->has('status_id') && $request->status_id) {
            $query->where('status_id', $request->status_id);
        }

        // فلترة حسب الموقع
        if ($request->has('latitude') && $request->has('longitude') && $request->has('radius')) {
            $lat = $request->latitude;
            $lng = $request->longitude;
            $radius = $request->radius;

            $query->whereRaw("
                (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) <= ?
            ", [$lat, $lng, $lat, $radius]);
        }

        // فلترة حسب التحقق
        if ($request->has('verified')) {
            $query->where('is_verified', $request->verified);
        }

        // فلترة حسب المميزات
        if ($request->has('featured')) {
            $query->where('is_featured', $request->featured);
        }

        // الترتيب
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default:
                $query->latest();
                break;
        }

        $facilities = $query->paginate(12);
        $categories = Category::all();
        $statuses = Status::all();

        return view('public.search.facilities', compact('facilities', 'categories', 'statuses'));
    }

    public function ajlanPlan(Request $request)
    {
        $fallbackCenterLat = 24.550964276;
        $fallbackCenterLng = 46.824846268;
        $fallbackPlanNumber = '2705/5';
        $fallbackPlanAreaKm2 = 3.88;

        $plan = Plan::query()->where('slug', 'ajlan')->with('lots')->first();

        if (!$plan || $plan->lots->count() === 0) {
            try {
                $viewPath = resource_path('views/public/plans/plans.blade.php');
                if (File::exists($viewPath)) {
                    $content = File::get($viewPath);
                    $marker = 'const parcelsWGS = ';
                    $start = strpos($content, $marker);
                    if ($start !== false) {
                        $start += strlen($marker);
                        $end = strpos($content, ';', $start);
                        if ($end !== false) {
                            $json = trim(substr($content, $start, $end - $start));
                            $payload = json_decode($json, true);
                            if (is_array($payload) && ($payload['type'] ?? null) === 'FeatureCollection' && is_array($payload['features'] ?? null)) {
                                $plan = Plan::query()->firstOrCreate(
                                    ['slug' => 'ajlan'],
                                    [
                                        'name' => 'عجلان',
                                        'plan_number' => $fallbackPlanNumber,
                                        'center_lat' => $fallbackCenterLat,
                                        'center_lng' => $fallbackCenterLng,
                                        'area_km2' => $fallbackPlanAreaKm2,
                                    ]
                                );

                                foreach ($payload['features'] as $feat) {
                                    if (!is_array($feat)) continue;
                                    $props = is_array($feat['properties'] ?? null) ? $feat['properties'] : [];
                                    $geom = is_array($feat['geometry'] ?? null) ? $feat['geometry'] : null;
                                    if (!$geom) continue;

                                    $lotNumber = (string) ($props['lot_number'] ?? $props['parcel_no'] ?? $props['id'] ?? '');
                                    if ($lotNumber === '') continue;

                                    $area = $props['area_m2'] ?? $props['area'] ?? null;

                                    PlanLot::query()->updateOrCreate(
                                        ['plan_id' => $plan->id, 'lot_number' => $lotNumber],
                                        [
                                            'usage' => $props['usage'] ?? null,
                                            'status' => $props['status'] ?? 'available',
                                            'area_m2' => is_numeric($area) ? (float) $area : null,
                                            'price' => isset($props['price']) && is_numeric($props['price']) ? (int) $props['price'] : null,
                                            'geometry' => $geom,
                                        ]
                                    );
                                }

                                $plan = Plan::query()->where('slug', 'ajlan')->with('lots')->first();
                            }
                        }
                    }
                }
            } catch (\Throwable $e) {
            }
        }

        $centerLat = $plan?->center_lat ?? $fallbackCenterLat;
        $centerLng = $plan?->center_lng ?? $fallbackCenterLng;
        $planNumber = $plan?->plan_number ?? $fallbackPlanNumber;
        $planAreaKm2 = $plan?->area_km2 ?? $fallbackPlanAreaKm2;

        $planAreaM2 = $planAreaKm2 * 1000 * 1000;
        $planShadeRadiusMeters = sqrt($planAreaM2 / pi());

        $geoJson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        if ($plan && $plan->lots->count()) {
            $geoJson['features'] = $plan->lots->map(function ($lot) {
                return [
                    'type' => 'Feature',
                    'properties' => [
                        'db_id' => $lot->id,
                        'lot_number' => (string) $lot->lot_number,
                        'area' => $lot->area_m2,
                        'usage' => $lot->usage,
                        'status' => $lot->status,
                        'price' => $lot->price,
                    ],
                    'geometry' => $lot->geometry,
                ];
            })->values()->all();
        } else {
            // بيانات GeoJSON مؤقتة للتجربة فقط ويجب استبدالها لاحقًا ببيانات قاعدة البيانات.
            $geoJson['features'] = [
                [
                    'type' => 'Feature',
                    'properties' => [
                        'lot_number' => '101',
                        'area' => 540,
                        'usage' => 'سكني',
                        'status' => 'available',
                        'price' => 650000,
                    ],
                    'geometry' => [
                        'type' => 'Polygon',
                        'coordinates' => [[
                            [$fallbackCenterLng + 0.00120, $fallbackCenterLat + 0.00040],
                            [$fallbackCenterLng + 0.00170, $fallbackCenterLat + 0.00040],
                            [$fallbackCenterLng + 0.00170, $fallbackCenterLat + 0.00010],
                            [$fallbackCenterLng + 0.00120, $fallbackCenterLat + 0.00010],
                            [$fallbackCenterLng + 0.00120, $fallbackCenterLat + 0.00040],
                        ]],
                    ],
                ],
                [
                    'type' => 'Feature',
                    'properties' => [
                        'lot_number' => '102',
                        'area' => 600,
                        'usage' => 'تجاري',
                        'status' => 'reserved',
                        'price' => 880000,
                    ],
                    'geometry' => [
                        'type' => 'Polygon',
                        'coordinates' => [[
                            [$fallbackCenterLng + 0.00120, $fallbackCenterLat + 0.00005],
                            [$fallbackCenterLng + 0.00170, $fallbackCenterLat + 0.00005],
                            [$fallbackCenterLng + 0.00170, $fallbackCenterLat - 0.00025],
                            [$fallbackCenterLng + 0.00120, $fallbackCenterLat - 0.00025],
                            [$fallbackCenterLng + 0.00120, $fallbackCenterLat + 0.00005],
                        ]],
                    ],
                ],
                [
                    'type' => 'Feature',
                    'properties' => [
                        'lot_number' => '103',
                        'area' => 510,
                        'usage' => 'سكني',
                        'status' => 'sold',
                        'price' => 610000,
                    ],
                    'geometry' => [
                        'type' => 'Polygon',
                        'coordinates' => [[
                            [$fallbackCenterLng + 0.00175, $fallbackCenterLat + 0.00040],
                            [$fallbackCenterLng + 0.00225, $fallbackCenterLat + 0.00040],
                            [$fallbackCenterLng + 0.00225, $fallbackCenterLat + 0.00010],
                            [$fallbackCenterLng + 0.00175, $fallbackCenterLat + 0.00010],
                            [$fallbackCenterLng + 0.00175, $fallbackCenterLat + 0.00040],
                        ]],
                    ],
                ],
                [
                    'type' => 'Feature',
                    'properties' => [
                        'lot_number' => '104',
                        'area' => 720,
                        'usage' => 'خدمات',
                        'status' => 'available',
                        'price' => 990000,
                    ],
                    'geometry' => [
                        'type' => 'Polygon',
                        'coordinates' => [[
                            [$fallbackCenterLng + 0.00175, $fallbackCenterLat + 0.00005],
                            [$fallbackCenterLng + 0.00225, $fallbackCenterLat + 0.00005],
                            [$fallbackCenterLng + 0.00225, $fallbackCenterLat - 0.00025],
                            [$fallbackCenterLng + 0.00175, $fallbackCenterLat - 0.00025],
                            [$fallbackCenterLng + 0.00175, $fallbackCenterLat + 0.00005],
                        ]],
                    ],
                ],
            ];
        }

        return view('public.plans.ajlan', [
            'centerLat' => $centerLat,
            'centerLng' => $centerLng,
            'planNumber' => $planNumber,
            'planAreaKm2' => $planAreaKm2,
            'planShadeRadiusMeters' => $planShadeRadiusMeters,
            'geoJson' => $geoJson,
            'whatsappNumber' => $request->string('whatsapp')->toString(),
        ]);
    }

    public function ajlanOsmRoads(Request $request)
    {
        $south = 24.543627000069844;
        $west = 46.81368100000787;
        $north = 24.56727800007028;
        $east = 46.84426200000871;

        $query = "[out:json][timeout:25];(way[\"highway\"]({$south},{$west},{$north},{$east}););out geom;";

        try {
            $resp = Http::timeout(30)->asForm()->post('https://overpass-api.de/api/interpreter', [
                'data' => $query,
            ]);

            if (!$resp->ok()) {
                return response()->json([
                    'ok' => false,
                    'status' => $resp->status(),
                ], 502);
            }

            return response($resp->body(), 200)
                ->header('Content-Type', 'application/json');
        } catch (\Throwable $e) {
            Log::warning('Overpass proxy failed', ['error' => $e->getMessage()]);
            return response()->json(['ok' => false], 502);
        }
    }

    /**
     * البحث المتقدم
     */
    public function advanced(Request $request)
    {
        $categories = Category::all();
        $features = Feature::all();
        $attributes = Attribute::all();
        $statuses = Status::all();

        return view('public.search.advanced', compact('categories', 'features', 'attributes', 'statuses'));
    }

    /**
     * البحث بالخريطة
     */
    public function map(Request $request)
    {
        $searchType = $request->get('search_type', 'projects');
        
        if ($searchType === 'facilities') {
            $query = Facility::with(['facilityCategory'])
                ->where('is_active', true)
                ->where('is_verified', true)
                ->whereNotNull('latitude')
                ->whereNotNull('longitude');

            // فلترة حسب الفئة
            if ($request->has('category_id') && $request->category_id) {
                $query->where('facility_category_id', $request->category_id);
            }

            $facilities = $query->get();

            $mapData = $facilities->map(function ($facility) {
                return [
                    'id' => $facility->id,
                    'name' => $facility->name,
                    'price' => null,
                    'address' => $facility->address,
                    'latitude' => $facility->latitude,
                    'longitude' => $facility->longitude,
                    'category' => $facility->facilityCategory->name ?? 'No Category',
                    'facility' => $facility->name,
                    'image' => $facility->logo,
                    'url' => route('public.facilities.show', $facility->id),
                    'type' => 'facility'
                ];
            });
        } else {
            $query = ExecutionRequest::query()
                ->with(['translations', 'project'])
                ->where('status', 'open')
                ->whereHas('project', function ($q) {
                    $q->whereNotNull('latitude')
                      ->whereNotNull('longitude');
                });

            if ($search = $request->get('q')) {
                $query->whereHas('translations', function ($q) use ($search) {
                    $q->where(function ($tq) use ($search) {
                        $tq->where('title', 'like', "%{$search}%")
                           ->orWhere('description', 'like', "%{$search}%");
                    });
                });
            }

            $minBudget = $request->get('min_budget');
            $maxBudget = $request->get('max_budget');
            if ($minBudget !== null && $minBudget !== '') {
                $query->where(function ($q) use ($minBudget) {
                    $q->whereNull('budget_max')
                      ->orWhere('budget_max', '>=', $minBudget);
                });
            }
            if ($maxBudget !== null && $maxBudget !== '') {
                $query->where(function ($q) use ($maxBudget) {
                    $q->whereNull('budget_min')
                      ->orWhere('budget_min', '<=', $maxBudget);
                });
            }

            $requests = $query->get();

            $mapData = $requests->map(function (ExecutionRequest $executionRequest) {
                $project = $executionRequest->project;
                $translation = $executionRequest->translations->firstWhere('locale', app()->getLocale())
                    ?? $executionRequest->translations->first();

                return [
                    'id' => $executionRequest->id,
                    'name' => $translation->title ?? ('طلب #' . $executionRequest->id),
                    'price' => $executionRequest->budget_min,
                    'address' => $project->address ?? '',
                    'latitude' => $project->latitude,
                    'longitude' => $project->longitude,
                    'category' => 'مشروع',
                    'facility' => $executionRequest->facility_id ? ('منشأة #' . $executionRequest->facility_id) : '',
                    'image' => null,
                    'url' => route('public.execution.show', $executionRequest->id),
                    'type' => 'project'
                ];
            });
        }

        $categories = $searchType === 'facilities' ? Category::all() : collect();

        return view('public.search.map', compact('mapData', 'categories'));
    }

    /**
     * البحث السريع (AJAX)
     */
    public function quickSearch(Request $request)
    {
        $query = $request->get('q', '');
        $type = $request->get('type', 'products');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        if ($type === 'facilities') {
            $results = Facility::where('is_active', true)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', '%' . $query . '%')
                      ->orWhere('address', 'like', '%' . $query . '%');
                })
                ->take(5)
                ->get(['id', 'name', 'address', 'logo'])
                ->map(function ($facility) {
                    return [
                        'id' => $facility->id,
                        'name' => $facility->name,
                        'address' => $facility->address,
                        'image' => $facility->logo,
                        'url' => route('public.facilities.show', $facility->id),
                        'type' => 'facility'
                    ];
                });
        } else {
            $locale = app()->getLocale();
            $results = Product::with(['translations'])
                ->where('is_active', true)
                ->where(function ($q) use ($query, $locale) {
                    $q->whereHas('translations', function($translationQuery) use ($query, $locale) {
                        $translationQuery->where('locale', $locale)
                            ->where('title', 'like', '%' . $query . '%');
                    })
                    ->orWhere('address', 'like', '%' . $query . '%');
                })
                ->take(5)
                ->get(['id', 'address', 'price', 'image'])
                ->map(function ($product) use ($locale) {
                    $title = $product->translations->where('locale', $locale)->first()->title ?? $product->translations->first()->title ?? 'No Title';
                    return [
                        'id' => $product->id,
                        'name' => $title,
                        'address' => $product->address,
                        'price' => $product->price,
                        'image' => $product->image,
                        'url' => route('public.products.show', $product->id),
                        'type' => 'product'
                    ];
                });
        }

        return response()->json($results);
    }

    /**
     * البحث في الفئات
     */
    public function searchByCategory(Category $category, Request $request)
    {
        $query = $category->products()
            ->with(['facility', 'statuses', 'offers'])
            ->where('is_active', true)
            ->withActiveOffers();

        // تطبيق الفلاتر الإضافية
        if ($request->has('min_price') && $request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        $products = $query->paginate(12);

        return view('public.search.category', compact('category', 'products'));
    }

    /**
     * البحث في المنطقة
     */
    public function searchByArea(Request $request)
    {
        $area = $request->get('area', '');

        $query = Product::with(['facility', 'category', 'offers'])
            ->where('is_active', true)
            ->where('address', 'like', '%' . $area . '%')
            ->withActiveOffers();

        $products = $query->paginate(12);

        return view('public.search.area', compact('area', 'products'));
    }

    public function search(Request $request)
    {
        $query = Product::with(['facility', 'category', 'features'])
            ->where('is_active', true)
            ->where('is_verified', true);

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by facility
        if ($request->has('facility_id')) {
            $query->where('facility_id', $request->facility_id);
        }

        // Filter by price range
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter by property type
        if ($request->has('property_type')) {
            $query->where('property_type', $request->property_type);
        }

        // Filter by rooms
        if ($request->has('rooms')) {
            $query->where('rooms', $request->rooms);
        }

        // Filter by area range
        if ($request->has('min_area')) {
            $query->where('area', '>=', $request->min_area);
        }

        if ($request->has('max_area')) {
            $query->where('area', '<=', $request->max_area);
        }

        // Search by keyword
        if ($request->has('q') && $request->q) {
            $locale = app()->getLocale();
            $query->where(function($q) use ($request, $locale) {
                $q->whereHas('translations', function($translationQuery) use ($request, $locale) {
                    $translationQuery->where('locale', $locale)
                        ->where(function($tq) use ($request) {
                            $tq->where('title', 'like', '%' . $request->q . '%')
                               ->orWhere('description', 'like', '%' . $request->q . '%');
                        });
                })
                ->orWhere('address', 'like', '%' . $request->q . '%');
            });
        }

        // Sort results
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $query->orderBy($sortBy, $sortOrder);

        $products = $query->paginate(12);

        $categories = Category::where('is_active', true)->get();
        $facilities = Facility::where('is_active', true)->where('is_verified', true)->get();
        $features = Feature::where('is_active', true)->get();

        return view('public.search.results', compact('products', 'categories', 'facilities', 'features'));
    }
}
