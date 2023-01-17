<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailVerificationRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPassword;
use App\Mail\ForgetPassword;
use App\Models\User;
use App\Repositories\AuthRepository;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    private $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->middleware('auth', [
            'only' => [
                'logout', 'verifyToken', 'verifyAdmin'
            ]
        ]);
        $this->authRepository = $authRepository;
    }
    public function verifyToken()
    {
        //To verify the token in fornt end
        return ["valid" => true];
    }
    public function verifyAdmin()
    {
        $type = JWTAuth::parseToken()->getPayload()->get("type");
        if (!$type) {
            return response()->json("Unauthorized", 401);
        }
        //To verify the token in fornt end
        return ["valid" => true];
    }
    public function login()
    {
        $credentials = request(['email', 'password']);
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }
    public function register(RegisterRequest $request)
    {
        $this->authRepository->create($request->input());
        return $this->login($request);
    }
    public function logout()
    {
        //Make the current token invalid
        auth()->logout();
    }
    public function forgetPassword(User $user)
    {
        $token = Str::random(40);
        $this->authRepository->insertResetPassword($user->email, $token);
        Mail::to($user->email)->send(new ForgetPassword(['user' => $user, 'token' => $token]));
    }
    public function resetPassword(ResetPassword $request)
    {
        $passwordReset = $this->authRepository->getPasswordReset($request->token, 15);
        if (empty($passwordReset)) {
            return response()->json(["error" => "Token isn't valid"], 400);
        }
        $this->authRepository->changePassword($request->password, $passwordReset->email);
        $request->merge(["email" => $passwordReset->email]);
        return $this->login($request);
    }
    //Commons
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
