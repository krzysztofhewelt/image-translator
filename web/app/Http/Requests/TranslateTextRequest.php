<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class TranslateTextRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    $rules = require app_path('Rules/ValidationRules.php');

    return [
      'original_text' => $rules['original_text'],
      'source_lang' => $rules['source_lang'],
      'target_lang' => $rules['target_lang'],
    ];
  }

  protected function failedValidation(Validator $validator): void
  {
    $errors = $validator->errors();

    throw new HttpResponseException(
      response()->json(
        [
          'errors' => $errors,
        ],
        Response::HTTP_UNPROCESSABLE_ENTITY
      )
    );
  }
}