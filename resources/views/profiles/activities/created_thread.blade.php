@php
    /* @var \App\User $userProfile */
    /* @var \App\Activity $activity */
@endphp

@component('profiles.activities._activity')
    @slot('heading')
        {{ $userProfile->name }} created thread::
        <a href="{{ $activity->subject->path() }}">{{ $activity->subject->title }}</a>
    @endslot

    @slot('body')
        {{ $activity->subject->body }}
    @endslot
@endcomponent
