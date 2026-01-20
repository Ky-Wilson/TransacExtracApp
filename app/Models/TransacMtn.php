<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class TransacMtn extends Model
{
    protected $table = 'transac_mtn';

    protected $fillable = [
        'user_id',
        'type',
        'montant',
        'expediteur',
        'reference',
        'solde',
        'frais',
        'date',
        'raw_text',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
