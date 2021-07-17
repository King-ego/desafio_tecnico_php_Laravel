<?php

namespace App\Repositories;

use App\Exceptions\DefaultException;
use App\Models\User;

class UserRepository extends AbstractRepository
{
    protected $model = User::class;

    public function createUser($data)
    {
        try {
            $data['password'] = bcrypt($data['password']);
            $user = $this->create($data);

            if(!$user) {
                throw new DefaultException('Email already registered', 409);
            }

            return $user;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function updateUser($data)
    {
        // dd($data);
        try {
            $user = $this->findOrFail($data['id']);
            // dd($user);
            $user->update($data);

            return response()->json(['data' => [
                'message' => 'User updated successfully!',
                'user' => $user]
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 422);
        }
    }

    public function deleteUser($id)
    {
        $user = $this->find($id);

        if (!$user){
            throw new DefaultException('It was not possible to delete the user', 422);
        }

        $user->delete();

        return true;

    }
}
