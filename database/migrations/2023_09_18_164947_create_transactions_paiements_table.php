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
        Schema::create('transactions_paiements', function (Blueprint $table) {
            $table->unsignedInteger('transaction_id');
            $table->unsignedInteger('paiement_id');
            $table->primary(['transaction_id','paiement_id'],'transactions_paiements');
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
        Schema::dropIfExists('transactions_paiements');
    }
};
