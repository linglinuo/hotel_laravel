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
            // 'created' => true,
        ]);

        return response([
            'message' => 'Device connected successfully!',
        ], Response::HTTP_OK);
    }

    //硬體若已觸發
    public function controlDeviceOK(Request $request) //post
    {
        DeviceData::where('device_id', $request->device_id)->where('ctrl_cmd', $request->ctrl_cmd)->update([
            'trigger' => false,
        ]);

        return response([
            'message' => 'Device trigger OK!',
        ], Response::HTTP_OK);
    }

    //若硬體抓trigger為True則get ctrl_cmd
    public function triggerOrNot(Request $request) //post
    {
        $data = DeviceData::select('ctrl_cmd')
            ->where('device_id', $request->device_id)
            ->where('trigger', true)
            ->get();

        return response([
            'message' => 'Need trigger',
            'data' => $data,
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

    //前端 get 後端儲存的 所有device 的 data (包含humidity、temperature、ctrl_cmd)
    public function getDataElement()
    {
        $data = DeviceData::all();

        return response([
            'message' => 'Device Data Lists',
            'data' => $data,
        ], Response::HTTP_OK);
    }

    //前端 get 後端儲存的 ctrl cmd
    public function getDeviceCtrlCmd($id)
    {
        $data = DeviceData::whereDeviceId($id)->where('ctrl_cmd', '!=', 'dht')->get();
        $ctrlCmd = [];
        foreach ($data as $d) {
            $ctrlCmd[] = $d->ctrl_cmd;
        }

        return response([
            'message' => 'Device Data Lists',
            'data' => $ctrlCmd,
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

    public function updateDeviceDatas(Request $request)
    {
        $request->validate([
            'device_id' => 'required|string',
            'temp' => 'string|nullable',
            'humidity' => 'string|nullable',
            'ctrl_cmd' => 'string|nullable',
        ]);

        $return = null;

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
                    DeviceData::create([
                        'device_id' => $request->device_id,
                        'temp' => $request->temp,
                        'humidity' => $request->humidity,
                        'ctrl_cmd' => $request->ctrl_cmd,
                    ]);
                }
            } else {
                Device::create([
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
        ], Response::HTTP_OK);
    }
}
