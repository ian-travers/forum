@php
    /* @var \App\User $userProfile */
    /* @var \App\Activity $activity */
@endphp

@component('profiles.activities._activity')
    @slot('heading')
        <a href="{{$activity->subject->favorited->path()}}">{{ $userProfile->name  }} favorited a reply::</a>
    @endslot

    @slot('body')
        {{ $activity->subject->favorited->body }}
    @endslot
@endcomponent

