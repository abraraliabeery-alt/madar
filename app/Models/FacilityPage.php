<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FacilityPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'facility_id','title','slug','content_html','meta','is_home','status','published_at'
    ];

    protected $casts = [
        'meta' => 'array',
        'is_home' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function facility(){ return $this->belongsTo(Facility::class);}    

    protected static function boot()
    {
        parent::boot();
        static::creating(function($model){
            if (empty($model->slug)) {
                $base = Str::slug($model->title);
                $slug = $base; $i=1;
                while(static::where('facility_id',$model->facility_id)->where('slug',$slug)->exists()){
                    $slug = $base.'-'.($i++);
                }
                $model->slug = $slug;
            }
            if (empty($model->status)) {
                $model->status = 'published';
            }
        });
    }
}
