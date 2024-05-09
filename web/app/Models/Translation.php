<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Pagination\LengthAwarePaginator;

class Translation extends Model
{
  use HasFactory;

  protected $guarded = [];

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }

  public function getUserTranslations(int $userId)
  {
    return $this->where('user_id', $userId)
      ->orderBy('updated_at', 'DESC')
      ->paginate(15);
  }

  public function countUserTranslations(int $userId)
  {
      return $this->where('user_id', $userId)->count();
  }

  public function searchUserTranslations(
    int $userId,
    string $searchString = null
  ): LengthAwarePaginator {
    return $searchString == null
      ? $this->getUserTranslations($userId)
      : $this->where('user_id', $userId)
        ?->where('title', 'like', '%' . $searchString . '%')
        ->orderBy('updated_at', 'DESC')
        ->paginate(15)
        ->withQueryString();
  }
}
