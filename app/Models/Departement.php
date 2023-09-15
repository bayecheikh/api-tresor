<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    use HasFactory;

    /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'nom_departement', 'slug','latitude','longitude','svg','status'
  ];

  public function region() {
    return $this->belongsToMany(Region::class,'regions_departements');          
  }
  public function communes() {
    return $this->belongsToMany(Commune::class,'departements_communes');          
  }
  public function structures() {
    return $this->belongsToMany(Structure::class,'structures_departemens');          
  }
  public function beneficiaires() {
    return $this->belongsToMany(Beneficiaire::class,'departements_beneficiaires');          
  }
  public function projets() {
    return $this->belongsToMany(Projet::class,'departements_projets');          
  }
  public function enquettes() {
    return $this->belongsToMany(Enquette::class,'departements_enquettes');          
  }
}
