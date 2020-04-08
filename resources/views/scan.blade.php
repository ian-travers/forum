@extends('layouts.app')

@section('content')
    <search
        algolia_app_id="{{ config('scout.algolia.id') }}"
        algolia_key="{{ config('scout.algolia.key') }}"
    >
    </search>
@endsection

