<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Signalement extends Model
{
    protected $fillable = [
        'user_id',
        'type_panne_id',
        'zone_id',
        'description',
        'latitude',
        'longitude',
        'adresse',
        'photo',
        'statut',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function typePanne()
    {
        return $this->belongsTo(TypePanne::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}
