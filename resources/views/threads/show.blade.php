@extends('layouts.app')

@php /* @var App\Thread $thread */ @endphp

@section('content')
    <thread-view :initial-replies-count="{{ $thread->replies_count }}" inline-template>
        <div class="container">
            <div class="row">
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-baseline justify-content-between">
                                <div>
                                    <a href="{{ route('profile', $thread->creator) }}"
                                       class="">{{ $thread->creator->name }} </a> posted:
                                    {{ $thread->title }}
                                </div>

                                @can('update', $thread)
                                    <form action="{{ $thread->path() }}" method="post">

                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-link">Delete Thread</button>
                                    </form>

                                @endcan
                            </div>

                        </div>
                        <div class="card-body">
                            {{ $thread->body }}
                        </div>
                    </div>

                    <replies @added="repliesCount++" @removed="repliesCount--"></replies>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <p>
                                This thread was published {{ $thread->created_at->diffForHumans() }} by
                                <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a>, and currently has <span v-text="repliesCount"></span> {{ \Illuminate\Support\Str::plural('comment', $thread->replies_count) }}.
                            </p>
                            <p>
                                <subscribe-button :initial-active="{{ $thread->isSubscribedTo ? 'true' : 'false' }}"></subscribe-button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </thread-view>

@endsection
