<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PdfSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'font_base_px'  => 'required|integer|min:10|max:40',
            'font_title_px' => 'required|integer|min:12|max:60',
            'font_value_px' => 'required|integer|min:10|max:60',

            'style' => 'array',
            'style.brand_color'       => 'nullable|string|max:30',
            'style.accent_color'      => 'nullable|string|max:30',
            'style.bg_color'          => 'nullable|string|max:30',
            'style.title_color'       => 'nullable|string|max:30',
            'style.text_color'        => 'nullable|string|max:30',
            'style.card_radius_mm'    => 'nullable|numeric|min:0|max:20',
            'style.card_padding_mm'   => 'nullable|numeric|min:0|max:30',
            'style.grid_spacing_mm'   => 'nullable|numeric|min:0|max:20',

            'theme_default' => 'nullable|string|in:light,dark',

            'dark_style' => 'array',
            'dark_style.brand_color'    => 'nullable|string|max:30',
            'dark_style.accent_color'   => 'nullable|string|max:30',
            'dark_style.card_color'     => 'nullable|string|max:30',
            'dark_style.bg_color'       => 'nullable|string|max:30',
            'dark_style.title_color'    => 'nullable|string|max:30',
            'dark_style.text_color'     => 'nullable|string|max:30',
            'dark_style.muted_color'    => 'nullable|string|max:30',
            'dark_style.stroke_color'   => 'nullable|string|max:40',

            'slides' => 'array',
            'slides.cover'    => 'nullable|boolean',
            'slides.details'  => 'nullable|boolean',
            'slides.location' => 'nullable|boolean',
            'slides.features' => 'nullable|boolean',
            'slides.offers'   => 'nullable|boolean',
            'slides.cta'      => 'nullable|boolean',

            'slides_order'   => 'array',
            'slides_order.*' => 'string',

            'slide_labels'       => 'array',
            'slide_labels.*'     => 'array',
            'slide_labels.*.*'   => 'nullable|string|max:60',

            'attribute_order'     => 'array',
            'attribute_order.*'   => 'integer',

            'attribute_order_by_category'   => 'array',
            'attribute_order_by_category.*' => 'array',

            'attribute_groups'   => 'array',
            'attribute_groups.*' => 'array',
        ];
    }
}
