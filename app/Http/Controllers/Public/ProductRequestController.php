<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\ProductRequest;
use App\Models\Product;

class ProductRequestController extends Controller
{
    public function form()
    {
        return view('public.product-requests.form');
    }

    public function analyze(Request $request)
    {
        $data = $request->validate([
            'description' => 'required|string|max:4000',
        ]);

        $extracted = $this->extractWithAi($data['description']);

        return response()->json([
            'extracted' => $extracted,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'description' => 'required|string|max:4000',
        ]);

        $extracted = $this->extractWithAi($data['description']);

        $requestRecord = ProductRequest::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'description' => $data['description'],
            'extracted' => $extracted,
            'status' => 'new',
        ]);

        $matches = $this->findMatches($extracted);

        return response()->json([
            'request' => $requestRecord,
            'matches' => $matches,
            'message' => 'تم استلام طلبك بنجاح، وهذه أبرز النتائج المطابقة.',
        ]);
    }

    private function extractWithAi(string $description): array
    {
        $apiKey = config('ai_chat.providers.openai.api_key');
        $base = rtrim(config('ai_chat.providers.openai.base_url', 'https://api.openai.com/v1'), '/');
        $model = config('ai_chat.providers.openai.model');

        if (!$apiKey || !$model) {
            return $this->fallbackExtract($description);
        }

        try {
            $response = Http::withToken($apiKey)
                ->timeout(120)
                ->post($base.'/chat/completions', [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => $this->extractionPrompt()],
                        ['role' => 'user', 'content' => $description],
                    ],
                    'response_format' => ['type' => 'json_object'],
                ]);

            $json = $response->json();
            $content = $json['choices'][0]['message']['content'] ?? '';
            $parsed = json_decode($content, true);

            return is_array($parsed) ? $parsed : $this->fallbackExtract($description);
        } catch (\Throwable $e) {
            \Log::warning('Product request AI extraction failed: '.$e->getMessage());
            return $this->fallbackExtract($description);
        }
    }

    private function extractionPrompt(): string
    {
        return 'You extract product-request details from Arabic or English user text. Return only a JSON object with these keys and values in Arabic where relevant: type (product/category name string), city (string), neighborhood (string), min_price (number or null), max_price (number or null), rooms (integer or null), bathrooms (integer or null), area (number or null), purpose ("buy" / "rent" / "service" / null), budget (number or null), notes (string). If a value is missing, use null. Keep the output strictly as JSON.';
    }

    private function fallbackExtract(string $description): array
    {
        return [
            'type' => null,
            'city' => null,
            'neighborhood' => null,
            'min_price' => null,
            'max_price' => null,
            'rooms' => null,
            'bathrooms' => null,
            'area' => null,
            'purpose' => null,
            'budget' => null,
            'notes' => $description,
        ];
    }

    private function findMatches(array $extracted): array
    {
        $type = $extracted['type'] ?? null;
        $city = $extracted['city'] ?? null;
        $minPrice = $extracted['min_price'] ?? $extracted['budget'] ?? null;
        $maxPrice = $extracted['max_price'] ?? null;
        $rooms = $extracted['rooms'] ?? null;

        $query = Product::query()
            ->where('is_active', true)
            ->with(['category', 'city'])
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc');

        if ($minPrice !== null && $maxPrice !== null) {
            $query->whereHas('activeOffers', function ($q) use ($minPrice, $maxPrice) {
                $q->whereBetween('price', [$minPrice, $maxPrice]);
            });
        } elseif ($minPrice !== null) {
            $query->whereHas('activeOffers', function ($q) use ($minPrice) {
                $q->where('price', '>=', $minPrice);
            });
        } elseif ($maxPrice !== null) {
            $query->whereHas('activeOffers', function ($q) use ($maxPrice) {
                $q->where('price', '<=', $maxPrice);
            });
        }

        if ($rooms) {
            $query->where(function ($q) use ($rooms) {
                $q->whereHas('attributes', function ($a) use ($rooms) {
                    $a->where('key', 'bedrooms')->where('product_attribute_values.value', $rooms);
                })->orWhereDoesntHave('attributes', function ($a) {
                    $a->where('key', 'bedrooms');
                });
            });
        }

        if ($type || $city) {
            $query->where(function ($q) use ($type, $city) {
                if ($type) {
                    $q->whereHas('translations', function ($sub) use ($type) {
                        $sub->where('title', 'like', '%'.$type.'%')
                            ->orWhere('description', 'like', '%'.$type.'%');
                    })
                    ->orWhereHas('category.translations', function ($sub) use ($type) {
                        $sub->where('name', 'like', '%'.$type.'%');
                    });
                }
                if ($city) {
                    $q->orWhereHas('city', function ($sub) use ($city) {
                        $sub->where('name', 'like', '%'.$city.'%')
                            ->orWhereHas('translations', function ($t) use ($city) {
                                $t->where('name', 'like', '%'.$city.'%');
                            });
                    })
                    ->orWhereHas('neighborhood', function ($sub) use ($city) {
                        $sub->where('name', 'like', '%'.$city.'%');
                    });
                }
            });
        }

        return $query->limit(6)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'title' => $product->title,
                    'image_url' => $product->image_url,
                    'price' => $product->getFormattedPrice(),
                    'address' => $product->address,
                    'category' => optional($product->category)->name,
                    'url' => route('public.products.show', $product),
                ];
            })
            ->toArray();
    }
}
