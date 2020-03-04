<?php

namespace App;

use App\Filters\ThreadFilters;
use Illuminate\Database\Eloquent\Model;

/**
 * \App\Thread
 *
 * @property int $id
 * @property int $user_id
 * @property int $channel_id
 * @property-read int|null $replies_count
 * @property string $title
 * @property string $body
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Activity[] $activity
 * @property-read int|null $activity_count
 * @property-read \App\Channel $channel
 * @property-read \App\User $creator
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Reply[] $replies
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread filter(\App\Filters\ThreadFilters $filters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread whereChannelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread whereRepliesCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread whereUserId($value)
 * @mixin \Eloquent
 */
class Thread extends Model
{
    use RecordsActivity;

    protected $guarded = [];

    protected $with = ['creator', 'channel'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function (self $thread) {
            $thread->replies->each->delete();
        });
    }

    public function path(): string
    {
        return '/threads/' . $this->channel->slug . '/' . $this->id;
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function addReply($reply)
    {
        return $this->replies()->create($reply);
    }

    public function scopeFilter($query, ThreadFilters $filters)
    {
        return $filters->apply($query);
    }
}
