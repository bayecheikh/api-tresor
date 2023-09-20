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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference_transaction', 255)->nullable();
            $table->string('id_beneficiaire',255)->nullable();
            $table->string('prenom_beneficiaire',255)->nullable();
            $table->string('nom_beneficiaire',255)->nullable();
            $table->string('cni_beneficiaire',255)->nullable();
            $table->string('telephone_beneficiaire',255)->nullable();
            $table->string('id_paiement',255)->nullable();
            $table->string('libelle_paiement',255)->nullable();
            $table->string('slug_paiement',255)->nullable();
            $table->string('id_operateur',255)->nullable();
            $table->string('libelle_operateur',255)->nullable();
            $table->string('slug_operateur',255)->nullable();
            $table->string('montant',255)->nullable();
            $table->string('commentaire',255)->nullable();
            $table->string('motif_rejet',255)->nullable();
            $table->string('user_id',255)->nullable();
            $table->string('status',255)->nullable();
            $table->string('state',255)->nullable();
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
        Schema::dropIfExists('transactions');
    }
};
