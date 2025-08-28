<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            // Color Scheme
            $table->string('primary_color', 7)->default('#2563eb')->after('website');
            $table->string('secondary_color', 7)->default('#1e40af')->after('primary_color');
            $table->string('accent_color', 7)->default('#f59e0b')->after('secondary_color');
            $table->string('background_color', 7)->default('#ffffff')->after('accent_color');
            $table->string('text_color', 7)->default('#374151')->after('background_color');
            $table->string('secondary_text_color', 7)->default('#6b7280')->after('text_color');
            
            // Typography
            $table->enum('font_family', ['figtree', 'inter', 'poppins', 'roboto', 'open-sans', 'lato'])->default('figtree')->after('secondary_text_color');
            
            // Hero Section
            $table->enum('hero_background_type', ['gradient', 'color', 'image'])->default('gradient')->after('font_family');
            $table->text('hero_background_value')->nullable()->after('hero_background_type'); // hex color or image URL
            $table->string('hero_overlay_opacity', 3)->default('20')->after('hero_background_value'); // 0-100
            
            // Layout & Design
            $table->enum('layout_style', ['modern', 'classic', 'minimal', 'corporate'])->default('modern')->after('hero_overlay_opacity');
            $table->enum('button_style', ['rounded', 'square', 'pill'])->default('rounded')->after('layout_style');
            $table->enum('logo_position', ['left', 'center', 'right'])->default('left')->after('button_style');
            
            // Animation & Effects
            $table->boolean('enable_animations')->default(true)->after('logo_position');
            $table->boolean('enable_parallax')->default(true)->after('enable_animations');
            
            // Custom Styling
            $table->text('custom_css')->nullable()->after('enable_parallax');
            
            // Social Media
            $table->string('facebook_url')->nullable()->after('custom_css');
            $table->string('twitter_url')->nullable()->after('facebook_url');
            $table->string('instagram_url')->nullable()->after('twitter_url');
            $table->string('linkedin_url')->nullable()->after('instagram_url');
            
            // SEO & Meta
            $table->text('meta_keywords')->nullable()->after('linkedin_url');
            $table->text('meta_description')->nullable()->after('meta_keywords');
            
            // Settings
            $table->json('customization_settings')->nullable()->after('meta_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
                // Drop columns only if they exist
        $columnsToDrop = [
            'primary_color',
            'secondary_color', 
            'accent_color',
            'background_color',
            'text_color',
            'secondary_text_color',
            'font_family',
            'hero_background_type',
            'hero_background_value',
            'hero_overlay_opacity',
            'layout_style',
            'button_style',
            'logo_position',
            'enable_animations',
            'enable_parallax',
            'custom_css',
            'facebook_url',
            'twitter_url',
            'instagram_url',
            'linkedin_url',
            'meta_keywords',
            'meta_description',
            'customization_settings'
        ];

        foreach ($columnsToDrop as $column) {
            if (Schema::hasColumn('facilities', $column)) {
                $table->dropColumn($column);
            }
        }
        });
    }
};
