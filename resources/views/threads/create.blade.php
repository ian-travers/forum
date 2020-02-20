@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Create a New Thread
                    </div>
                    <div class="card-body">
                        <form action="/threads" method="post">

                            @csrf
                            <div class="form-group">
                                <label for="channel_id">Choose a Channel</label>
                                <select name="channel_id" id="channel_id" class="form-control" required>
                                    <option value="">Choose One ...</option>
                                    @foreach($channels as $channel)
                                        @php /** @var \App\Channel $channel */ @endphp
                                        <option
                                            value="{{ $channel->id }}" {{ old('channel_id') == $channel->id ? 'selected' : '' }}>{{ $channel->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input name="title" type="text" class="form-control" id="title"
                                       value="{{ old('title') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="body">Body</label>
                                <textarea name="body" id="body" rows="7"
                                          class="form-control" required>{{ old('body') }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Publish</button>

                            @if(count($errors))
                                <ul class="alert alert-danger mt-3">

                                    @foreach($errors->all() as $error)
                                        <li class="ml-2">{{ $error }}</li>

                                    @endforeach
                                </ul>

                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
