<?php

namespace Modules\KnowledgeBase\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KnowledgeBaseAttachment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function newFactory()
    {
        return \Modules\KnowledgeBase\Database\factories\KnowledgeBaseAttachmentFactory::new();
    }
}
