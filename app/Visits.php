<?php

namespace App;

use Illuminate\Support\Facades\Redis;

class Visits
{
    /**
     * @var Thread
     */
    protected $thread;

    public function __construct(Thread $thread)
    {
        $this->thread = $thread;
    }

    public function reset()
    {
        Redis::del($this->cacheKey());

        return $this;
    }

    public function count()
    {
        return Redis::get($this->cacheKey()) ?? 0;
    }

    public function record()
    {
        Redis::incr($this->cacheKey());

        return $this;
    }

    /**
     * @return string
     */
    protected function cacheKey(): string
    {
        return "threads.{$this->thread->id}.visits";
    }
}
