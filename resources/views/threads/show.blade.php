@extends('layouts.app')

@php /* @var App\Thread $thread */ @endphp

@section('content')
    <div class="container">
        <div class="row justify-content-center">
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
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">

                @foreach($thread->replies as $reply)
                    @include('threads.reply')
                @endforeach
            </div>
        </div>

        @if(auth()->check())
            <div class="row justify-content-center">
                <div class="col-md-8 mt-3">
                    <form action="{{ $thread->path() . '/replies' }}" method="post">

                        @csrf
                        <div class="form-group">
                            <textarea name="body" id="body" class="form-control" rows="5"
                                      placeholder="Have something to say?"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Post</button>
                    </form>
                </div>
            </div>

        @else
            <p class="text-center mt-3">Please <a href="{{ route('login') }}">sign in</a> to participate to this discussion.</p>
        @endif
    </div>
@endsection
