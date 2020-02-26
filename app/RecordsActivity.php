<?php

namespace App;

trait RecordsActivity
{
    protected static function bootRecordsActivity()
    {
        static::created(function ($model) {
            $model->recordActivity('created');
        });
    }

    protected function recordActivity(string $event)
    {
        $this->activity()->create([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event),
        ]);
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    protected function getActivityType(string $event): string
    {
        return $event . '_' . strtolower((new \ReflectionClass($this))->getShortName());
    }
}
