<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Exception;

class AuthService
{

    public function register(array $data)
    {
        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $token = JWTAuth::fromUser($user);

            return [
                'user' => $user,
                'token' => $token,
            ];
        } catch (Exception $e) {
            throw new Exception('Registration failed: ' . $e->getMessage());
        }
    }

    public function login(array $data)
    {
        try {
            $user = User::where('email', $data['email'])->first();

            if (!$user || !Hash::check($data['password'], $user->password)) {
                throw new Exception('The provided credentials are incorrect.');
            }

            $token = JWTAuth::fromUser($user);

            return [
                'user' => $user,
                'access_token' => $token,
            ];
        } catch (JWTException $e) {
            throw new Exception('Failed to create token: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception('Login failed: ' . $e->getMessage());
        }
    }

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return ['message' => 'Logged out successfully'];
        } catch (JWTException $e) {
            throw new Exception('Failed to logout: ' . $e->getMessage());
        }
    }
}
