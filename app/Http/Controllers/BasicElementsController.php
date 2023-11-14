<?php

namespace App\Http\Controllers;

use App\Models\BasicElement;
use App\Models\DeviceData;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BasicElementsController extends Controller
{
    //BasicElement List
    // public function index()
    // {
    //     $elements = BasicElement::all();

    //     foreach ($elements as $element) {
    //         $BasicElement = BasicElement::whereID($element->id)->first();
    //         $element->name = $BasicElement->name;
    //         $element->board = $BasicElement->board;
    //         $element->small_marks_date = $BasicElement->small_marks_date;
    //         $element->small_marks_time = $BasicElement->small_marks_time;
    //         $element->small_marks_people = $BasicElement->small_marks_people;
    //         $element->small_marks_other = $BasicElement->small_marks_other;
    //         $element->on_name = $BasicElement->on_name;
    //         $element->off_name = $BasicElement->off_name;
    //         $element->type = $BasicElement->type;
    //         $element->switches = $BasicElement->switches;
    //     }

    //     return response([
    //         'message' => 'Basic Element list',
    //         'data' => $elements,
    //     ], Response::HTTP_OK);
    // }

    //前端基本元件資料更新
    public function update(Request $request)
    {
        //
        $request->validate([
            'id' => 'string',
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
        if (BasicElement::where('name', $request->name)->first()) {
            $return_id = BasicElement::where('name', $request->name)->update([
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
