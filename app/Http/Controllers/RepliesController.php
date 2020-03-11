<?php

namespace App\Http\Controllers;

use App\Inspections\Spam;
use App\Reply;
use App\Thread;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(15);
    }

    /**
     * @param $channelId
     * @param Thread $thread
     * @param Spam $spam
     * @return Reply|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException|\Exception
     * @throws \Exception
     */
    public function store($channelId, Thread $thread, Spam $spam)
    {
        $this->validate(request(), [
            'body' => 'required',
        ]);

        $spam->detect(request('body'));

        $reply = $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id(),
        ]);

        if (request()->expectsJson()) {
            return $reply->load('owner');
        }

        return redirect($thread->path())
            ->with('flash', 'Your reply has been left.');
    }

    /**
     * @param Reply $reply
     * @param Spam $spam
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException|\Exception
     * @throws \Exception
     */
    public function update(Reply $reply, Spam $spam)
    {
        $this->authorize('update', $reply);

        $this->validate(request(), ['body' => 'required']);
        $spam->detect(request('body'));

        $reply->update(request(['body']));
    }

    /**
     * @param Reply $reply
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if (request()->expectsJson()) {
            return response(['status' => 'Reply deleted']);
        }

        return back();
    }
}
