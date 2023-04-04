<?php

namespace Modules\KnowledgeBase\Entities;

use App\Models\Brand;
use App\Models\Language;
use App\Models\User;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Base\Entities\Comment;
use Modules\Base\Entities\Visit;

class KnowledgeBase extends Model
{
    use HasFactory;
    use Sluggable;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function category()
    {
        return $this->belongsTo(KnowledgeBaseCategory::class, 'category_id');
    }

    public function attachments()
    {
        return $this->hasMany(KnowledgeBaseAttachment::class, 'knowledge_base_id', 'id');
    }

    public function brand()
    {
        return $this->hasOne(Brand::class, 'id', 'brand_id');
    }

    public function language()
    {
        return $this->hasOne(Language::class, 'lang', 'lang');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comment()
    {
        return $this->morphMany(Comment::class, 'comments')->where('status', 1);
    }

    public function visits() {
        return $this->morphMany(Visit::class, 'visits');
    }

    protected static function newFactory()
    {
        return \Modules\KnowledgeBase\Database\factories\KnowledgeBaseFactory::new();
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
}
