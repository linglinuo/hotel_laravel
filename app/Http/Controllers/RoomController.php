<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = Room::all()->makeHidden([
            'photo',
            'info',
            'members',
            'created_at',
            'updated_at'
        ]);

        foreach ($rooms as $room) {
            $user = User::whereEmail($room->email)->first();
            $profile = UserProfile::whereUserId($user->id)->first();
            $room->user_name = $user->name;
            $room->user_phone = $profile->phone;
            $room->user_photo = $profile->photo;
        }

        return response([
            'message' => 'room list',
            'data' => $rooms,
        ], Response::HTTP_OK);
    }


    /**
     * Display the specified resource.
     */
    public function get(Request $request, $id)
    {
        $room = Room::find($id);
        $user = User::whereEmail($room->email)->first();
        $userProfile = UserProfile::whereUserId($user->id)->first();

        return response([
            'message' => 'user profile',
            'data' => [
                "user" => [
                    "name" => $user->name,
                    "email" => $user->email,
                    "phone" => $userProfile->phone,
                    "user_img" => $userProfile->photo,
                ],
                "room" => [
                    'no' => $room->no,
                    'type' => $room->type,
                    'room_name' => $room->room_name,
                    'status' => $room->status,
                    'info' => $room->info,
                    "img" => $room->photo
                ]
            ]
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'no' => 'string',
            'type' => 'int',
            'room_name' => 'string',
            'status' => 'string',
            'email' => 'string|nullable',
            'info' => 'string',
        ]);

        if (Room::where('id', $id)->first()) {
            Room::where('id', $id)->update([
                'no' => $request->no,
                'type' => $request->type,
                'room_name' => $request->room_name,
                'status' => $request->status,
                'email' => $request->email,
                'info' => $request->info
            ]);
        } else {
            Room::create([
                'no' => $request->no,
                'type' => $request->type,
                'room_name' => $request->room_name,
                'status' => $request->status,
                'email' => $request->email,
                'info' => $request->info
            ]);
        }

        return response([
            'message' => 'Room updated successfully!',
        ], Response::HTTP_OK);
    }

    public function updateimg(Request $request, $id)
    {
        $request->validate([
            'img' => 'string',
        ]);

        Room::where('id', $id)->update([
            'photo' => $request->img,
        ]);

        return response([
            'message' => 'Room photo updated successfully!',
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        Room::where('id', $request->room()->id)->first()->delete();

        return response([
            'message' => 'Room photo deleted successfully!',
        ], Response::HTTP_OK);
    }
}
