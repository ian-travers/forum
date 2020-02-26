@php
    /* @var \App\User $userProfile */
    /* @var \App\Activity $activity */
@endphp

<div class="card mt-3">
    <div class="card-header">
        <p>
            {{ $userProfile->name }} replied to thread::
            <a href="{{$activity->subject->thread->path()}}">{{ $activity->subject->thread->title }}</a>
        </p>
    </div>
    <div class="card-body">
        {{ $activity->subject->body }}
    </div>
</div>

