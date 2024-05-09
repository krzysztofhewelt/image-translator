<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class TranslationRequest extends FormRequest
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
    return [
      'title' => 'required|min:3|max:255',
      'translated_text' => 'required|min:3|max:65535',
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
