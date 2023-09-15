<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parrainage extends Model
{
    use HasFactory;
    /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
    protected $fillable = [
        'numero_cedeao',
        'prenom',
        'nom',
        'date_naissance',
        'lieu_naissance',
        'taille',
        'sexe',
        'numero_electeur',
        'centre_vote',
        'bureau_vote',
        'numero_cin',
        'telephone',
        'prenom_responsable',
        'nom_responsable',
        'telephone_responsable',
        'region',
        'departement',
        'commune',
        'user_id',
        'status'
    ];
    
}
