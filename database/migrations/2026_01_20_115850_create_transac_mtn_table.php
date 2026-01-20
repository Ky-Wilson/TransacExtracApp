<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transac_mtn', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // ID du gestionnaire qui a uploadé l'image
            $table->string('type')->nullable(); // transfere, depot, retrait
            $table->string('montant')->nullable(); // Montant en FCFA
            $table->string('expediteur')->nullable(); // Numéro expéditeur / destinataire
            $table->string('reference')->nullable(); // ID Tr ou ID Transaction
            $table->string('solde')->nullable(); // Solde restant
            $table->string('frais')->nullable(); // Frais (surtout retrait)
            $table->dateTime('date')->nullable(); // Date et heure de la transaction
            $table->text('raw_text')->nullable(); // Texte brut extrait pour debug
            $table->timestamps();

            // Clé étrangère
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Index pour recherche rapide + anti-doublon
            $table->index(['user_id', 'type', 'reference']);
            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transac_mtn');
    }
};
