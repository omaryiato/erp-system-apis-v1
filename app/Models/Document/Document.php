<?php

namespace App\Models\Document;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Document extends Model
{
    protected $table = 'documents_v1';

    protected $primaryKey = 'id';

    protected $fillable = [
        'category_id',
        'title',
        'description',
        'document_code',
        'current_version_id',
        'status',
        'reference_type',
        'reference_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'reference_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            DocumentCategory::class,
            'category_id',
            'id'
        );
    }

    public function versions(): HasMany
    {
        return $this->hasMany(
            DocumentVersion::class,
            'document_id',
            'id'
        )->orderByDesc('version_number');
    }

    public function currentVersion(): BelongsTo
    {
        return $this->belongsTo(
            DocumentVersion::class,
            'current_version_id',
            'id'
        );
    }
}
