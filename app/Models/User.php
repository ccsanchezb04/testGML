<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table      = 'gml_user';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'last_name',
        'id_number',
        'email',
        'country',
        'address',
        'phone_number',
        'category_id'
    ];

    public function category()
    {
        return $this->hasMany('app/Models/Category');
    }
}
