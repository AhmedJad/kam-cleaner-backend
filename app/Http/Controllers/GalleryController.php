<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGalleryRequest;
use App\Http\Requests\UpdateGalleryRequest;
use App\Models\User;
use App\Repositories\GalleryRepository;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    private $galleryRepository;
    public function __construct(GalleryRepository $galleryRepository)
    {
        $this->galleryRepository = $galleryRepository;
    }
    public function store(StoreGalleryRequest $request)
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        $image = $request->file("image")->store("");
        $request->merge(["image" => $image]);
        $gallery = $this->galleryRepository->store($request->input());
        return $gallery;
    }
    public function update(UpdateGalleryRequest $request)
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        $image = "";
        if ($request->file("image")) {
            $image = $request->file("image")->store("");
            $request->merge(["image" => $image]);
        }
        $updateResult = $this->galleryRepository->update($request->input());
        if ($request->file("image")) {
            Storage::delete($updateResult["old_image"]);
        }
        return $updateResult["updated_gallery"];
    }
    public function delete($id)
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        $oldImage = $this->galleryRepository->delete($id);
        if ($oldImage) {
            Storage::delete($oldImage);
        }
    }
    //This end point for dashboard and front 
    public function index()
    {
        return $this->galleryRepository->getPage(request()->page_size);
    }
}
