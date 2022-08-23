<?php

namespace App\Services;

use App\Models\Image;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ImageUploadService
{
    //Add image
    public function addImage($storageFolder, $imageID, $modelName){
        try {
            $imageName = time() . Str::random(8) . '.' . request()->image->extension();
            //move the image to the storage folder
            request()->image->move(storage_path('app/public/' . $storageFolder), $imageName);

            //store image data to database
            return Image::create([
                'image_name' => $imageName,
                'image_path'=>'/'.$storageFolder.'/'.$imageName,
                'imageable_id' => $imageID,
                'imageable_type' => $modelName,
            ]);
        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
        }
    }

    //Add image
    public function updateImage($imageData, $storageFolder)
    {
        $imagePath = storage_path('app/public/' . $storageFolder . '/' . $imageData->image_name);
        if (file_exists($imagePath)) {
            //Remove the image from the storage folder
            unlink($imagePath);
            try {
                $imageName = time() . Str::random(8) . '.' . request()->image->extension();
                //move the image to the storage folder
                request()->image->move(storage_path('app/public/' . $storageFolder), $imageName);

                //Update image info
                $imageData->update([
                    'image_name'=>$imageName,
                    'image_path'=>'/'.$storageFolder.'/'.$imageName
                ]);
            } catch (\Exception $exception) {
                Log::info($exception->getMessage());
            }
        }
    }
}
