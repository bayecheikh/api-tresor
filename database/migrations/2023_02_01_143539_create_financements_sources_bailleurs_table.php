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
        Schema::create('financements_sources_bailleurs', function (Blueprint $table) {
            $table->unsignedInteger('financement_source_id');
            $table->unsignedInteger('bailleur_id');
            $table->primary(['financement_source_id','bailleur_id'],'finances_bailleurs');
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
        Schema::dropIfExists('financements_sources_bailleurs');
    }
};
