<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annuaire extends Model
{
    use HasFactory;
    /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
        'prenom',
        'nom',
        'telephone',
        'type_militant',
        'region',
        'departement',
        'commune',
        'user_id',
        'status'
    ];
}
