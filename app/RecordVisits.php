<?php

namespace App;

use Illuminate\Support\Facades\Redis;

trait RecordVisits
{
    public function recordVisit(): self
    {
        Redis::incr($this->visitCacheKey());

        return $this;
    }

    public function visits()
    {
        return Redis::get($this->visitCacheKey()) ?? 0;
    }

    public function resetVisits(): self
    {
        Redis::del($this->visitCacheKey());

        return $this;
    }

    /**
     * @return string
     */
    protected function visitCacheKey(): string
    {
        return "threads.{$this->id}.visits";
    }
}
