<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function store(Request $request, Task $task): RedirectResponse
    {
        Gate::authorize('view', $task);

        $validated = $request->validate([
            'file' => ['required', 'file', 'max:10240'],
        ]);

        $file = $validated['file'];
        $path = $file->store("attachments/tasks/{$task->id}", 'public');

        $task->attachments()->create([
            'user_id' => $request->user()->id,
            'disk' => 'public',
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
        ]);

        return Redirect::back();
    }

    public function destroy(Attachment $attachment): RedirectResponse
    {
        Gate::authorize('view', $attachment->task);

        Storage::disk($attachment->disk)->delete($attachment->path);
        $attachment->delete();

        return Redirect::back();
    }
}
