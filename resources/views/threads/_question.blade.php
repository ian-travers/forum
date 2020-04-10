@php /* @var App\Thread $thread */ @endphp

{{-- Editing the question --}}
<div class="card" v-if="editing">
    <div class="card-header">
        <input class="form-control" id="title" name="title" type="text" v-model="form.title">
    </div>
    <div class="card-body">
        <div class="form-group">
            <wysiwyg v-model="form.body"></wysiwyg>
        </div>

    </div>
    <div class="card-footer">
        <button class="btn btn-primary btn-sm" @click="update">Update</button>
        <button class="btn btn-secondary btn-sm" @click="resetForm">Cancel</button>
    </div>
</div>

{{-- Viewing the question --}}
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
            <span v-text="title"></span>
        </div>
    </div>
    <div class="card-body" v-html="body"></div>
    <div class="card-footer" v-if="authorize('owns', thread)">
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
