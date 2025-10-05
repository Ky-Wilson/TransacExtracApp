<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransacOrange extends Model
{
    protected $table = 'transac_orange'; // Définir le nom exact de la table
    protected $fillable = [
        'user_id', 'type', 'montant', 'expediteur', 'reference', 'solde', 'frais', 'date', 'raw_text'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}