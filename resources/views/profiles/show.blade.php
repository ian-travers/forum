@php /* @var \App\User $userProfile */  @endphp

@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-10">
                <p>
                    <span class="h3">{{ $userProfile->name }}</span>
                    <small>since {{ $userProfile->created_at->format('d F, Y.') }}</small>
                </p>

                @foreach($threads as $thread)
                    <div class="card mb-3">
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
        </div>
    </div>

@endsection
