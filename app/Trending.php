<?php

namespace App;

use Illuminate\Support\Facades\Redis;

class Trending
{
    public function cacheKey()
    {
        return app()->environment('testing') ? 'testing_trending_threads' : 'trending_threads';
    }

    public function get()
    {
        return $this->isRedisOn() ? array_map('json_decode', Redis::zrevrange($this->cacheKey(), 0, 4)) : [];
    }

    public function push(Thread $thread)
    {
        Redis::zincrby($this->cacheKey(), 1, json_encode([
            'title' => $thread->title,
            'path' => $thread->path(),
        ]));
    }

    public function reset()
    {
        Redis::del($this->cacheKey());
    }

    public function isRedisOn(): bool
    {
        try {
            $redis = Redis::connect(config('database.redis.default.host'), config('database.redis.default.port'));

            return true;
        } catch (\Predis\Connection\ConnectionException $e) {
            return false;
        }
    }
}
