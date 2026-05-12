<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'nip',
        'jabatan',
        'no_hp',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * URL profile untuk AdminLTE
     */
    public function adminlte_profile_url()
    {
        return route('profile.index');
    }

    /**
     * Deskripsi user
     */
    public function adminlte_desc()
    {
        return $this->jabatan ?? 'User';
    }

    /**
     * Foto profile user
     */
    public function adminlte_image()
    {
        return null;
    }
}
