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
        Schema::create('parrainages', function (Blueprint $table) {
            $table->id();
            $table->string('numero_cedeao')->nullable();
            $table->string('prenom')->nullable();
            $table->string('nom')->nullable();
            $table->string('date_naissance')->nullable();
            $table->string('lieu_naissance')->nullable();
            $table->string('taille')->nullable();
            $table->string('sexe')->nullable();
            $table->string('numero_electeur')->nullable();
            $table->string('centre_vote')->nullable();
            $table->string('bureau_vote')->nullable();
            $table->string('numero_cin')->nullable();
            $table->string('telephone')->nullable();
            $table->string('prenom_responsable')->nullable();
            $table->string('nom_responsable')->nullable();
            $table->string('telephone_responsable')->nullable();
            $table->string('region')->nullable();
            $table->string('departement')->nullable();
            $table->string('commune')->nullable();
            $table->string('user_id')->nullable();
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
        Schema::dropIfExists('parrainages');
    }
};
