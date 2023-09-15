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
        Schema::create('communes_enquettes', function (Blueprint $table) {
            $table->unsignedInteger('commune_id');
            $table->unsignedInteger('enquette_id');
            $table->primary(['commune_id','enquette_id']);
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
        Schema::dropIfExists('communes_enquettes');
    }
};
