@php
    /* @var \App\User $userProfile */
    /* @var \App\Activity $activity */
@endphp

@component('profiles.activities._activity')
    @slot('heading')
        {{ $userProfile->name }} replied to thread::
        <a href="{{$activity->subject->thread->path()}}">{{ $activity->subject->thread->title }}</a>
    @endslot

    @slot('body')
        {{ $activity->subject->body }}
    @endslot
@endcomponent

