@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">

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
                        </div>
                        <div class="card-body">
                            <div>{{ $thread->body }}</div>
                        </div>
                    </div>

                @empty
                    <p>There is not relevant results at this time</p>

                @endforelse
            </div>
        </div>
    </div>
@endsection

