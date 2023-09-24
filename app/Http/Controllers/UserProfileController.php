<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserProfileController extends Controller
{
    public function index()
    {
        $members = User::all()->makeHidden([
            'photo',
            'info',
            'members',
            'created_at',
            'updated_at'
        ]);

        foreach ($members as $member) {
            $user = User::whereEmail($member->email)->first();
            $profile = UserProfile::whereUserId($user->id)->first();
            $member->user_id = $user->id;
            $member->user_name = $user->name;
            $member->user_email = $member->email;
            $member->user_phone = $profile->phone;
            $member->user_photo = $profile->photo;
        }

        return response([
            'message' => 'user list',
            'data' => $members,
        ], Response::HTTP_OK);
    }

    public function get(Request $request)
    {
        $user = User::find($request->user()->id);
        $profile = UserProfile::where('user_id', $request->user()->id)->first();

        return response([
            'message' => 'user profile',
            'data' => [
                "name" => $user->name,
                "email" => $user->email,
                "phone" => $profile->phone,
                "birthday" => $profile->birthday,
                "img" => $profile->photo
            ]
        ], Response::HTTP_OK);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'string',
            'phone' => 'string',
            'birthday' => 'string',
        ]);

        if (UserProfile::where('phone', $request->phone)->where('user_id', '!=', $request->user()->id)->first()) {
            return response([
                'message' => 'phone exist!',
            ], Response::HTTP_BAD_REQUEST);
        }

        if (UserProfile::where('user_id', $request->user()->id)->first()) {
            User::where('id', $request->user()->id)->update(['name' => $request->name]);
            UserProfile::where('user_id', $request->user()->id)->update([
                'phone' => $request->phone,
                'birthday' => $request->birthday
            ]);
        } else {
            UserProfile::create([
                'user_id' => $request->user()->id,
                'phone' => $request->phone,
                'birthday' => $request->birthday
            ]);
        }

        return response([
            'message' => 'Profile updated successfully!',
        ], Response::HTTP_OK);
    }

    public function updateimg(Request $request)
    {
        $request->validate([
            'img' => 'string',
        ]);

        UserProfile::where('user_id', $request->user()->id)->update([
            'photo' => $request->img,
        ]);

        return response([
            'message' => 'Profile updated successfully!',
        ], Response::HTTP_OK);
    }

    public function destroy(Request $request, $id)
    {
        $user = User::find($id);
        $user->delete();

        return response([
            'message' => 'Profile deleted successfully!',
        ], Response::HTTP_OK);
    }
}
