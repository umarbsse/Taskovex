<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;

class CommentController extends Controller
{
    public function store(Request $request, Task $task, ActivityLogService $activity): RedirectResponse
    {
        Gate::authorize('view', $task);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:5000'],
        ]);

        $task->comments()->create([
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
        ]);

        $activity->commentAdded($task, $request->user());

        return Redirect::back();
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        Gate::authorize('view', $comment->task);

        $comment->delete();

        return Redirect::back();
    }
}
