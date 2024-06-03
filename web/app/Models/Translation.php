<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use App\Support\Path;

class Translation extends Model
{
  use HasFactory;

  protected $guarded = [];

  protected function imageName(): Attribute
  {
    return Attribute::make(
      get: fn(string $value) => new Path(
        $value,
        Storage::disk('public')->url($value)
      )
    )->withoutObjectCaching();
  }

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
        ->where('title', 'like', '%' . $searchString . '%')
        ->orderBy('updated_at', 'DESC')
        ->paginate(1)
        ->withQueryString();
  }
}
