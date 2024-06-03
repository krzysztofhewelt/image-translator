<?php

namespace App\Rules;

use App\Support\Languages;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AvailableLanguage implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
      $isValid = array_column(Languages::$languages, null, 'tesseractCode')[$value] ?? false;

      if (!$isValid) {
        $fail("$value is not a valid language.");
      }
    }
}
