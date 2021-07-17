<?php

namespace App\Http\Controllers\Api\User;

use App\Exceptions\DefaultException;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private $user;

    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }


    public function store(UserRequest $request)
    {
        $data = $request->all();

        try {
            $user = $this->user->createUser($data);

            return response()->json([
                'data' => ['message' => 'User created successfully!', 'user' => $user]
            ], 201);
        } catch ( DefaultException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], $e->getCode());
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }


    public function show()
    {
        try {
            $user = $this->user->find(2);//Auth::user()->id

            if (!$user) {
                return response()->json(['message' => 'User doesn\'t found']);
            }

            return response()->json(['data' => $user], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 422);
        }
    }


    public function update(Request $request)
    {
        $data = $request->all();
        $data['id'] = 1;//Auth::user()->id

        //verifying if the password field has been set
        if ($request->has('password') && $request->get('password')) {
            //validating password
            Validator::make($data, [
                'password' => 'required|min:4'
            ])->validate();

            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        try {

            $user = $this->user->updateUser($data);

            return response()->json([
                'data' => [
                    'message' => 'User updated successfully!',
                    'user' => $user
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], $e->getCode());
        }
    }


    public function destroy()
    {
        // dd($request->id);
        try {
            $this->user->deleteUser(1);//Auth::user()->id

            return response()->json(['status' => 'success', 'message' => 'User deleted successfully'], 200);
        } catch (DefaultException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], $e->getCode());
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 422);
        }
    }

}
