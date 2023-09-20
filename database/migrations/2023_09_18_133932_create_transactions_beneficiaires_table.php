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
        Schema::create('transactions_beneficiaires', function (Blueprint $table) {
            $table->unsignedInteger('transaction_id');
            $table->unsignedInteger('beneficiaire_id');
            $table->primary(['transaction_id','beneficiaire_id']);
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
        Schema::dropIfExists('transactions_beneficiaires');
    }
};
