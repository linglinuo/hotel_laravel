<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomMember;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    //所有房間
    public function index()
    {
        $rooms = Room::all()->makeHidden([
            'photo',
            'info',
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

    //會員可控制的所有房間
    public function search(Request $request)
    {
        $user = User::find($request->user()->id);
        $rooms = RoomMember::where('member', $request->user()->id);
        foreach ($rooms as $room) {
            $profile = Room::where('no', $request->roomMembers()->room_id)->first();
            $room->room_no = $profile->no;
            $room->room_type = $profile->type;
            $room->room_name = $profile->room_name;
            $room->room_info = $profile->info;
            $room->room_photo = $profile->photo;
        }

        return response([
            'message' => 'room search',
            'data' => $rooms,
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     */
    public function get(Request $request, $id)
    {
        $room = Room::find($id);
        $roomUser = RoomMember::whereRoomId($id)->get();
        $user = [];
        foreach ($roomUser as $roomUserInfo) {
            $userInfo = User::find($roomUserInfo->user_id);
            $userProfileInfo = UserProfile::whereUserId($roomUserInfo->user_id)->first();
            $user[] = [
                "name" => $userInfo->name,
                "email" => $userInfo->email,
                "phone" => $userProfileInfo->phone,
                "img" => $userProfileInfo->photo,
            ];
        }

        return response([
            'message' => 'user profile',
            'data' => [
                "user" => $user,
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
        $return_id = '';
        if (Room::where('id', $id)->first()) {
            $return_id = $id;
            Room::where('id', $id)->update([
                'no' => $request->no,
                'type' => $request->type,
                'room_name' => $request->room_name,
                'status' => $request->status,
                'email' => $request->email,
                'info' => $request->info
            ]);
        } else {
            $return_id = Room::create([
                'no' => $request->no,
                'type' => $request->type,
                'room_name' => $request->room_name,
                'status' => $request->status,
                'email' => $request->email,
                'info' => $request->info
            ])->id;
        }

        return response([
            'message' => 'Room updated successfully!',
            'data' => [
                'room_id' => $return_id,
            ]
        ], Response::HTTP_OK);
    }

    public function updateroommember(Request $request, $room_id)
    {
        $request->validate([
            'email' => 'required|array',
            'email.*' => 'required|string',
        ]);
        RoomMember::whereRoomId($room_id)->delete();
        foreach ($request->email as $email) {
            $user = User::whereEmail($email)->first();
            if (RoomMember::where('user_id', $email)->first()) {
                Room::where('member', $email)->update([
                    'room' => $room_id,
                    'member' => $user->id,
                ]);
            } else {
                RoomMember::create([
                    'room_id' => $room_id,
                    'user_id' => $user->id,
                ]);
            }
        }

        return response([
            'message' => 'Room photo updated successfully!',
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
