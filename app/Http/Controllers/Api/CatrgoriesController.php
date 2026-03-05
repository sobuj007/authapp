<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoriesResource;
use App\Models\Api\CategoriesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CatrgoriesController extends Controller
{
    
    function getall(){
        try {
            //code...
            $categorys= CategoriesModel::getall();
            if($categorys){
                return response() ->json([
                    'status' => true,
                    'message' => 'categorys found',
                    'data' => $categorys
                ],200);
            }
            return response()-> json([
                'status' => false,
                'message' => 'categorys not found',
            ],404);

        } catch (\Throwable $th) {
            //throw $th;
            return response()-> json([
                'status' => false,
                'message' => $th -> getMessage(),
            ],500);
        }


    }

    function createCategory(Request $request){
        $request -> validate([
            'name' => 'required | string | max:256',
            'description' => 'required | string ',
            'iamge' => 'required | image',
        ]);
        try {
            if($request ->hasFile('image')){ 
                $fileName = time().'-'.str_replace(' ','--',$request->image->getClientOriginalName());
                Storage::putFileAs('/',$request ->file('image'),$fileName);
            }
         $category =   CategoriesModel::create([
                'name'=> $request ->name,
                'description' => $request -> description,
                'image' => $fileName
            ]);
            return response() ->json([
                'success' =>true,
                'message' => 'Category creation successful',
                'info' => new CategoriesResource($category)
            ]);


        } catch (\Throwable $th) {
            //throw $th;
            return response() -> json([
                'success' => false,
                'message' => $th ->getMessage() 
            ]);
        }
    }
}
