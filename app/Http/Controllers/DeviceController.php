<?php

namespace App\Http\Controllers;

use App\Models\BasicElement;
use App\Models\Device;
use App\Models\DeviceData;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DeviceController extends Controller
{
    
    //前端
    public function updateDeviceConnect(Request $request)
    {
        Device::where('device_id', $request->device_id())->create([
            'basic_element_id' => $request->basic_element_id,
        ]);

        return response([
            'message' => 'Device connected successfully!',
        ], Response::HTTP_OK);
    }

    public function index()
    {
        $devices = Device::where('created', 0)->get();
        foreach ($devices as $device) {
            $device->uuid = $devices->uuid;
        }

        return response([
            'message' => 'Uncreated Devices list',
            'data' => $devices,
        ], Response::HTTP_OK);
    }


    public function updateDevices(Request $request)
    {
        $request->validate([
            'device_id' => 'string',
            'room_id' => 'string|nullable',
            'created' => 'boolean',
        ]);
        if (Device::where('device_id', $request->device_id())->first()) {
            Device::where('device_id', $request->device_id())->update([
                'device_id' => $request->device_id,
                'created' => $request->created,
            ]);
        } else {
            $return = BasicElement::create([
                'device_id' => $request->device_id,
                'created' => $request->created,
            ]);
        }

        return response([
            'message' => 'Device updated successfully!',
        ], Response::HTTP_OK);
    }

    public function updateDeviceDatas(Request $request)
    {
        $request->validate([
            'device_id' => 'string',
            'temp' => 'string|nullable',
            'humidity' => 'string|nullable',
            'ctrl_cmd' => 'string|nullable',
        ]);
        $return = BasicElement::create([
            'device_id' => $request->device_id,
            'temp' => $request->temp,
            'humidity' => $request->humidity,
            'ctrl_cmd' => $request->ctrl_cmd,
        ]);

        return response([
            'message' => 'Device updated successfully!',
        ], Response::HTTP_OK);
    }
}
