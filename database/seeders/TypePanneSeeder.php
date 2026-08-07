<?php

namespace Database\Seeders;

use App\Models\TypePanne;
use Illuminate\Database\Seeder;

class TypePanneSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['libelle' => 'Coupure totale', 'description' => 'Absence complète d\'électricité dans la zone.'],
            ['libelle' => 'Coupure partielle', 'description' => 'Certains équipements ou zones alimentés, d\'autres non.'],
            ['libelle' => 'Fluctuation de tension', 'description' => 'Variations anormales de la tension électrique.'],
            ['libelle' => 'Câble endommagé', 'description' => 'Câble électrique visiblement endommagé ou tombé.'],
            ['libelle' => 'Poteau électrique endommagé', 'description' => 'Poteau incliné, cassé ou dangereux.'],
            ['libelle' => 'Transformateur défectueux', 'description' => 'Panne ou bruit anormal au niveau d\'un transformateur.'],
        ];

        foreach ($types as $type) {
            TypePanne::create($type);
        }
    }
}
