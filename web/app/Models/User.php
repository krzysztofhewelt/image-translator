<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Pagination\LengthAwarePaginator;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
  use HasFactory, Notifiable;

  protected $guarded = [];

  protected $hidden = ['password'];

  /*
   * JWT
   */
  public function getJWTIdentifier()
  {
    return $this->getKey();
  }

  public function getJWTCustomClaims()
  {
    return [];
  }

  /*
   * Relations
   */
  public function translations(): HasMany
  {
    return $this->hasMany(Translation::class, 'user_id', 'id');
  }

  public function isBanned(): bool
  {
    return $this->banned === true;
  }

  public function getUserByEmail(string $email): ?User
  {
    return $this->where('email', $email)->first();
  }
}
