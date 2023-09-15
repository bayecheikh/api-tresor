<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancementSource extends Model
{
    use HasFactory;
      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'montant','libelle_source','libelle_secteur','annee','trimestre','type'
    ];

    public function sources() {
        return $this->belongsToMany(Bailleur::class,'financements_sources_bailleurs');          
    }

    public function secteurs() {
        return $this->belongsToMany(Secteur::class,'financements_sources_secteurs');          
    }

}
