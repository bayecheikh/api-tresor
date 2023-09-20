<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiaire extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $fillable = [
        'numero_cin',
        'nom_beneficiaire',
        'prenom_beneficiaire',
        'adresse_beneficiaire',
        'telephone_beneficiaire'
    ];
    public function commune() {
        return $this->belongsToMany(Commune::class,'communes_beneficiaires');          
    }
    public function departement() {
        return $this->belongsToMany(Departement::class,'departements_beneficiaires');          
    }
    public function region() {
        return $this->belongsToMany(Region::class,'regions_beneficiaires');          
    }
    public function projets() {
        return $this->belongsToMany(Projet::class,'beneficiaires_projets');          
    }

    public function transactions() {
        return $this->belongsToMany(Transaction::class,'transactions_beneficiaires','transaction_id','beneficiaire_id');          
    }
}
