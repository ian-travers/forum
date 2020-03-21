<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Filters\ThreadFilters;
use App\Thread;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redis;

class ThreadsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(Channel $channel, ThreadFilters $filters)
    {
        $threads = $this->getThreads($channel, $filters);

        if (request()->wantsJson()) {
            return $threads;
        }

        return view('threads.index', compact('threads'));
    }

    public function create()
    {
        return view('threads.create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|spamfree',
            'body' => 'required|spamfree',
            'channel_id' => 'required|exists:channels,id',
        ]);

        $thread = Thread::create([
            'user_id' => auth()->id(),
            'channel_id' => request('channel_id'),
            'title' => request('title'),
            'body' => request('body'),
        ]);

        return redirect($thread->path())
            ->with('flash', 'Your thread has been published!');
    }

    public function show($channel, Thread $thread)
    {
        if (auth()->check()) {
            auth()->user()->readThread($thread);
        }

        Redis::zincrby('trending_threads', 1, json_encode([
            'title' => $thread->title,
            'path' =>$thread->path(),
        ]));

        return view('threads.show', compact('thread'));
    }

    public function edit(Thread $thread)
    {
        //
    }

    public function update(Request $request, Thread $thread)
    {
        //
    }

    /**
     * @param $channel
     * @param Thread $thread
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|Response|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function destroy($channel, Thread $thread)
    {
        $this->authorize('update', $thread);

        $thread->delete();

        if (request()->wantsJson()) {
            return response([], Response::HTTP_NO_CONTENT);
        }

        return redirect('/threads');
    }

    /**
     * @param Channel $channel
     * @param ThreadFilters $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    protected function getThreads(Channel $channel, ThreadFilters $filters)
    {
        /** @var Thread $threads */
        $threads = Thread::latest();

        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }

        $threads = $threads->filter($filters);

        return $threads->paginate(10);
    }
}
