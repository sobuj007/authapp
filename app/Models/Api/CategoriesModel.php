<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class CategoriesModel extends Model
{
    //

    protected $guarded = ['id','created_at','updated_at'];

    function task(){
        return $this -> hasMany(TasksModel::class);
    }
}
