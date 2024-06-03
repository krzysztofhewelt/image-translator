<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class ChangeSelfPasswordRequest extends FormRequest
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
      'current_password' => [
        'required',
        function ($attribute, $value, $fail) {
          if (!Hash::check($value, User::find(Auth::id())->password)) {
            $fail(trans('validation.current_password'));
          }
        },
      ],
      'new_password' => [...$rules['password'], 'different:current_password'],
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
