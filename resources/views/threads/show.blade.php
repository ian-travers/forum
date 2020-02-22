@extends('layouts.app')

@php /* @var App\Thread $thread */ @endphp

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <a href="#" class="">{{ $thread->creator->name }} </a> posted:
                        {{ $thread->title }}
                    </div>
                    <div class="card-body">
                        {{ $thread->body }}
                    </div>
                </div>

                @foreach($thread->replies as $reply)
                    @include('threads.reply')
                @endforeach

                @if(auth()->check())
                    <form action="{{ $thread->path() . '/replies' }}" method="post">

                        @csrf
                        <div class="form-group mt-3">
                            <textarea name="body" id="body" class="form-control" rows="5"
                                      placeholder="Have something to say?"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Post</button>
                    </form>

                @else
                    <p class="text-center mt-3">Please <a href="{{ route('login') }}">sign in</a> to participate to this
                        discussion.</p>
                @endif
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                       <p>
                           This thread was published {{ $thread->created_at->diffForHumans() }} by
                           <a href="#">{{ $thread->creator->name }}</a>, and currently has {{ $thread->replies_count }} {{ \Illuminate\Support\Str::plural('comment', $thread->replies_count) }}.
                       </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
