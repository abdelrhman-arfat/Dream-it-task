<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeUserMail;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Triats\LoggerTrait;
use App\Triats\ResponseTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ResponseTrait, LoggerTrait;

    /**
     * Register a new user
     */
    public function register(RegisterRequest $request)
    {
        try {
            $validated = $request->validated();

            $user = User::create([
                'name_ar' => $validated['name_ar'],
                'name_en' => $validated['name_en'],
                'email'   => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $user->assignRole("author");

            // observer if i want automatically send email
            Mail::to($user->email)->queue(new WelcomeUserMail($user));

            return $this->successResponse([
                'user' => $user,
            ], __('messages.auth.success'));
        } catch (\Exception $e) {
            $this->logger("failed to register with user AuthController@Register");
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Login user
     */
    public function login(LoginRequest $request)
    {
        try {
            $user = $request->getValidatedUser();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return $this->errorResponse(__('messages.auth.failed'), 401);
            }

            $token = $user->createToken('api-token')->plainTextToken;

            return $this->successResponse([
                'user'  => $user,
                'token' => $token,
            ], __('messages.auth.success'));
        } catch (\Exception $e) {
            $this->logger("failed to login with user AuthController@Login");
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
