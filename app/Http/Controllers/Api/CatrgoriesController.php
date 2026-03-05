<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoriesResource;
use App\Models\Api\CategoriesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CatrgoriesController extends Controller
{
    
    function getall(){
        try {
            //code...
            $categorys= CategoriesModel::all();
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
            'iamge' => 'nullable | image',
        ]);
        try {
            if($request ->hasFile('image')){ 
                $fileName = time().'-'.str_replace(' ','--',$request->image->getClientOriginalName());
                Storage::putFileAs('category/',$request ->file('image'),$fileName);
            }
         $category =   CategoriesModel::create([
                'name'=> $request ->name,
                'description' => $request -> description,
                'image' => $fileName??''
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

    function updateCategory(Request $request){
        $request -> validate([
            'name' => 'required | string | max:256',
            'description' => 'required | string',
            'image' => 'nullable | image'
        ]);

        try {
            $fileName = CategoriesModel::find($request ->id);
            if($request ->file('image')){
                Storage::delete($fileName);
                $fileName= time().'-'.str_replace(' ','--',$request->file('image')->getClientOriginalName());

                Storage::putFileAs('category/',$request->file('image'),$fileName);
            }
            $category = CategoriesModel::find($request->id);

            $category->update([
                'name' => $request ->name,
                'description' => $request -> description,
                'image' =>$fileName
            ]);

            return response() -> json([
                'success' => true ,
                'message' => "Category updated successfuly"
            ]);
            

            
        } catch (\Throwable $th) {
            //throw $th;
            return response() -> json([
                'success' =>false,
                'message' => $th->getMessage()

            ]);
        }
    }

    function deleteCategory (Request $request ){
   try {
         if(!Auth::check()){
            return response() ->json([
                'success' => false,
                'message' => "your not authorized"
            ]);
        }
        $category = CategoriesModel::find($request ->id);
        $category->delete();

        return response() -> json([
        "success" => true,
        "essage" => "category deleted successfuly"
        ]);
   } catch (\Throwable $th) {
    
            return response() -> json([
                'success' =>false,
                'message' => $th->getMessage()

            ]);
   }
    }
}
