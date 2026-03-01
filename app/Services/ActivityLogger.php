<?php

namespace Vanguard\Services;

use Vanguard\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    protected $logName;
    protected $description;
    protected $subject;
    protected $properties = [];
    protected $causer;

    public function __construct()
    {
        $this->causer = auth()->user();
    }

    /**
     * Set the log name.
     */
    public function inLog($logName)
    {
        $this->logName = $logName;
        return $this;
    }

    /**
     * Set the log description.
     */
    public function withDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Set the subject.
     */
    public function onSubject(Model $subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * Set properties.
     */
    public function withProperties(array $properties)
    {
        $this->properties = $properties;
        return $this;
    }

    /**
     * Set causer (user who performed the action).
     */
    public function causedBy($causer)
    {
        $this->causer = $causer;
        return $this;
    }

    /**
     * Log the activity.
     */
    public function log()
    {
        $log = new ActivityLog();
        
        $log->log_name = $this->logName ?? 'default';
        $log->description = $this->description;
        
        if ($this->subject) {
            $log->subject_type = get_class($this->subject);
            $log->subject_id = $this->subject->getKey();
        }
        
        if ($this->causer) {
            $log->causer_type = get_class($this->causer);
            $log->causer_id = $this->causer->getKey();
        }
        
        $log->properties = $this->properties;
        $log->ip_address = Request::ip();
        $log->user_agent = Request::userAgent();
        
        $log->save();
        
        return $log;
    }

    /**
     * Static method to create a new instance.
     */
    public static function make()
    {
        return new static();
    }
}