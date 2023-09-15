<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{
    use HasFactory;

    /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'nom_commune', 'slug','latitude','longitude','svg','status'
  ];
  public function departement() {
    return $this->belongsToMany(Departement::class,'departements_communes');          
  }
  public function beneficiaires() {
    return $this->belongsToMany(Beneficiaire::class,'communes_beneficiaires');          
  }
  public function projets() {
    return $this->belongsToMany(Projet::class,'communes_projets');          
  }
  public function enquettes() {
    return $this->belongsToMany(Enquette::class,'communes_enquettes');          
  }
}
