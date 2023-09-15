<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SousSecteur extends Model
{
    use HasFactory;
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'libelle','status'
    ];

    //Composante
    public function secteur() {
        return $this->belongsToMany(Secteur::class,'secteurs_sous_secteurs');          
    }

    public function users() {
        return $this->belongsToMany(User::class,'users_sous_secteurs');          
    }
}