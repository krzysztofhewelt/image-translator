<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class LoginRequest extends FormRequest
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
      'email' => 'required|email|exists:users,email',
      'password' => [
        'required',
        'regex:/^.*(?=.{1,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).{8,255}$/',
      ],
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
