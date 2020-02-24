@php /* @var \App\User $userProfile */  @endphp

@extends('layouts.app')

@section('content')
    <div class="container">
        <p>
            <span class="h3">{{ $userProfile->name }}</span>
            <small>since {{ $userProfile->created_at->format('d F, Y.') }}</small>
        </p>

        @foreach($threads as $thread)
            <div class="card mb-2">
                <div class="card-header">
                    <div class="d-flex align-items-baseline justify-content-between">
                        <h4>
                            <a href="{{ $thread->path() }}">{{ $thread->title }}</a>
                        </h4>
                        <p>{{ $thread->created_at->diffForHumans() }}</p>
                    </div>

                </div>
                <div class="card-body">
                    {{ $thread->body }}
                </div>
            </div>

        @endforeach
        {{ $threads->links() }}
    </div>

@endsection
