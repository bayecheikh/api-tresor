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
        Schema::create('financements_activites_activites', function (Blueprint $table) {
            $table->unsignedInteger('financement_activite_id');
            $table->unsignedInteger('activite_id');
            $table->primary(['financement_activite_id','activite_id'],'finances_activites');
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
        Schema::dropIfExists('financements_activites_activites');
    }
};
