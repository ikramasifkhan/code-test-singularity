<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OutletResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "name"=>$this->name,
            "phone"=>$this->phone,
            "latitude"=>$this->latitude,
            "longitude"=>$this->longitude,
            'outlet_image'=>new ImageResource($this->whenLoaded('image')),
        ];
    }
}
