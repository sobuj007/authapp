<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class TasksModel extends Model
{
    //
    protected $guarded =['id','created_at','updated_at'];
    function category(){
      return  $this -> belongsTo(CategoriesModel::class);
    }
    
    
}
