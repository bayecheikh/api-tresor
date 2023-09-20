<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operateur extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $fillable = [
        'libelle',
        'slug',
        'logo',
        'status'
    ];

    public function transactions() {
        return $this->belongsToMany(Transaction::class,'transactions_operateurs');          
    }
}
