<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'nom_region', 'slug','latitude','longitude','svg','status'
  ];

  public function departements() {
    return $this->belongsToMany(Departement::class,'regions_departements');          
  }

  public function beneficiaires() {
    return $this->belongsToMany(Beneficiaire::class,'regions_beneficiaires');          
  }

  public function projets() {
    return $this->belongsToMany(Projet::class,'regions_projets');          
  }
  public function enquettes() {
    return $this->belongsToMany(Enquette::class,'regions_enquettes');          
}

}
