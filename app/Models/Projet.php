<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projet extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $fillable = [
        'reference_projet',
        'titre_projet'
    ];
    public function beneficiaire() {
        return $this->belongsToMany(Beneficiaire::class,'beneficiaires_projets');          
    }
    public function commune() {
        return $this->belongsToMany(Commune::class,'communes_projets');          
    }
    public function departement() {
        return $this->belongsToMany(Departement::class,'departements_projets');          
    }
    public function region() {
        return $this->belongsToMany(Region::class,'regions_projets');          
    }
    public function enquettes() {
        return $this->belongsToMany(Enquette::class,'projets_enquettes');          
    }
}
