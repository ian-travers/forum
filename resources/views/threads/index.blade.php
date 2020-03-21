@php /* @var \Illuminate\Contracts\Pagination\LengthAwarePaginator $threads */ @endphp

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
            </div>
        </div>
    </div>
@endsection

