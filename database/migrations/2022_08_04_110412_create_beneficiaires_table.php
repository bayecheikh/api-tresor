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
        Schema::create('beneficiaires', function (Blueprint $table) {
            $table->id();
            $table->string('numero_cin', 255)->nullable();
            $table->string('prenom_beneficiaire', 255)->nullable();
            $table->string('nom_beneficiaire', 255)->nullable();
            $table->text('adresse_beneficiaire')->nullable();
            $table->string('telephone_beneficiaire', 255)->nullable();
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
        Schema::dropIfExists('beneficiaires');
    }
};
