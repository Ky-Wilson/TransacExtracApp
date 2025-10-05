<?php
namespace App\Models;

use App\Models\Companie;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'status', 'company_id', 'avatar',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'status' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Companie::class);
    }

    public function admin()
    {
        // Relation pour lier un gestionnaire à son admin via company_id
        return $this->belongsTo(User::class, 'company_id', 'company_id')
            ->where('role', 'admin')
            ->latest(); // Prend le dernier admin ajouté si plusieurs existent
    }

    public function managers()
    {
        // Relation pour lier un admin à ses gestionnaires via company_id
        return $this->hasMany(User::class, 'company_id', 'company_id')
            ->where('role', 'gestionnaire');
    }
}