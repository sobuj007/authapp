<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\TasksModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TasksController extends Controller
{
    //

    function getAll()
    {
        try {
            $tasks = TasksModel::get();
            if ($tasks) {
                return response()->json([
                    'success' => true,
                    'message' => 'fetaching task complete',
                    'info' => $tasks
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'somthing worng'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    function create(Request $request)
    {
        $request->validate([
            'name' => 'required | string | max:256',
            'description' => 'required | string ',
            'image' => 'nullable | image',
            'status' => 'nullable | string',
            'catgeory_id' => 'required | integer'
        ]);


        try {
            $fileName = '';
            if ($request->hasFile('image')) {
                $fileName = time() . '-' . str_replace(' ', '--', $request->file('image')->getClientOriginalName());
                Storage::puFileAs('task/', $request->file('image'), $fileName);
            }
            $task = TasksModel::create([
                'name' => $request->name,
                'description' => $request->description,
                'image' => $fileName,
                'status' => $request->status,
                'category_id' => $request->category_id
            ]);
            if ($task) {
                return response()->json([
                    'success' => true,
                    'message' => " Task created successful"
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => "somthing worng"
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
}
