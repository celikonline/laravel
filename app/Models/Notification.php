<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'link',
        'is_read',
        'notifiable_type',
        'notifiable_id'
    ];

    protected $casts = [
        'is_read' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notifiable()
    {
        return $this->morphTo();
    }

    public static function send($userId, $title, $message, $type = 'info', $link = null, $notifiable = null)
    {
        $data = [
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'link' => $link
        ];

        if ($notifiable) {
            $data['notifiable_type'] = get_class($notifiable);
            $data['notifiable_id'] = $notifiable->id;
        }

        return static::create($data);
    }

    public function markAsRead()
    {
        $this->is_read = true;
        $this->save();
    }
}
