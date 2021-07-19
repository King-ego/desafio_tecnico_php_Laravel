<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthRepository extends AbstractRepository
{
    protected $model = User::class;

    public function authenticate ($data): array
    {
//        dd($request['email']);
        try {
            $user = $this->model::where('email', $data['email'])->first();;
//            dd($user);

            if (! $user || ! Hash::check($data['password'], $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }
            $token = $user->createToken($data['email'])->plainTextToken;

            return ['user' => $user, 'token' => $token];
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
