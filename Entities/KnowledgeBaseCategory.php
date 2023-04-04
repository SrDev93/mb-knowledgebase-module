<?php

namespace Modules\KnowledgeBase\Entities;

use App\Models\Brand;
use App\Models\Language;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KnowledgeBaseCategory extends Model
{
    use HasFactory;
    use Sluggable;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function parent()
    {
        return $this->hasOne(KnowledgeBaseCategory::class, 'parent_id');
    }

    public function brand()
    {
        return $this->hasOne(Brand::class, 'id', 'brand_id');
    }

    public function language()
    {
        return $this->hasOne(Language::class, 'lang', 'lang');
    }

    public function children()
    {
        return $this->hasMany(KnowledgeBaseCategory::class, 'parent_id')->with('children');
    }

    public function KnowledgeBases() {
        return $this->hasMany(KnowledgeBase::class, 'category_id', 'id');
    }

    protected static function newFactory()
    {
        return \Modules\KnowledgeBase\Database\factories\KnowledgeBaseCategoryFactory::new();
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}
