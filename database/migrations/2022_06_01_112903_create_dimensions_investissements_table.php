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
        Schema::create('dimensions_investissements', function (Blueprint $table) {
            $table->unsignedInteger('dimension_id');
            $table->unsignedInteger('investissement_id');
            $table->primary(['dimension_id','investissement_id'],'dim_investissement_id');
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
        Schema::dropIfExists('dimensions_investissements');
    }
};
