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
        Schema::create('departements_enquettes', function (Blueprint $table) {
            $table->unsignedInteger('departement_id');
            $table->unsignedInteger('enquette_id');
            $table->primary(['departement_id','enquette_id']);;
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
        Schema::dropIfExists('departements_enquettes');
    }
};
