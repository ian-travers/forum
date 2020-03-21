@php /* @var App\Thread $thread */ @endphp

@forelse($threads as $thread)
    <div class="card mb-3">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>
                    <a href="{{ $thread->path() }}">

                        @if(auth()->check() && $thread->hasUpdatesFor(auth()->user()))
                            <strong>{{ $thread->title }}</strong>

                        @else
                            {{ $thread->title }}

                        @endif
                    </a>
                </h4>
                <a href="{{ $thread->path() }}">
                    {{ $thread->replies_count }}
                    {{ \Illuminate\Support\Str::plural('comment', $thread->replies_count) }}
                </a>
            </div>
            <div>
                <small class="fas fa-user"></small>
                <a href="{{ route('profile', $thread->creator) }}"> {{ $thread->creator->name }}</a>
                <small class="fas fa-calendar-day"></small>
                <span> {{ $thread->created_at->format('H:i l F d, Y.') }}</span>
            </div>
        </div>
        <div class="card-body">
            <div>{{ $thread->body }}</div>
        </div>
        <div class="card-footer">
            <span class="fas fa-eye"></span>
            {{ $thread->visits() }}
        </div>
    </div>

@empty
    <p>There is not relevant results at this time</p>

@endforelse
