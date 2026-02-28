<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data=[
            'email'=> $this->email,
            'name' => $this -> name,
            'image' => asset('upload/'.$this -> image),
            'created_at' => $this -> created_at -> format('F j, Y')

        ];
        return $data;
    }
}
