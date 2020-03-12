<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Notifications\YouAreMentioned;
use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Http\Response;

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
     * @param CreatePostRequest $request
     * @return Reply|\Illuminate\Http\Response
     */
    public function store($channelId, Thread $thread, CreatePostRequest $request)
    {
        /** @var Reply $reply */
        $reply = $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id(),
        ])->load('owner');

        preg_match_all('/\@([^\W+]+)/', $reply->body, $matches);

        $names = $matches[1];

        foreach ($names as $name) {
            $user = User::whereName($name)->first();
            if ($user) {
                $user->notify(new YouAreMentioned($reply));
            }
        }

        return $reply;
    }

    /**
     * @param Reply $reply
     * @return void|\Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        try {
            $this->validate(request(), [
                'body' => 'required|spamfree',
            ]);

            $reply->update(request(['body']));
        } catch (\Exception $e) {
            return response('Sorry, your reply could not be updated at this time.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param Reply $reply
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
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
