<?php

namespace App\Repositories;

use App\Models\User;
use Carbon\Carbon;
use App\Models\PasswordReset;

class AuthRepository
{
    public function create($user)
    {
        return User::create($user);
    }
    public function insertResetPassword($email, $token)
    {
        $passwordReset = PasswordReset::where("email", $email)->first();
        if ($passwordReset) {
            $passwordReset->token = $token;
            $passwordReset->created_at =  Carbon::now();
            $passwordReset->save();
        } else {
            PasswordReset::insert(['email' => $email, 'token' => $token, 'created_at' => Carbon::now()]);
        }
        return $token;
    }
    public function getPasswordReset($token, $expirationTime)
    {
        $passwordReset = PasswordReset::where('token', $token)
            ->where('created_at', '>', Carbon::now()->subMinutes($expirationTime))->first();
        return $passwordReset;
    }
    public function changePassword($password, $email)
    {
        User::where('email', $email)
            ->update(['password' => bcrypt($password)]);
        PasswordReset::where('email', $email)->delete();
    }
}
