<?php

namespace App;

trait Favoritable
{
    protected static function bootFavoritable()
    {
        static::deleting(function ($model) {
//            $model->favorites()->delete(); // this is only sql-query

            /** below is a collection of models, then fire static::deleting for all activities
             * @see \App\RecordsActivity::bootRecordsActivity()
             */
            $model->favorites->each->delete();
        });
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    public function favorite()
    {
        $attributes = ['user_id' => auth()->id()];

        if (!$this->favorites()->where($attributes)->exists()) {
            return $this->favorites()->create($attributes);
        }
    }

    public function unfavorite()
    {
        $attributes = ['user_id' => auth()->id()];

        $this->favorites()->where($attributes)->get()->each->delete();
    }

    public function isFavorited(): bool
    {
        return (bool)$this->favorites->where('user_id', auth()->id())->count();
    }

    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }
}
