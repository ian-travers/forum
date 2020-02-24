@php /* @var App\Reply $reply */ @endphp

<div class="card mt-3">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <div>
                <a href="#">
                    {{ $reply->owner->name }}
                </a>
                said {{ $reply->created_at->diffForHumans() }}...
            </div>
            <div>
                <form action="/replies/{{ $reply->id }}/favorites" method="post">

                    @csrf
                    <button type="submit" class="btn btn-sm btn-secondary" {{ $reply->isFavorited() ? 'disabled' : '' }}>
                        <span class="badge badge-light badge-pill">{{ $reply->favorites()->count() }}</span>
                        {{ \Illuminate\Support\Str::plural('Favorite', $reply->favorites()->count()) }}
                    </button>
                </form>
            </div>
        </div>

    </div>
    <div class="card-body">
        {{ $reply->body }}
    </div>
</div>

