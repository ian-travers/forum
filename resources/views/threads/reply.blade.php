@php /* @var App\Reply $reply */ @endphp

<reply :attributes="{{ $reply }}" inline-template v-cloak>
    <div id="reply-{{ $reply->id }}" class="card mt-3">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div>
                    <a href=" {{ route('profile', $reply->owner) }}">
                        {{ $reply->owner->name }}
                    </a>
                    said {{ $reply->created_at->diffForHumans() }}...
                </div>
                <div>
                    <form action="/replies/{{ $reply->id }}/favorites" method="post">

                        @csrf
                        <button type="submit"
                                class="btn btn-sm btn-secondary" {{ $reply->isFavorited() ? 'disabled' : '' }}>
                            <span class="badge badge-light badge-pill">{{ $reply->favorites_count }}</span>
                            {{ \Illuminate\Support\Str::plural('Favorite', $reply->favorites_count) }}
                        </button>
                    </form>
                </div>
            </div>

        </div>
        <div class="card-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea class="form-control" v-model="body" name=""></textarea>
                </div>
                <button class="btn btn-sm btn-primary" @click="update">Update</button>
                <button class="btn btn-sm btn-secondary" @click="editing = false">Cancel</button>
            </div>
            <div v-else v-text="body"></div>
        </div>

        @can('update', $reply)
            <div class="card-footer">
                <button class="btn btn-sm btn-primary" @click="editing = true">Edit</button>
                <form action="/replies/{{ $reply->id }}" method="post" class="d-inline">

                    @csrf
                    @method('delete')
                    <button
                        type="submit"
                        class="btn btn-sm btn-danger"
                        onclick="return confirm('Confirm delete?')"
                    >
                        Delete
                    </button>
                </form>
            </div>

        @endcan
    </div>
</reply>
