<?php

namespace App\Models\Document;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentCategory extends Model
{
    protected $table = 'document_categories';

    protected $primaryKey = 'id';

    protected $fillable = [
        'parent_id',
        'name',
        'name_ar',
        'code',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(
            DocumentCategory::class,
            'parent_id',
            'id'
        );
    }

    public function children(): HasMany
    {
        return $this->hasMany(
            DocumentCategory::class,
            'parent_id',
            'id'
        );
    }

    public function documents(): HasMany
    {
        return $this->hasMany(
            Document::class,
            'category_id',
            'id'
        );
    }
}
