<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\AttributeTranslation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;

class ApiAttributeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_attributes_by_category_uses_session_locale()
    {
        // Create a category
        $category = Category::factory()->create();

        // Create an attribute
        $attribute = Attribute::factory()->create([
            'category_id' => $category->id,
        ]);

        // Create translations for the attribute
        $arTranslation = AttributeTranslation::factory()->create([
            'attribute_id' => $attribute->id,
            'locale' => 'ar',
            'name' => 'اسم بالعربية',
            'symbol' => 'ر.س'
        ]);

        $enTranslation = AttributeTranslation::factory()->create([
            'attribute_id' => $attribute->id,
            'locale' => 'en',
            'name' => 'Name in English',
            'symbol' => 'SAR'
        ]);

        // Set session locale to Arabic
        Session::put('locale', 'ar');

        $response = $this->getJson("/api/v1/attributes/by-category?category_id={$category->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data' => [
                         [
                             'id' => $attribute->id,
                             'name' => 'اسم بالعربية',
                             'translated_symbol' => 'ر.س'
                         ]
                     ]
                 ]);

        // Change session locale to English
        Session::put('locale', 'en');

        $response = $this->getJson("/api/v1/attributes/by-category?category_id={$category->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data' => [
                         [
                             'id' => $attribute->id,
                             'name' => 'Name in English',
                             'translated_symbol' => 'SAR'
                         ]
                     ]
                 ]);
    }

    public function test_get_all_attributes_uses_session_locale()
    {
        // Create an attribute
        $attribute = Attribute::factory()->create();

        // Create translations for the attribute
        $arTranslation = AttributeTranslation::factory()->create([
            'attribute_id' => $attribute->id,
            'locale' => 'ar',
            'name' => 'اسم بالعربية',
            'symbol' => 'ر.س'
        ]);

        $enTranslation = AttributeTranslation::factory()->create([
            'attribute_id' => $attribute->id,
            'locale' => 'en',
            'name' => 'Name in English',
            'symbol' => 'SAR'
        ]);

        // Set session locale to Arabic
        Session::put('locale', 'ar');

        $response = $this->getJson("/api/v1/attributes");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data' => [
                         [
                             'id' => $attribute->id,
                             'name' => 'اسم بالعربية',
                             'translated_symbol' => 'ر.س'
                         ]
                     ]
                 ]);

        // Change session locale to English
        Session::put('locale', 'en');

        $response = $this->getJson("/api/v1/attributes");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data' => [
                         [
                             'id' => $attribute->id,
                             'name' => 'Name in English',
                             'translated_symbol' => 'SAR'
                         ]
                     ]
                 ]);
    }
}
