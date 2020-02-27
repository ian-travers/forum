@php
    /* @var \App\User $userProfile */
    /* @var \App\Activity $activity */
@endphp

@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-10">
                <p>
                    <span class="h3">{{ $userProfile->name }}</span>
                    <small>since {{ $userProfile->created_at->format('d F, Y.') }}</small>
                </p>

                @foreach($activities as $date => $activity)

                    <h4 class="text-right">{{ $date }}</h4>
                    @foreach($activity as $record)
                        @if(view()->exists("profiles.activities.{$record->type}"))
                            @include("profiles.activities.{$record->type}", ['activity' => $record])
                        @endif
                    @endforeach
                @endforeach
            </div>
        </div>
    </div>

@endsection
