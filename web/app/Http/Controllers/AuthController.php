<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
  private User $userModel;

  protected int $maxBadAttemptsPerMinute = 3;

  public function __construct()
  {
    $this->userModel = new User();
  }

  public function register(RegisterRequest $request): JsonResponse
  {
    $user = $this->userModel->create([
      'username' => $request->username,
      'email' => $request->email,
      'password' => bcrypt($request->password),
    ]);

    $token = Auth::login($user);

    return response()
      ->json(
        [
          'success' => 'User created successfully',
        ],
        Response::HTTP_CREATED
      )
      ->header('Authorization', $token);
  }

  public function login(LoginRequest $request): JsonResponse
  {
    if (
      RateLimiter::tooManyAttempts(
        $request->ip(),
        $this->maxBadAttemptsPerMinute
      )
    ) {
      return response()->json(
        ['errors' => ['account' => trans('auth.throttle')]],
        Response::HTTP_TOO_MANY_REQUESTS
      );
    }

    $credentials = $request->only('email', 'password');
    $user = $this->userModel->getUserByEmail($credentials['email']);

    if ($user->isBanned()) {
      return response()->json(
        ['errors' => ['account' => trans('auth.banned')]],
        Response::HTTP_UNAUTHORIZED
      );
    }

    if ($token = Auth::attempt($credentials)) {
      RateLimiter::clear($request->ip());
      $user->last_success_login = now();
      $user->save();

      return response()
        ->json([
          'status' => 'success',
          'user' => [
            'id' => $user->id,
            'email' => $user->email,
          ],
        ])
        ->header('Authorization', $token);
    }

    $user->last_wrong_login = now();
    $user->save();

    RateLimiter::increment($request->ip());

    return response()->json(
      ['errors' => ['account' => trans('auth.failed')]],
      Response::HTTP_UNAUTHORIZED
    );
  }

  public function logout(): JsonResponse
  {
    try {
      Auth::logout();
    } catch (\Exception $exception) {
      return response()->json(['errors' => $exception->getMessage()]);
    }

    return response()->json(['status' => 'success']);
  }

  public function refresh(): JsonResponse
  {
    try {
      $token = Auth::refresh();
      return response()
        ->json(['status' => 'success'])
        ->header('Authorization', $token);
    } catch (\Exception $e) {
      return response()->json(
        ['errors' => 'Can not refresh token'],
        Response::HTTP_UNAUTHORIZED
      );
    }
  }
}
