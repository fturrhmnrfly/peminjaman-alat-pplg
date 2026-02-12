<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'role',
        'title',
        'message',
        'is_read',
    ];

    public static function pushForRole(string $role, string $title, string $message): void
    {
        User::where('role', $role)->pluck('id')->each(function ($userId) use ($role, $title, $message) {
            self::create([
                'user_id' => $userId,
                'role' => $role,
                'title' => $title,
                'message' => $message,
                'is_read' => false,
            ]);
        });
    }

    public static function pushForUser(int $userId, string $title, string $message): self
    {
        return self::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'is_read' => false,
        ]);
    }
}
