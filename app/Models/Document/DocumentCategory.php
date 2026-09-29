<?php

namespace App\Models\Document;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentCategory extends Model
{
    protected $table = 'document_categories_v1';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'name_ar',
        'code',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function documents(): HasMany
    {
        return $this->hasMany(
            Document::class,
            'category_id',
            'id'
        );
    }
}
