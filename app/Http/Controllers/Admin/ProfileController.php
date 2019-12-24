<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Model\Admin;
use Auth;
use App\Http\Controllers\Controller;


class ProfileController extends Controller
{
     /**
     * Get Profile details
     */
    public function get(Admin $admin) :JsonResponse
    {
        return response()->json($admin);
    }

    /**
     * Update Profile details
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request) :JsonResponse
    {
        $this->validate($request, [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'required|string',
            'dob' => 'nullable|date'
        ]);
        
        $user = Auth::guard('admin')->user();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->phone = $request->phone;
        $user->dob = $request->dob;

        $user->save();

        return $this->success('Profile updated');
    }
}
