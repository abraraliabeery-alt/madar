<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FacilityProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'facility_id','title','slug','excerpt','content','cover_image','gallery','status','order'
    ];

    protected $casts = [
        'gallery' => 'array',
        'order' => 'integer',
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function($model){
            if (empty($model->slug)) {
                $base = Str::slug($model->title);
                $slug = $base;
                $i = 1;
                while (static::where('facility_id', $model->facility_id)->where('slug', $slug)->exists()) {
                    $slug = $base.'-'.($i++);
                }
                $model->slug = $slug;
            }
            if (empty($model->status)) {
                $model->status = 'published';
            }
        });
    }

    public function scopePublished($q)
    {
        return $q->where('status', 'published');
    }
}
