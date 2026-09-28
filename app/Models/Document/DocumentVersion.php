<?php

namespace App\Models\Document;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentVersion extends Model
{
    protected $table = 'document_versions_v1';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'document_id',
        'version_number',
        'file_name',
        'original_file_name',
        'file_path',
        'storage_disk',
        'mime_type',
        'file_extension',
        'file_size',
        'file_hash',
        'description',
        'uploaded_by',
        'created_at',
    ];

    protected $casts = [
        'version_number' => 'integer',
        'file_size' => 'integer',
        'uploaded_by' => 'integer',
        'created_at' => 'datetime',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(
            Document::class,
            'document_id',
            'id'
        );
    }
}
