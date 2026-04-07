<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Archive extends Model
{
    protected $fillable = [
        'user_id',
        'document_id',
        'category_id',
        'document_title',
        'document_type',
        'action',
        'old_data',
        'new_data',
        'description',
        'ip_address',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeByAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    public function scopeByDocumentType($query, string $type)
    {
        return $query->where('document_type', $type);
    }

    public function getFormattedTimeAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    public function getActionColorAttribute(): string
    {
        return match($this->action) {
            'created' => 'green',
            'edited' => 'blue',
            'deleted' => 'red',
            default => 'gray'
        };
    }

    public function getActionIconAttribute(): string
    {
        return match($this->action) {
            'created' => 'fas fa-plus',
            'edited' => 'fas fa-edit',
            'deleted' => 'fas fa-trash',
            default => 'fas fa-info-circle'
        };
    }

    /**
     * Log an action to archive (for system use)
     */
    public static function logAction($userId, $action, $documentTitle, $documentType, $oldData = null, $newData = null, $description = null, $ipAddress = null, $documentId = null, $categoryId = null)
    {
        return Archive::create([
            'user_id' => $userId,
            'document_id' => $documentId,
            'category_id' => $categoryId,
            'action' => $action,
            'document_title' => $documentTitle,
            'document_type' => $documentType,
            'old_data' => $oldData,
            'new_data' => $newData,
            'description' => $description,
            'ip_address' => $ipAddress ?? request()->ip(),
            'created_at' => now(),
        ]);
    }
}
