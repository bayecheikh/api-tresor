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
        Schema::create('analyse_genres', function (Blueprint $table) {
            $table->id();
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
            $table->longText('questionnaire')->nullable();
            $table->longText('evaluation_expert')->nullable();
            $table->string('state')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('analyse_genres');
    }
};
