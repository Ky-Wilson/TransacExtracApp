<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransacOrangeTable extends Migration
{
    public function up()
    {
        Schema::create('transac_orange', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // ID du gestionnaire qui a uploadé l'image
            $table->string('type'); // Type de transaction (transfere, depot, retrait)
            $table->string('montant'); // Montant en FCFA
            $table->string('expediteur')->nullable(); // Numéro expéditeur
            $table->string('reference')->nullable(); // Référence de la transaction
            $table->string('solde')->nullable(); // Solde restant
            $table->string('frais')->nullable(); // Frais appliqués
            $table->dateTime('date')->nullable(); // Date et heure de la transaction
            $table->text('raw_text')->nullable(); // Texte brut extrait pour référence
            $table->timestamps();
            // Clé étrangère vers la table users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Index pour optimiser la vérification des doublons
            $table->index(['user_id', 'type', 'reference']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('transac_orange');
    }
}
