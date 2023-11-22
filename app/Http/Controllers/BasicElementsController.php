<?php

namespace App\Http\Controllers;

use App\Models\BasicElement;
use App\Models\DeviceData;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BasicElementsController extends Controller
{
    // BasicElement List
    public function index($roomId)
    {
        //$elements = BasicElement::where();
        $deviceId = Device::select('device_id')->whereRoomId($roomId)->first();
        $deviceDatas = DeviceData::whereDeviceId($deviceId->device_id)->get();
        $results = [];

        foreach ($deviceDatas as $deviceData) {
            $BasicElement = BasicElement::whereId($deviceData->basic_element_id)->first();
            $BasicElement->uuid = $deviceId->device_id;
            $results[] = $BasicElement;
        }

        return response([
            'message' => 'Basic Element list',
            'data' => $results,
        ], Response::HTTP_OK);
    }

    //前端基本元件資料更新
    public function update(Request $request)
    {
        //
        $request->validate([
            'id' => 'string',
            'room_id' => 'string',
            'name' => 'string',
            'board' => 'string',
            'small_marks' => 'array',
            'type' => 'string',
            'ctrl_cmd_group' => 'array',
            'default_value' => 'string',
            'value' => 'array',
        ]);
        $encodeSmallMarks = json_encode($request->small_marks);
        $encodeValue = json_encode($request->value);
        $basicElement = BasicElement::where('name', $request->name)->first();
        if ($basicElement) {
            $return_id = BasicElement::whereId($basicElement->id)->update([
                'name' => $request->name,
                'board' => $request->board,
                'small_marks' => $encodeSmallMarks,
                'type' => $request->type,
                'default_value' => $request->default_value,
                'value' => $encodeValue,
            ]);
        } else {
            $return_id = BasicElement::create([
                'name' => $request->name,
                'board' => $request->board,
                'small_marks' => $encodeSmallMarks,
                'type' => $request->type,
                'default_value' => $request->default_value,
                'value' => $encodeValue,
            ]);
            DeviceData::whereDeviceId($request->id)->whereCtrlCmd($request->ctrl_cmd_group[0])->update([
                'basic_element_id' => $return_id->id,
            ]);
            Device::whereDeviceId($request->id)->update([
                'room_id' => $request->room_id,
                'created' => $request->True,
            ]);
        }

        return response([
            'message' => 'BasicElement updated successfully!',
            'data' => $return_id
        ], Response::HTTP_OK);
    }

    //得到單一一筆Basic Element資料
    public function get($id)
    {
        $BasicElement = BasicElement::find($id);
        $BasicElement->value = json_decode($BasicElement->value);
        return response([
            'message' => 'basic element',
            'data' => $BasicElement,
        ], Response::HTTP_OK);
    }

    public function destroy(Request $request, $id)
    {
        $BasicElement = BasicElement::find($id);
        $BasicElement->first()->delete();

        return response([
            'message' => 'BasicElement deleted successfully!',
        ], Response::HTTP_OK);
    }
}
