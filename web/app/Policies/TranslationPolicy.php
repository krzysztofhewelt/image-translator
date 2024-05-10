<?php

namespace App\Policies;

use App\Models\Translation;
use App\Models\User;

class TranslationPolicy
{
    public function showUpdateDelete(User $user, Translation $translation): bool
    {
      return $translation->user_id === $user->id;
    }
}
