<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupeWhatsapp extends Model
{
    use HasFactory;

     /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'nom_groupe',
    'nombre_membre',
    'prenom_admin',
    'nom_admin',
    'telephone_admin',
    'email_admin',
    'user_id',
    'status'
];
}
