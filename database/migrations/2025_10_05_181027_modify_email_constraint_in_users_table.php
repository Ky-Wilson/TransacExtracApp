<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyEmailConstraintInUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Supprimer la contrainte unique existante sur email
            $table->dropUnique('users_email_unique');

            // Ajouter une contrainte unique composite sur email et company_id
            $table->unique(['email', 'company_id']);
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Restaurer la contrainte unique sur email
            $table->dropUnique(['email', 'company_id']);
            $table->unique('email');
        });
    }
}