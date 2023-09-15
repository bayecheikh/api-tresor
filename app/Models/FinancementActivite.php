<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancementActivite extends Model
{
    use HasFactory;
      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'montant','libelle_activite','annee','trimestre','type'
    ];

    public function activites() {
        return $this->belongsToMany(Activite::class,'financements_activites_activites');          
    }

}
