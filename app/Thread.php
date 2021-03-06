<?php

namespace App;

use App\Events\ThreadReceiveNewReply;
use App\Filters\ThreadFilters;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Str;

/**
 * App\Thread
 *
 * @property int $id
 * @property int $user_id
 * @property int $channel_id
 * @property-read int|null $replies_count
 * @property int $visits
 * @property string $title
 * @property string|null $slug
 * @property string $body
 * @property int|null $best_reply_id
 * @property bool $locked
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Activity[] $activity
 * @property-read int|null $activity_count
 * @property-read \App\Channel $channel
 * @property-read \App\User $creator
 * @property-read mixed $is_subscribed_to
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Reply[] $replies
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ThreadSubscription[] $subscriptions
 * @property-read int|null $subscriptions_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread filter(\App\Filters\ThreadFilters $filters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread whereBestReplyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread whereChannelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread whereLocked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread whereRepliesCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread whereVisits($value)
 * @mixin \Eloquent
 */
class Thread extends Model
{
    use RecordsActivity, Searchable;

    protected $guarded = [];

    protected $with = ['creator', 'channel'];

    protected $appends = ['isSubscribedTo'];

    protected $casts = [
        'locked' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function (self $thread) {
            $thread->replies->each->delete();
        });

        static::created(function (self $thread) {
            $thread->update(['slug' => $thread->title]);
        });
    }

    public function path(): string
    {
        return "/threads/{$this->channel->slug}/{$this->slug}";
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

    public function addReply(array $attributes)
    {
        /** @var Reply $reply */
        $reply = $this->replies()->create($attributes);

        event(new ThreadReceiveNewReply($reply));

        return $reply;
    }

    public function locks()
    {
        $this->update(['locked' => true]);
    }

    public function unlocks()
    {
        $this->update(['locked' => false]);
    }

    public function scopeFilter($query, ThreadFilters $filters)
    {
        return $filters->apply($query);
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id(),
        ]);

        return $this;
    }

    public function unsubscribe($userId = null)
    {
        $this->subscriptions()
            ->where('user_id', $userId ?: auth()->id())
            ->delete();
    }

    public function getIsSubscribedToAttribute(): bool
    {
        return (bool)$this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }

    /**
     * @param User $user
     * @return bool
     * @throws \Exception
     */
    public function hasUpdatesFor($user): bool
    {
        return $this->updated_at > cache($user->visitedThreadCacheKey($this));
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function setSlugAttribute($value)
    {
        $slug = Str::slug($value);

        while (static::whereSlug($slug)->exists()) {
            $slug = "$slug-" . $this->id;
        }

        $this->attributes['slug'] = $slug;
    }

    public function markBestReply(Reply $reply)
    {
        $this->update(['best_reply_id' => $reply->id]);
    }

    public function toSearchableArray()
    {
        return $this->toArray() + ['path' => $this->path()];
    }
}
