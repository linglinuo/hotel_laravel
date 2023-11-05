<?php

namespace App\Http\Controllers;

use App\Models\BasicElement;
use App\Models\Device;
use App\Models\DeviceData;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DeviceController extends Controller
{

    //前端call後端
    public function updateDeviceConnect(Request $request, $roomId, $uuid)
    {
        Device::where('device_id', $uuid)->update([
            'basic_element_id' => $request->basic_element_id,
            'room_id' => $roomId,
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

    public function getDataElement()
    {
        $data = DeviceData::all();

        return response([
            'message' => 'Device Data Lists',
            'data' => $data,
        ], Response::HTTP_OK);
    }

    public function getDeviceElement()
    {
        $devices = Device::all();

        return response([
            'message' => 'Device Lists',
            'data' => $devices,
        ], Response::HTTP_OK);
    }

    //硬體call後端
    public function updateDevices(Request $request) //需修
    {
        $request->validate([
            'device_id' => 'string',
            'created' => 'boolean',
        ]);


        return response([
            'message' => 'Device updated successfully!',
        ], Response::HTTP_OK);
    }

    public function updateDeviceDatas(Request $request)
    {
        $request->validate([
            'device_id' => 'required|string',
            'temp' => 'string|nullable',
            'humidity' => 'string|nullable',
            'ctrl_cmd' => 'string|nullable',
        ]);

        if ($request->ctrl_cmd != "no data") {
            if (Device::where('device_id', $request->device_id)->first()) {
                if (DeviceData::where('device_id', $request->device_id)
                    ->where('ctrl_cmd', $request->ctrl_cmd)
                    ->first()
                ) {
                    DeviceData::where('device_id', $request->device_id)
                        ->where('ctrl_cmd', $request->ctrl_cmd)
                        ->update([
                            'device_id' => $request->device_id,
                            'temp' => $request->temp,
                            'humidity' => $request->humidity,
                            'ctrl_cmd' => $request->ctrl_cmd,
                        ]);
                } else {
                    $return = DeviceData::create([
                        'device_id' => $request->device_id,
                        'temp' => $request->temp,
                        'humidity' => $request->humidity,
                        'ctrl_cmd' => $request->ctrl_cmd,
                    ]);
                }
            } else {
                $return = Device::create([
                    'device_id' => $request->device_id,
                    'created' => false,
                ]);
                DeviceData::create([
                    'device_id' => $request->device_id,
                    'temp' => $request->temp,
                    'humidity' => $request->humidity,
                    'ctrl_cmd' => $request->ctrl_cmd,
                ]);
            }
        }

        return response([
            'message' => 'Device updated successfully!',
            'data' => $return,
        ], Response::HTTP_OK);
    }
}
