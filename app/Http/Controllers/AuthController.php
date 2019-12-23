<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Auth;

/**
 * Class for managing and authenticating users
 */
class AuthController extends Controller
{
    /**
     * Register a user
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request) :JsonResponse
    {
        $this->validate($request, [
            'email'    => 'required|email|unique:users|max:191',
            'password' => 'required|min:4',
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'phone' => 'required|string'
        ]);

        $user = User::create([
            'first_name'     => $request->first_name,
            'last_name'      => $request->last_name,
            'phone'          => $request->phone,
            'email'          => $request->email,
            'password'       => $request->password,
         ]);

        return $this->login($request);
    }

    /**
     * Login user
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request) :JsonResponse
    {
        $this->validate($request, [
            'email'    => 'required|email|max:191',
            'password' => 'required|min:4',
        ]);

        try {

            if (!$token = Auth::attempt($request->only('email', 'password'))) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], 500);

        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], 500);

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent' => $e->getMessage()], 500);

        }

        $user = Auth::user();

        $this->updateLoggedInAt($user);
        return $this->respondWithToken($token, $user);
    }

    /**
     * Refresh jwt
     * 
     * @return JsonResponse
     */
    public function refresh() :JsonResponse
    {
        $token = Auth::refresh(true, true);

        return $this->success($token);
    }

    /**
     * Logout user
     * 
     * @param Request
     * @return JsonResponse
     */
    public function logout() :JsonResponse
    {
        Auth::logout(true);
        return $this->success('logged out');
    }

    /**
     * Set json response token, and user email
     * 
     * @param string $token
     * @param User $user
     * @return JsonResponse
     */
    protected function respondWithToken(string $token, User $user) :JsonResponse
    {
        return response()->json([
            'token' => $token,
            'email' => $user->email,
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    /**
     * Update the user logged in at date
     * 
     * @param User $user
     * @return void
     */
    protected function updateLoggedInAt(User $user)
    {
       $user->loggedin_at = now();
       $user->save();
    }
}
