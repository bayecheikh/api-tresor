<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalyseRisque extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected $fillable = [
        'reference_projet','date_enquette','titre_projet','prenom_beneficiaire',
        'nom_beneficiaire','telephone_beneficiaire','cni_beneficiaire','adresse_beneficiaire','region',
        'departement','commune','libelle_secteur','id_secteur','questionnaire','evaluation_expert','state','status'
    ];
}
