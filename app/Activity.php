<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * \App\Activity
 *
 * @property int $id
 * @property int $user_id
 * @property int $subject_id
 * @property string $subject_type
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Activity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Activity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Activity query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Activity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Activity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Activity whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Activity whereSubjectType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Activity whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Activity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Activity whereUserId($value)
 * @mixin \Eloquent
 */
class Activity extends Model
{
    protected $guarded = [];

    public function subject()
    {
        return $this->morphTo();
    }
}
