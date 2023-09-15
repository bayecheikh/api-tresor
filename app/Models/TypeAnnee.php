<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeAnnee extends Model
{
    use HasFactory;
     /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'libelle'
  ];

  public function annee() {
    return $this->belongsToMany(Region::class,'annees_type_annees');          
  }

}
