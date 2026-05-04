<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'notifications' => fn () => $request->user()
                ? [
                    'items' => $request->user()
                        ->notifications()
                        ->latest()
                        ->limit(8)
                        ->get()
                        ->map(fn ($notification) => [
                            'id' => $notification->id,
                            'type' => $notification->type,
                            'message' => $notification->data['message'] ?? 'Taskovex update',
                            'read_at' => $notification->read_at?->toDateTimeString(),
                            'created_at' => $notification->created_at?->diffForHumans(),
                        ]),
                    'unread' => $request->user()->unreadNotifications()->count(),
                ]
                : [
                    'items' => [],
                    'unread' => 0,
                ],
        ];
    }
}
