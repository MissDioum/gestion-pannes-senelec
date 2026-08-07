<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'message',
        'signalement_id',
        'lu',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function signalement()
    {
        return $this->belongsTo(Signalement::class);
    }
}
