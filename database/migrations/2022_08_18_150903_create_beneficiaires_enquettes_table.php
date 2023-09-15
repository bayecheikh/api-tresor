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
        Schema::create('beneficiaires_enquettes', function (Blueprint $table) {
            $table->unsignedInteger('beneficiaire_id');
            $table->unsignedInteger('enquette_id');
            $table->primary(['beneficiaire_id','enquette_id']);
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
        Schema::dropIfExists('beneficiaires_enquettes');
    }
};
