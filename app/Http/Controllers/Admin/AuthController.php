<?php

namespace App\Http\Controllers\Admin;

use App\Model\Admin;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Auth;
use App\Http\Controllers\Controller;

/**
 * Class for managing and authenticating users
 */
class AuthController extends Controller
{
    /**
     * Register an admin
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

        Admin::create([
            'first_name'     => $request->first_name,
            'last_name'      => $request->last_name,
            'phone'          => $request->phone,
            'email'          => $request->email,
            'password'       => $request->password,
         ]);

        return $this->login($request);
    }

    /**
     * Login admin
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

            if (!$token = Auth::guard('admin')->attempt($request->only('email', 'password'))) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], 500);

        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], 500);

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent' => $e->getMessage()], 500);

        }

        $admin = Auth::guard('admin')->user();

        $this->updateLoggedInAt($admin);
        return $this->respondWithToken($token, $admin);
    }

    /**
     * Refresh jwt
     * 
     * @return JsonResponse
     */
    public function refresh() :JsonResponse
    {
        $token = Auth::guard('admin')->refresh(true, true);

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
        Auth::guard('admin')->logout(true);
        return $this->success('logged out');
    }

    /**
     * Set json response token, and user email
     * 
     * @param string $token
     * @param Admin $admin
     * @return JsonResponse
     */
    protected function respondWithToken(string $token, Admin $admin) :JsonResponse
    {
        return response()->json([
            'token' => $token,
            'email' => $admin->email,
            'expires_in' => auth()->guard('admin')->factory()->getTTL() * 60
        ]);
    }

    /**
     * Update the user logged in at date
     * 
     * @param Admin $admin
     * @return void
     */
    protected function updateLoggedInAt(Admin $admin) :void
    {
       $admin->loggedin_at = now();
       $admin->save();
    }
}
