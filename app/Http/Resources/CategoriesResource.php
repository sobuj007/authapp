<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoriesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'name' =>$this-> name,
            'image' => asset('/',$this->image),
            'description' => $this -> description,
            'created_at' => $this ->created_at->format('F j,Y')


        ];
        return $data;
    }
}
