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
        Schema::create('regions_beneficiaires', function (Blueprint $table) {
            $table->unsignedInteger('region_id');
            $table->unsignedInteger('beneficiaire_id');
            $table->primary(['region_id','beneficiaire_id']);
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
        Schema::dropIfExists('regions_beneficiaires');
    }
};
