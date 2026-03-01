<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';
    
    protected $fillable = [
        'log_name',
        'description',
        'subject_type',
        'subject_id',
        'causer_type',
        'causer_id',
        'properties',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the subject that the log belongs to.
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user that caused the activity.
     */
    public function causer(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user that caused the activity (alias for causer).
     */
    public function user()
    {
        return $this->belongsTo(Vanguard\User::class, 'causer_id');
    }

    /**
     * Scope a query to only include logs by log name.
     */
    public function scopeInLog($query, ...$logNames)
    {
        if (is_array($logNames[0])) {
            $logNames = $logNames[0];
        }

        return $query->whereIn('log_name', $logNames);
    }

    /**
     * Scope a query to only include logs for a specific subject.
     */
    public function scopeForSubject($query, Model $subject)
    {
        return $query
            ->where('subject_type', $subject->getMorphClass())
            ->where('subject_id', $subject->getKey());
    }

    /**
     * Scope a query to only include logs caused by a specific user.
     */
    public function scopeCausedBy($query, Model $causer)
    {
        return $query
            ->where('causer_type', $causer->getMorphClass())
            ->where('causer_id', $causer->getKey());
    }
}