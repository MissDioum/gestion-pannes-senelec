<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypePanne extends Model
{
    protected $table = 'types_pannes';

    protected $fillable = ['libelle', 'description'];

    public function signalements()
    {
        return $this->hasMany(Signalement::class);
    }
}
