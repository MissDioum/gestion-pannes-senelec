<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Intervention extends Model
{
    protected $fillable = [
        'signalement_id',
        'technicien_id',
        'superviseur_id',
        'date_affectation',
    ];

    public function signalement()
    {
        return $this->belongsTo(Signalement::class);
    }

    public function technicien()
    {
        return $this->belongsTo(User::class, 'technicien_id');
    }

    public function superviseur()
    {
        return $this->belongsTo(User::class, 'superviseur_id');
    }
}
