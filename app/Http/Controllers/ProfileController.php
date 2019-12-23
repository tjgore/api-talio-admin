<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;


class ProfileController extends Controller
{
     /**
     * Get Profile details
     */
    public function get(User $user) //:JsonResponse
    {
        return response()->json($user);
    }
}
