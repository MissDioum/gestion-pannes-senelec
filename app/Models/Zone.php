<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    protected $fillable = ['nom', 'commune'];

    public function signalements()
    {
        return $this->hasMany(Signalement::class);
    }
}
