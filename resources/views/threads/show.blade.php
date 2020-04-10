@extends('layouts.app')

@php /* @var App\Thread $thread */ @endphp

@section('head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.2.1/trix.css" integrity="sha256-yebzx8LjuetQ3l4hhQ5eNaOxVLgqaY1y8JcrXuJrAOg=" crossorigin="anonymous" />
@endsection

@section('content')
    <thread-view :thread="{{ $thread }}" inline-template>
        <div class="container">
            <div class="row">
                <div class="col-md-9">

                    @include('threads._question')
                    <replies @added="repliesCount++" @removed="repliesCount--"></replies>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <p>
                                This thread was published {{ $thread->created_at->diffForHumans() }} by
                                <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a>, and
                                currently has <span
                                    v-text="repliesCount"></span> {{ \Illuminate\Support\Str::plural('comment', $thread->replies_count) }}
                                .
                            </p>
                            <p>
                                <subscribe-button :initial-active="{{ $thread->isSubscribedTo ? 'true' : 'false' }}"
                                                  v-if="signedIn"></subscribe-button>
                                <button
                                    class="btn btn-secondary"
                                    v-if="authorize('isAdmin')"
                                    v-text="locked ? 'Unlock' : 'Lock'"
                                    @click="toggleLock"
                                ></button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </thread-view>

@endsection
