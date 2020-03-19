@php
    /* @var \App\User $userProfile */
    /* @var \App\Activity $activity */
@endphp

@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-10">
               <avatar-form :user="{{ $userProfile }}"></avatar-form>

                @forelse($activities as $date => $activity)
                    <h4 class="text-right">{{ $date }}</h4>

                    @foreach($activity as $record)
                        @if(view()->exists("profiles.activities.{$record->type}"))
                            @include("profiles.activities.{$record->type}", ['activity' => $record])
                        @endif
                    @endforeach
                @empty
                    <p class="lead">There is no activity for this user yet.</p>

                @endforelse
            </div>
        </div>
    </div>

@endsection
