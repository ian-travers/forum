@php /* @var \Illuminate\Contracts\Pagination\LengthAwarePaginator $threads */ @endphp
@php /* @var \App\Thread $thread */ @endphp

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9">

                @include('threads._list')

                @if($threads->isNotEmpty())
                    <div>{{ $threads->links() }}</div>

                @endif
            </div>
            <div class="col-md-3">
                <div class="card mb-3">
                    <div class="card-header">
                        Search
                    </div>
                    <div class="card-body">
                        <form method="get" action="{{ route('threads.search') }}">
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ request('q') }}" name="q"
                                       placeholder="Search for something...">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                @if(count($trending))
                    <div class="card">
                        <div class="card-header">
                            Trending Threads
                        </div>
                        <div class="card-body">
                            <ul class="list-group">

                                @foreach($trending as $thread)
                                    <li class="list-group-item">
                                        <a href="{{ url($thread->path) }}">{{ $thread->title }}</a>
                                    </li>

                                @endforeach
                            </ul>
                        </div>
                    </div>

                @endif
            </div>
        </div>
    </div>
@endsection

