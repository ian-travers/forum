@php
    /* @var \App\User $userProfile */
    /* @var \App\Activity $activity */
@endphp

<div class="card mb-3">
    <div class="card-header">
        <p>
            {{ $userProfile->name }} created thread::
            <a href="{{ $activity->subject->path() }}">{{ $activity->subject->title }}</a>
        </p>
    </div>
    <div class="card-body">
        {{ $activity->subject->body }}
    </div>
</div>
