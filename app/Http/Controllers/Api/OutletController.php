<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OutletRequest;
use App\Http\Resources\OutletResource;
use App\Models\Image;
use App\Models\Outlet;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OutletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $outlets = Outlet::with('image')->get();
            $data = OutletResource::collection($outlets);
            return response()->sendSuccess($data, 'Outlets List');
        }catch (\Exception $exception){
            return \response()->sendErrorWithException($exception, 'OPPS! Something Wrong', 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OutletRequest $request, ImageUploadService $service)
    {
        try{
            DB::beginTransaction();
            $outletData = $request->except('image');
            $outlet = Outlet::create($outletData);

            //store image
            if(request()->hasFile('image')){
                $service->addImage('outlet', $outlet->id, Outlet::class);
            }
            DB::commit();
            $data = new OutletResource($outlet);
            return response()->sendSuccess($data, 'Outlet Created Successfully', 201);
        }catch (\Exception $exception){
            DB::rollBack();
            return \response()->sendErrorWithException($exception, 'OPPS! Something Wrong', 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Outlet $outlet, ImageUploadService $service)
    {
        try{
            DB::beginTransaction();
            $outletData = $request->except('image');
            $outlet->update($outletData);

            //image upload
            if ($request->hasFile('image')) {
                $image = Image::where(['imageable_id'=>$outlet->id, 'imageable_type'=>Outlet::class])->first();
                if(isset($image)){
                    $service->updateImage($image, 'outlet');
                }else{
                    $service->addImage('outlet', $outlet->id, Outlet::class);
                }
            }
            DB::commit();
            $data = new OutletResource($outlet);
            return response()->sendSuccess($data, 'Info Updated Successfully', 200);
        }catch (\Exception $exception){
            DB::rollBack();
            return \response()->sendErrorWithException($exception, 'OPPS! Something Wrong', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Outlet $outlet)
    {
        try{
            DB::beginTransaction();
            $outlet->delete();
            $image = Image::where(['imageable_id'=>$outlet->id, 'imageable_type'=>Outlet::class])->first();
            $filePath = storage_path('app/public/outlet/'. $image->image_name);

            //delete the image from the storage folder
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            //delete image data from database
            $image->delete();
            DB::commit();
            $data = new OutletResource($outlet);
            return response()->sendSuccess($data, 'Outlet Deleted Successfully', 200);
        }catch (\Exception $exception){
            DB::rollBack();
            return \response()->sendErrorWithException($exception, 'OPPS! Something Wrong', 500);
        }
    }
}
