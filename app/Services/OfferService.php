<?php

namespace App\Services;

use App\Models\Offer;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OfferService
{
    /**
     * إنشاء عرض جديد
     */
    public function createOffer(array $data)
    {
        return DB::transaction(function () use ($data) {
            // إنشاء العرض
            $offer = Offer::create($data);
            
            // حساب العمولة
            $offer->calculateCommission()->save();
            
            return $offer;
        });
    }

    /**
     * تحديث عرض موجود
     */
    public function updateOffer(Offer $offer, array $data)
    {
        return DB::transaction(function () use ($offer, $data) {
            $offer->update($data);
            
            // إعادة حساب العمولة
            $offer->calculateCommission()->save();
            
            return $offer;
        });
    }

    /**
     * تفعيل/إلغاء تفعيل العرض
     */
    public function toggleOfferStatus(Offer $offer)
    {
        $offer->is_active = !$offer->is_active;
        $offer->save();
        
        return $offer;
    }

    /**
     * الحصول على العروض النشطة لمنتج
     */
    public function getActiveOffersForProduct(Product $product)
    {
        return $product->offers()
            ->active()
            ->valid()
            ->with(['translations'])
            ->get();
    }

    /**
     * الحصول على العروض حسب النوع
     */
    public function getOffersByType(string $type, int $facilityId = null)
    {
        $query = Offer::active()->valid()->byType($type);
        
        if ($facilityId) {
            $query->where('facility_id', $facilityId);
        }
        
        return $query->with(['product', 'translations'])->get();
    }

    /**
     * البحث في العروض
     */
    public function searchOffers(array $filters = [])
    {
        $query = Offer::active()->valid();
        
        if (isset($filters['type'])) {
            $query->byType($filters['type']);
        }
        
        if (isset($filters['facility_id'])) {
            $query->where('facility_id', $filters['facility_id']);
        }
        
        if (isset($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }
        
        if (isset($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }
        
        if (isset($filters['product_id'])) {
            $query->where('product_id', $filters['product_id']);
        }
        
        return $query->with(['product', 'facility', 'translations'])->get();
    }

    /**
     * نسخ عرض من منتج لآخر
     */
    public function copyOffer(Offer $sourceOffer, int $targetProductId)
    {
        return DB::transaction(function () use ($sourceOffer, $targetProductId) {
            $newOffer = $sourceOffer->replicate();
            $newOffer->product_id = $targetProductId;
            $newOffer->is_active = false; // غير مفعل افتراضياً
            $newOffer->save();
            
            // نسخ الترجمات
            foreach ($sourceOffer->translations as $translation) {
                $newTranslation = $translation->replicate();
                $newTranslation->offer_id = $newOffer->id;
                $newTranslation->save();
            }
            
            return $newOffer;
        });
    }

    /**
     * إنشاء عروض متعددة لمنتج
     */
    public function createMultipleOffersForProduct(Product $product, array $offersData)
    {
        return DB::transaction(function () use ($product, $offersData) {
            $createdOffers = [];
            
            foreach ($offersData as $offerData) {
                $offerData['product_id'] = $product->id;
                $offer = $this->createOffer($offerData);
                $createdOffers[] = $offer;
            }
            
            return $createdOffers;
        });
    }

    /**
     * إحصائيات العروض
     */
    public function getOfferStatistics(int $facilityId = null)
    {
        $query = Offer::query();
        
        if ($facilityId) {
            $query->where('facility_id', $facilityId);
        }
        
        return [
            'total_offers' => $query->count(),
            'active_offers' => $query->clone()->active()->count(),
            'expired_offers' => $query->clone()->where('valid_to', '<', now())->count(),
            'sale_offers' => $query->clone()->byType('sale')->count(),
            'rent_offers' => $query->clone()->whereIn('offer_type', ['rent_monthly', 'rent_yearly', 'rent_daily'])->count(),
            'total_value' => $query->clone()->sum('price'),
        ];
    }

    /**
     * تحديث أسعار العروض
     */
    public function updateOfferPrices(int $facilityId, float $percentage, string $operation = 'increase')
    {
        return DB::transaction(function () use ($facilityId, $percentage, $operation) {
            $offers = Offer::where('facility_id', $facilityId)->active()->get();
            
            foreach ($offers as $offer) {
                if ($operation === 'increase') {
                    $offer->price = $offer->price * (1 + $percentage / 100);
                } else {
                    $offer->price = $offer->price * (1 - $percentage / 100);
                }
                
                $offer->calculateCommission()->save();
            }
            
            return $offers;
        });
    }

    /**
     * تصدير العروض
     */
    public function exportOffers(array $filters = [])
    {
        $offers = $this->searchOffers($filters);
        
        $data = [];
        foreach ($offers as $offer) {
            $data[] = [
                'id' => $offer->id,
                'product_title' => $offer->product->getTranslatedTitle(),
                'offer_type' => $offer->offer_type,
                'price' => $offer->price,
                'currency' => 'SAR',
                'deposit_amount' => $offer->deposit_amount,
                'commission_rate' => $offer->commission_rate,
                'commission_amount' => $offer->commission_amount,
                'is_active' => $offer->is_active,
                'valid_from' => $offer->valid_from,
                'valid_to' => $offer->valid_to,
                'facility_name' => $offer->facility->name ?? '',
                'created_at' => $offer->created_at,
            ];
        }
        
        return $data;
    }
}
