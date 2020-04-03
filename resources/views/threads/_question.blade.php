@php /* @var App\Thread $thread */ @endphp

<div class="card" v-if="editing">
    <div class="card-header">
        <input class="form-control" id="title" name="title" type="text" value="{{ $thread->title }}">
    </div>
    <div class="card-body">
        <div class="form-group">
            <textarea class="form-control" rows="8">{{ $thread->body }}</textarea>
        </div>

    </div>
    <div class="card-footer">
        <button class="btn btn-primary btn-sm">Update</button>
        <button class="btn btn-secondary btn-sm" @click="editing = false">Cancel</button>
    </div>
</div>

<div class="card" v-else>
    <div class="card-header">
        <div>
            <img
                src="{{ $thread->creator->avatar_path }}"
                alt="author_avatar"
                width="25"
                height="25"
                class="rounded-circle mr-1"
            >
            <a href="{{ route('profile', $thread->creator) }}">
                {{ $thread->creator->name }}
            </a> posted:
            {{ $thread->title }}
        </div>
    </div>
    <div class="card-body">
        {{ $thread->body }}
    </div>
    <div class="card-footer">
        <div class="d-flex align-items-baseline justify-content-between">
            <button class="btn btn-primary btn-sm" @click="editing = true">Edit</button>

            @can('update', $thread)
                <form action="{{ $thread->path() }}" method="post">

                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-link">Delete Thread</button>
                </form>

            @endcan
        </div>
    </div>
</div>
