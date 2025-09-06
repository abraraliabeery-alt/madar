<?php

namespace App\Helpers;

use Illuminate\Http\Request;

class SearchHelper
{
    /**
     * Build a route with current search parameters
     *
     * @param string $routeName
     * @param array $additionalParams
     * @param Request|null $request
     * @return string
     */
    public static function buildSearchRoute($routeName, $additionalParams = [], $request = null)
    {
        $request = $request ?: request();
        $currentParams = $request->query();
        
        // Merge additional parameters with current ones
        $params = array_merge($currentParams, $additionalParams);
        
        return route($routeName, $params);
    }
    
    /**
     * Build a URL with current search parameters
     *
     * @param string $path
     * @param array $additionalParams
     * @param Request|null $request
     * @return string
     */
    public static function buildSearchUrl($path, $additionalParams = [], $request = null)
    {
        $request = $request ?: request();
        $currentParams = $request->query();
        
        // Merge additional parameters with current ones
        $params = array_merge($currentParams, $additionalParams);
        
        return url($path) . '?' . http_build_query($params);
    }
    
    /**
     * Get current search parameters as array
     *
     * @param Request|null $request
     * @return array
     */
    public static function getCurrentSearchParams($request = null)
    {
        $request = $request ?: request();
        return $request->query();
    }
    
    /**
     * Get current search parameters as query string
     *
     * @param Request|null $request
     * @return string
     */
    public static function getCurrentSearchQueryString($request = null)
    {
        $request = $request ?: request();
        return http_build_query($request->query());
    }
    
    /**
     * Check if a search parameter exists
     *
     * @param string $key
     * @param Request|null $request
     * @return bool
     */
    public static function hasSearchParam($key, $request = null)
    {
        $request = $request ?: request();
        return $request->has($key);
    }
    
    /**
     * Get a search parameter value
     *
     * @param string $key
     * @param mixed $default
     * @param Request|null $request
     * @return mixed
     */
    public static function getSearchParam($key, $default = null, $request = null)
    {
        $request = $request ?: request();
        return $request->get($key, $default);
    }
    
    /**
     * Remove a search parameter from current parameters
     *
     * @param string|array $keys
     * @param Request|null $request
     * @return array
     */
    public static function removeSearchParam($keys, $request = null)
    {
        $request = $request ?: request();
        $params = $request->query();
        
        $keys = is_array($keys) ? $keys : [$keys];
        
        foreach ($keys as $key) {
            unset($params[$key]);
        }
        
        return $params;
    }
    
    /**
     * Add or update a search parameter
     *
     * @param string $key
     * @param mixed $value
     * @param Request|null $request
     * @return array
     */
    public static function addSearchParam($key, $value, $request = null)
    {
        $request = $request ?: request();
        $params = $request->query();
        $params[$key] = $value;
        
        return $params;
    }
    
    /**
     * Build search form action URL with current parameters
     *
     * @param string $routeName
     * @param array $excludeParams
     * @param Request|null $request
     * @return string
     */
    public static function buildSearchFormAction($routeName, $excludeParams = [], $request = null)
    {
        $request = $request ?: request();
        $params = $request->query();
        
        // Remove excluded parameters
        foreach ($excludeParams as $param) {
            unset($params[$param]);
        }
        
        return route($routeName, $params);
    }
    
    /**
     * Get search type from current request
     *
     * @param Request|null $request
     * @return string
     */
    public static function getSearchType($request = null)
    {
        $request = $request ?: request();
        return $request->get('search_type', 'products');
    }
    
    /**
     * Check if current search is for products
     *
     * @param Request|null $request
     * @return bool
     */
    public static function isProductSearch($request = null)
    {
        return self::getSearchType($request) === 'products';
    }
    
    /**
     * Check if current search is for facilities
     *
     * @param Request|null $request
     * @return bool
     */
    public static function isFacilitySearch($request = null)
    {
        return self::getSearchType($request) === 'facilities';
    }
    
    /**
     * Get search results count text
     *
     * @param int $count
     * @param string $type
     * @return string
     */
    public static function getResultsCountText($count, $type = 'properties')
    {
        if ($count === 0) {
            return __('public.search.no_results_found');
        }
        
        if ($count === 1) {
            return __('public.search.one_result_found');
        }
        
        return __('public.search.results_found', ['count' => $count, 'type' => $type]);
    }
    
    /**
     * Get search sort options
     *
     * @param string $type
     * @return array
     */
    public static function getSortOptions($type = 'products')
    {
        if ($type === 'facilities') {
            return [
                'latest' => __('public.advanced_search.latest'),
                'name_asc' => __('public.common.name') . ': A to Z',
                'name_desc' => __('public.common.name') . ': Z to A',
                'rating' => __('public.common.rating'),
                'oldest' => __('public.advanced_search.oldest'),
            ];
        }
        
        return [
            'latest' => __('public.advanced_search.latest'),
            'price_low' => __('public.advanced_search.price_low_high'),
            'price_high' => __('public.advanced_search.price_high_low'),
            'area_low' => __('public.advanced_search.area_small_large'),
            'area_high' => __('public.advanced_search.area_large_small'),
            'oldest' => __('public.advanced_search.oldest'),
        ];
    }
    
    /**
     * Get search radius options
     *
     * @return array
     */
    public static function getRadiusOptions()
    {
        return [
            '1' => '1 km',
            '5' => '5 km',
            '10' => '10 km',
            '25' => '25 km',
            '50' => '50 km',
        ];
    }
    
    /**
     * Format price for display
     *
     * @param float $price
     * @param string $currency
     * @param string $locale
     * @return string
     */
    public static function formatPrice($price, $currency = 'SAR', $locale = 'ar-SA')
    {
        return number_format($price, 0) . ' ' . $currency;
    }
    
    /**
     * Get search breadcrumb data
     *
     * @param string $currentPage
     * @param Request|null $request
     * @return array
     */
    public static function getSearchBreadcrumbs($currentPage, $request = null)
    {
        $request = $request ?: request();
        $breadcrumbs = [
            [
                'name' => __('public.navigation.home'),
                'url' => route('public.home')
            ],
            [
                'name' => __('public.navigation.search'),
                'url' => route('public.search')
            ]
        ];
        
        switch ($currentPage) {
            case 'products':
                $breadcrumbs[] = [
                    'name' => __('public.navigation.products'),
                    'url' => route('public.search.products', $request->query())
                ];
                break;
            case 'facilities':
                $breadcrumbs[] = [
                    'name' => __('public.navigation.facilities'),
                    'url' => route('public.search.facilities', $request->query())
                ];
                break;
            case 'advanced':
                $breadcrumbs[] = [
                    'name' => __('public.search.advanced_search'),
                    'url' => route('public.search.advanced', $request->query())
                ];
                break;
            case 'map':
                $breadcrumbs[] = [
                    'name' => __('public.search.map_search'),
                    'url' => route('public.search.map', $request->query())
                ];
                break;
        }
        
        return $breadcrumbs;
    }
}
