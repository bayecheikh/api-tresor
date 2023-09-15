<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enquette extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reference_enquette','reference_projet','date_enquette','titre_projet','prenom_beneficiaire',
        'nom_beneficiaire','telephone_beneficiaire','cni_beneficiaire','adresse_beneficiaire','region',
        'departement','commune','section','activites','contraintes','geolocalisation','libelle_secteur','id_secteur','questionnaire','evaluation_expert','state','status'
    ];
    public function projet() {
        return $this->belongsToMany(Projet::class,'projets_enquettes');
    }
    public function beneficiaire() {
        return $this->belongsToMany(Beneficiaire::class,'beneficiaires_enquettes');
    }
    public function commune() {
        return $this->belongsToMany(Commune::class,'communes_enquettes');
    }
    public function departement() {
        return $this->belongsToMany(Departement::class,'departements_enquettes');
    }
    public function region() {
        return $this->belongsToMany(Region::class,'regions_enquettes');
    }
}
