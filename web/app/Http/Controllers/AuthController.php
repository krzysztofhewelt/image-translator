<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    private User $userModel;

    protected int $maxAttempts = 3;
    protected int $decayMinutes = 1;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function login(Request $request): JsonResponse
    {
        $test = RateLimiter::attempt(
            $request->ip(),
            $perMinute = 5,
            function() {
                return "dupa";
            }
        );

        if (! $test) {
            return response()->json(['too many attempts'], Response::HTTP_BAD_REQUEST);
        }

        $credentials = $request->only('email', 'password');
        $user = $this->userModel->getUserByEmail($credentials['email']);

        if ($token = Auth::attempt($credentials)) {
//            $user->last_success_login = now();
//            $user->locale = App::getLocale();
//            $user->save();
//            $this->clearLoginAttempts($request);


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

//        $user->last_wrong_login = now();
//        $user->save();

        return response()->json(
            ['errors' => ['account' => trans('auth.failed')]],
            Response::HTTP_UNAUTHORIZED,
        );
    }

    public function logout(): JsonResponse
    {
        Auth::logout();
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
                ['errors' => ['account' => 'Can not refresh token']],
                Response::HTTP_UNAUTHORIZED,
            );
        }
    }

}
