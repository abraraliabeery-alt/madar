<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AIKnowledge extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'source',
    ];

    public function chunks()
    {
        return $this->hasMany(AIKnowledgeChunk::class, 'knowledge_id');
    }
}
