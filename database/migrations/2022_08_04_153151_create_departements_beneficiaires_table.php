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
        Schema::create('departements_beneficiaires', function (Blueprint $table) {
            $table->unsignedInteger('departement_id');
            $table->unsignedInteger('beneficiaire_id');
            $table->primary(['departement_id','beneficiaire_id'],'departement_benef');
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
        Schema::dropIfExists('departements_beneficiaires');
    }
};
