<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'image_name' => $this->image_name,
            'image_path' => asset('storage/') . $this->image_path,
            'imageable_id' => $this->image_id,
            'imageable_type' => $this->image_type,
        ];
    }
}
