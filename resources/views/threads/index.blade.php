@php /* @var \Illuminate\Contracts\Pagination\LengthAwarePaginator $threads */ @endphp

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">

                @include('threads._list')

                @if($threads->isNotEmpty())
                    <div>{{ $threads->links() }}</div>

                @endif
            </div>
        </div>
    </div>
@endsection

