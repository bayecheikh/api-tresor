<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('enquettes', function (Blueprint $table) {
            $table->string('reference_projet')->nullable();
            $table->string('date_enquette')->nullable();
            $table->string('titre_projet')->nullable();
            $table->string('prenom_beneficiaire')->nullable();
            $table->string('nom_beneficiaire')->nullable();
            $table->string('telephone_beneficiaire')->nullable();
            $table->string('cni_beneficiaire')->nullable();
            $table->string('adresse_beneficiaire')->nullable();
            $table->string('region')->nullable();
            $table->string('departement')->nullable();
            $table->string('commune')->nullable();
            $table->string('section')->nullable();
            $table->string('activites')->nullable();
            $table->string('contraintes')->nullable();
            $table->string('geolocalisation')->nullable();
            $table->string('libelle_secteur')->nullable();
            $table->string('id_secteur')->nullable();
            $table->longText('questionnaire')->nullable();
            $table->longText('evaluation_expert')->nullable();
            $table->string('state')->nullable();
            $table->string('status')->nullable();    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('enquettes', function (Blueprint $table) {
            //
        });
    }
};
