<?php

namespace Vanguard\Traits;

use Vanguard\ActivityLog;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    /**
     * Log an activity.
     *
     * @param string $description
     * @param array $properties
     * @param string|null $logName
     * @return ActivityLog
     */
    public function logActivity($description, $properties = [], $logName = null)
    {
        $log = new ActivityLog();
        
        $log->log_name = $logName ?? 'default';
        $log->description = $description;
        $log->subject_type = $this instanceof \Illuminate\Database\Eloquent\Model ? get_class($this) : null;
        $log->subject_id = $this instanceof \Illuminate\Database\Eloquent\Model ? $this->getKey() : null;
        
        if (auth()->check()) {
            $log->causer_type = get_class(auth()->user());
            $log->causer_id = auth()->id();
        }
        
        $log->properties = $properties;
        $log->ip_address = Request::ip();
        $log->user_agent = Request::userAgent();
        
        $log->save();
        
        return $log;
    }

    /**
     * Get all activity logs for this subject.
     */
    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'subject')->latest();
    }

    /**
     * Get the last activity log for this subject.
     */
    public function lastActivity()
    {
        return $this->morphOne(ActivityLog::class, 'subject')->latestOfMany();
    }
}