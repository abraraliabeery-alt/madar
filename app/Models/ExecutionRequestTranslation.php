<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExecutionRequestTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'execution_request_id',
        'locale',
        'title',
        'description',
    ];

    public function executionRequest()
    {
        return $this->belongsTo(ExecutionRequest::class);
    }
}
