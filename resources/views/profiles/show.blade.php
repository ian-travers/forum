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

                @can('update', $userProfile)
                    <form method="post" action="{{ route('avatar', $userProfile) }}" enctype="multipart/form-data">

                        @csrf
                        <div class="d-flex justify-content-between align-items-baseline">
                            <div class="form-group">
                                <input type="file" name="avatar">
                            </div>
                            <button type="submit" class="btn btn-primary">Add Avatar</button>
                        </div>

                    </form>
                @endcan

                <img src="{{ $userProfile->avatar() }}" width="50" height="50" alt="avatar">
                <hr>

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
