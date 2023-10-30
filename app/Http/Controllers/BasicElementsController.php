<?php

namespace App\Http\Controllers;

use App\Models\BasicElement;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BasicElementsController extends Controller
{
    //BasicElement List
    public function index()
    {
        $elements = BasicElement::all();

        foreach ($elements as $element) {
            $BasicElement = BasicElement::whereID($element->id)->first();
            $element->name = $BasicElement->name;
            $element->board = $BasicElement->board;
            $element->small_marks_date = $BasicElement->small_marks_date;
            $element->small_marks_time = $BasicElement->small_marks_time;
            $element->small_marks_people = $BasicElement->small_marks_people;
            $element->small_marks_other = $BasicElement->small_marks_other;
            $element->on_name = $BasicElement->on_name;
            $element->off_name = $BasicElement->off_name;
            $element->type = $BasicElement->type;
            $element->switches = $BasicElement->switches;
        }

        return response([
            'message' => 'Basic Element list',
            'data' => $elements,
        ], Response::HTTP_OK);
    }

    //前端基本元件資料更新
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'string',
            'board' => 'int',
            'small_marks_date' => 'string',
            'small_marks_time' => 'string',
            'small_marks_people' => 'string',
            'small_marks_other' => 'string',
            'on_name' => 'string|nullable',
            'off_name' => 'string|nullable',
            'type' => 'string',
            'switches' => 'string',
        ]);
        if (BasicElement::where('name', $request->name())->first()) {
            BasicElement::where('name', $request->name())->update([
                'name' => $request->name,
                'board' => $request->board,
                'small_marks_date' => $request->small_marks_date,
                'small_marks_time' => $request->small_marks_time,
                'small_marks_people' => $request->small_marks_people,
                'small_marks_other' => $request->small_marks_other,
                'on_name' => $request->on_name,
                'off_name' => $request->off_name,
                'type' => $request->type,
                'switches' => $request->switches
            ]);
        } else {
            $return_id = BasicElement::create([
                'name' => $request->name,
                'board' => $request->board,
                'small_marks_date' => $request->small_marks_date,
                'small_marks_time' => $request->small_marks_time,
                'small_marks_people' => $request->small_marks_people,
                'small_marks_other' => $request->small_marks_other,
                'on_name' => $request->on_name,
                'off_name' => $request->off_name,
                'type' => $request->type,
                'switches' => $request->switches
            ]);
        }

        return response([
            'message' => 'BasicElement updated successfully!',
        ], Response::HTTP_OK);
    }

    //得到單一一筆Basic Element資料
    public function get(Request $request)
    {
        $BasicElement = BasicElement::find($request->id);
        return response([
            'message' => 'basic element',
            'data' => [
                "name" => $BasicElement->name,
                "board" => $BasicElement->board,
                "small_marks_date" => $BasicElement->small_marks_date,
                "small_marks_time" => $BasicElement->small_marks_time,
                "small_marks_people" => $BasicElement->small_marks_people,
                "small_marks_other" => $BasicElement->small_marks_other,
                "on_name" => $BasicElement->on_name,
                "off_name" => $BasicElement->off_name,
                "type" => $BasicElement->type,
                "switches" => $BasicElement->switches
            ]
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
