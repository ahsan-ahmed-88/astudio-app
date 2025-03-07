<?php

namespace App\Http\Controllers;

use App\Models\Attribute as Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttributeController extends Controller
{
    public function store(Request $request) {

        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:attributes',
                'type' => 'required|in:text,date,number,select'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }


            return response()->json(Attribute::create($request->all()), 200);
        } catch (\Throwable $e) {
            return  response()->json(['message' => $e->getMessage()], 400);
        }
    }
    public function index(Request $request) {

        try {
            return response()->json(Attribute::get(), 200);
        } catch (\Throwable $e) {
            return  response()->json(['message' => $e->getMessage()], 400);
        }
    }


    public function update($attribute, Request $request) {

        try {
            $validator = Validator::make($request->all(), [
                'name' => 'unique:attributes',
                'type' => 'in:text,date,number,select'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }


            return response()->json(Attribute::where('id', $attribute)->update($request->all()), 200);
        } catch (\Throwable $e) {
            return  response()->json(['message' => $e->getMessage()], 400);
        }
    }


    public function destroy($attribute, Request $request) {

        try {

            return response()->json(Attribute::where('id', $attribute)->delete(), 200);
        } catch (\Throwable $e) {
            return  response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
