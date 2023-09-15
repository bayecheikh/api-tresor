<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annee extends Model
{
    use HasFactory;
      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'libelle','status'
    ];

    public function investissements() {
        return $this->belongsToMany(Investissement::class,'annees_investissements');          
    }

    public function type_annees() {
        return $this->belongsToMany(TypeAnnee::class,'annees_type_annees');          
    }

}
