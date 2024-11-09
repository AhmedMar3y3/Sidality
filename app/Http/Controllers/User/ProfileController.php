<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\updateProfileRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\User;



class ProfileController extends Controller
{
    public function getProfile(Request $request)
    {
        $user = Auth::user();
        return response()->json(['user' => $user], 200);
    }
    public function updateProfile(updateProfileRequest $request)
    {
        $user = Auth::user();
        $updateData = [];

        if ($request->has('name')) {
            $updateData['name'] = $request->input('name');
        }
        if ($request->has('phone')) {
            $updateData['phone'] = $request->input('phone');
        }
        if ($request->has('address')) {
            $updateData['address'] = $request->input('address');
        }

       
        $user->update($updateData);
        return response()->json(['user' => $user, 'message' => 'Profile updated successfully'], 200);
    }
    public function changePassword(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $validatedData = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|confirmed',
        ]);

        if (!Hash::check($validatedData['current_password'], $user->password)) {
            return response()->json([
                'message' => 'The current password is incorrect.',
            ], 400);
        }

        $user->password = Hash::make($validatedData['new_password']);
        $user->save();
        return response()->json([
            'message' => 'Password changed successfully.',
        ], 200);
    }
}
