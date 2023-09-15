<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bailleur extends Model
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

    public function financement_sources() {
        return $this->belongsToMany(FinancementSource::class,'financements_sources_bailleurs');          
    }
}
