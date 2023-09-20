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
        Schema::create('transactions_operateurs', function (Blueprint $table) {
            $table->unsignedInteger('transaction_id');
            $table->unsignedInteger('operateur_id');
            $table->primary(['transaction_id','operateur_id'],'transactions_operateurs');
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
        Schema::dropIfExists('transactions_operateurs');
    }
};
