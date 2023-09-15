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
        Schema::create('groupe_whatsapps', function (Blueprint $table) {
            $table->id();
            $table->string('nom_groupe')->nullable();
            $table->string('nombre_membre')->nullable();
            $table->string('prenom_admin')->nullable();
            $table->string('nom_admin')->nullable();
            $table->string('telephone_admin')->nullable();
            $table->string('email_admin')->nullable();
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
        Schema::dropIfExists('groupe_whatsapps');
    }
};
