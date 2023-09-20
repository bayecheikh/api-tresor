<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $fillable = [
        'reference_transaction',
        'id_beneficiaire',
        'prenom_beneficiaire',
        'nom_beneficiaire',
        'cni_beneficiaire',
        'telephone_beneficiaire',
        'id_paiement',
        'libelle_paiement',
        'slug_paiement',
        'id_operateur',
        'libelle_operateur',
        'slug_operateur',
        'montant',
        'commentaire',
        'motif_rejet',
        'user_id',
        'status',
        'state',
    ];
    public function beneficiaire() {
        return $this->belongsToMany(Beneficiaire::class,'transactions_beneficiaires');          
    }
    public function operateur() {
        return $this->belongsToMany(Operateur::class,'transactions_operateurs');          
    }
    public function paiement() {
        return $this->belongsToMany(Operateur::class,'transactions_paiements');          
    }
}
